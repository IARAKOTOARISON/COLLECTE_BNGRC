<?php
namespace app\controllers;

use app\models\Besoin;
use app\models\Don;
use app\models\Distribution;
use flight\Engine;

/**
 * Contrôleur de Dispatch des Dons
 * 
 * Centralise les trois méthodes de répartition des dons :
 * 1. Par date : priorité aux demandes les plus anciennes
 * 2. Par quantité : priorité aux demandes les plus petites
 * 3. Par proportionnalité : répartition au prorata des demandes
 */
class DispatchController extends BaseController {

    /**
     * Réinitialiser les états des tables don, besoin, distribution depuis les historiques
     */
    public function reinitialiserEtatsDepuisHistorique(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        try {
            $this->db->beginTransaction();


            // 1. Réinitialiser table besoin
            $this->db->exec('UPDATE besoin b JOIN historique_besoin h ON b.id = h.id SET b.idStatus = h.idStatus, b.quantite = h.quantite, b.dateBesoin = h.dateBesoin');

            // 2. Réinitialiser table don
            $this->db->exec('UPDATE don d JOIN historique_don h ON d.id = h.id SET d.idStatus = h.idStatus, d.quantite = h.quantite, d.montant = h.montant');

            // 3. Réinitialiser table distribution
            $this->db->exec('UPDATE distribution d JOIN historique_distribution h ON d.id = h.id SET d.idStatusDistribution = h.idStatusDistribution, d.quantite = h.quantite, d.montant = h.montant, d.dateDistribution = h.dateDistribution');

            $this->db->commit();
            $_SESSION['reinit_success'] = true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['error_message'] = 'Erreur lors de la réinitialisation : ' . $e->getMessage();
        }
        $baseUrl = $this->getBaseUrl();
        header('Location: ' . $baseUrl . '/dispatch');
        exit;
    }

    /**
     * Afficher la page de dispatch unifiée
     */
    public function afficherDispatch(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);

        // Récupérer les besoins non satisfaits et dons disponibles
        $besoins = $besoinModel->getBesoinsNonSatisfaits();
        $dons = $donModel->getDonsDisponibles();

        // Méthode par défaut ou depuis la session
        $methode = $_GET['methode'] ?? $_SESSION['dispatch_methode'] ?? 'date';
        $_SESSION['dispatch_methode'] = $methode;

        // Exécuter le dispatch selon la méthode choisie
        $distributions = match($methode) {
            'quantite' => $this->dispatchByQuantity($besoins, $dons),
            'proportionnalite' => $this->dispatchByProportionality($besoins, $dons),
            default => $this->dispatchByDate($besoins, $dons),
        };

        // Calculer les statistiques
        $stats = $this->calculerStatistiques($besoins, $distributions);

        // Récupérer les messages flash

        $success = $_SESSION['success_message'] ?? null;
        $error = $_SESSION['error_message'] ?? null;
        $reinit_success = $_SESSION['reinit_success'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message'], $_SESSION['reinit_success']);

        $this->app->render('dispatch', [
            'besoins' => $besoins,
            'dons' => $dons,
            'distributions' => $distributions,
            'stats' => $stats,
            'methode' => $methode,
            'success' => $success,
            'error' => $error,
            'reinit_success' => $reinit_success,
            'baseUrl' => $this->getBaseUrl(),
            'nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * API: Lancer une simulation selon la méthode choisie (JSON)
     */
    public function lancerDispatch(): void {
        try {
            $besoinModel = new Besoin($this->db);
            $donModel = new Don($this->db);

            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            $dons = $donModel->getDonsDisponibles();

            // Récupérer la méthode depuis GET ou POST
            $methode = $_GET['methode'] ?? $_POST['methode'] ?? 'date';

            // Exécuter le dispatch selon la méthode
            $distributions = match($methode) {
                'quantite' => $this->dispatchByQuantity($besoins, $dons),
                'proportionnalite' => $this->dispatchByProportionality($besoins, $dons),
                default => $this->dispatchByDate($besoins, $dons),
            };

            $stats = $this->calculerStatistiques($besoins, $distributions);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'methode' => $methode,
                'distributions' => $distributions,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Valider et persister le dispatch en base de données
     */
    public function validerDispatch(): void {
        try {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $distributionModel = new Distribution($this->db);
            $besoinModel = new Besoin($this->db);
            $donModel = new Don($this->db);

            // Récupérer les propositions depuis POST
            $payload = $_POST['distributions'] ?? null;
            $methode = $_POST['methode'] ?? 'date';

            if ($payload) {
                $distributionsProposees = json_decode($payload, true);
                if (!is_array($distributionsProposees)) {
                    throw new \Exception('Format de distributions invalide');
                }
            } else {
                // Recalculer si pas de données
                $besoins = $besoinModel->getBesoinsNonSatisfaits();
                $dons = $donModel->getDonsDisponibles();
                
                $distributionsProposees = match($methode) {
                    'quantite' => $this->dispatchByQuantity($besoins, $dons),
                    'proportionnalite' => $this->dispatchByProportionality($besoins, $dons),
                    default => $this->dispatchByDate($besoins, $dons),
                };
            }

            if (empty($distributionsProposees)) {
                throw new \Exception('Aucune distribution à valider');
            }

            // Début de transaction
            $this->db->beginTransaction();

            // ÉTAPE 1: Récupérer et enregistrer l'état actuel dans l'historique
            // pour chaque besoin, don et distribution impliqués
            $besoinsToSave = [];
            $donsToSave = [];
            
            foreach ($distributionsProposees as $dist) {
                // Ajouter les IDs des besoins et dons à sauvegarder
                $besoinsToSave[$dist['idBesoin']] = true;
                $donsToSave[$dist['idDon']] = true;
            }

            // Sauvegarder les besoins impliqués dans l'historique
            foreach (array_keys($besoinsToSave) as $idBesoin) {
                $besoinData = $besoinModel->getBesoinById($idBesoin);
                if ($besoinData) {
                    $besoinModel->saveToHistorique($besoinData);
                }
            }

            // Sauvegarder les dons impliqués dans l'historique
            foreach (array_keys($donsToSave) as $idDon) {
                $donData = $donModel->getDonById($idDon);
                if ($donData) {
                    $donModel->saveToHistorique($donData);
                }
            }

            // ÉTAPE 2: Créer les distributions et les enregistrer dans l'historique
            $count = 0;
            foreach ($distributionsProposees as $dist) {
                $data = [
                    'idBesoin' => $dist['idBesoin'],
                    'idVille' => $dist['idVille'],
                    'idDon' => $dist['idDon'],
                    'quantite' => $dist['quantite_attribuee'],
                    'idStatusDistribution' => 2, // Effectué
                    'dateDistribution' => date('Y-m-d H:i:s')
                ];

                $distributionId = $distributionModel->create($data);
                if ($distributionId) {
                    // Ajouter l'ID généré aux données
                    $data['id'] = $distributionId;
                    // Sauvegarder la distribution dans l'historique avec son ID
                    $distributionModel->saveToHistorique($data);
                    $count++;
                }
            }

            // Mettre à jour les statuts des besoins
            $this->mettreAJourStatutsBesoins($besoinModel);

            // Mettre à jour les statuts des dons
            $this->mettreAJourStatutsDons($donModel);

            $this->db->commit();

            $_SESSION['success_message'] = "$count distribution(s) créée(s) avec succès via la méthode '$methode' !";

        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['error_message'] = "Erreur: " . $e->getMessage();
        }

        $baseUrl = $this->getBaseUrl();
        $this->app->redirect($baseUrl . '/dispatch');
    }

    // =========================================================================
    // MÉTHODE 1: DISPATCH PAR DATE
    // La ville qui a déposé sa demande en premier reçoit son don en priorité
    // =========================================================================

    /**
     * Dispatch par date : priorité aux demandes les plus anciennes
     * 
     * @param array $besoins Besoins non satisfaits
     * @param array $dons Dons disponibles
     * @return array Distributions proposées
     */
    public function dispatchByDate(array $besoins, array $dons): array {
        $distributions = [];

        // Trier les besoins par date croissante (les plus anciens en premier)
        usort($besoins, function($a, $b) {
            return strtotime($a['dateBesoin']) - strtotime($b['dateBesoin']);
        });

        // Trier les dons par date croissante (les plus anciens utilisés en premier)
        usort($dons, function($a, $b) {
            return strtotime($a['dateDon']) - strtotime($b['dateDon']);
        });

        // Créer une copie des quantités restantes des dons
        $donsRestants = [];
        foreach ($dons as $don) {
            $donsRestants[$don['id']] = [
                'don' => $don,
                'quantite_restante' => $don['quantite_restante']
            ];
        }

        // Parcourir chaque besoin par ordre de date
        foreach ($besoins as $besoin) {
            $quantite_besoin_restante = $besoin['quantite_restante'];

            if ($quantite_besoin_restante <= 0) {
                continue;
            }

            // Chercher des dons correspondants (même produit)
            foreach ($donsRestants as $idDon => &$donData) {
                $don = $donData['don'];

                // Vérifier même produit
                if ($don['idProduit'] != $besoin['idProduit']) {
                    continue;
                }

                // Vérifier quantité disponible
                if ($donData['quantite_restante'] <= 0) {
                    continue;
                }

                // Calculer quantité à attribuer
                $quantite_a_attribuer = min($quantite_besoin_restante, $donData['quantite_restante']);

                // Créer la distribution
                $distributions[] = [
                    'idBesoin' => $besoin['id'],
                    'idVille' => $besoin['idVille'],
                    'idDon' => $don['id'],
                    'idProduit' => $besoin['idProduit'],
                    'ville_nom' => $besoin['ville_nom'],
                    'produit_nom' => $besoin['produit_nom'],
                    'besoin_quantite_demandee' => $besoin['quantite'],
                    'besoin_quantite_restante' => $besoin['quantite_restante'],
                    'donateur_nom' => $don['donateur_nom'],
                    'don_quantite_disponible' => $don['quantite_restante'],
                    'quantite_attribuee' => $quantite_a_attribuer,
                    'dateBesoin' => $besoin['dateBesoin'],
                    'dateDon' => $don['dateDon'],
                    'dateDistribution' => date('Y-m-d'),
                    'methode' => 'date'
                ];

                // Mettre à jour les quantités
                $quantite_besoin_restante -= $quantite_a_attribuer;
                $donData['quantite_restante'] -= $quantite_a_attribuer;

                if ($quantite_besoin_restante <= 0) {
                    break;
                }
            }
        }

        return $distributions;
    }

    // =========================================================================
    // MÉTHODE 2: DISPATCH PAR QUANTITÉ
    // La ville qui a demandé le moins de dons est servie en premier
    // =========================================================================

    /**
     * Dispatch par quantité : priorité aux demandes les plus petites
     * 
     * @param array $besoins Besoins non satisfaits
     * @param array $dons Dons disponibles
     * @return array Distributions proposées
     */
    public function dispatchByQuantity(array $besoins, array $dons): array {
        $distributions = [];

        // Trier les besoins par quantité restante croissante (les plus petits en premier)
        usort($besoins, function($a, $b) {
            return $a['quantite_restante'] - $b['quantite_restante'];
        });

        // Trier les dons par date (pour cohérence)
        usort($dons, function($a, $b) {
            return strtotime($a['dateDon']) - strtotime($b['dateDon']);
        });

        // Créer une copie des quantités restantes des dons
        $donsRestants = [];
        foreach ($dons as $don) {
            $donsRestants[$don['id']] = [
                'don' => $don,
                'quantite_restante' => $don['quantite_restante']
            ];
        }

        // Parcourir chaque besoin par ordre de quantité (les plus petits d'abord)
        foreach ($besoins as $besoin) {
            $quantite_besoin_restante = $besoin['quantite_restante'];

            if ($quantite_besoin_restante <= 0) {
                continue;
            }

            // Chercher des dons correspondants (même produit)
            foreach ($donsRestants as $idDon => &$donData) {
                $don = $donData['don'];

                if ($don['idProduit'] != $besoin['idProduit']) {
                    continue;
                }

                if ($donData['quantite_restante'] <= 0) {
                    continue;
                }

                $quantite_a_attribuer = min($quantite_besoin_restante, $donData['quantite_restante']);

                $distributions[] = [
                    'idBesoin' => $besoin['id'],
                    'idVille' => $besoin['idVille'],
                    'idDon' => $don['id'],
                    'idProduit' => $besoin['idProduit'],
                    'ville_nom' => $besoin['ville_nom'],
                    'produit_nom' => $besoin['produit_nom'],
                    'besoin_quantite_demandee' => $besoin['quantite'],
                    'besoin_quantite_restante' => $besoin['quantite_restante'],
                    'donateur_nom' => $don['donateur_nom'],
                    'don_quantite_disponible' => $don['quantite_restante'],
                    'quantite_attribuee' => $quantite_a_attribuer,
                    'dateBesoin' => $besoin['dateBesoin'],
                    'dateDon' => $don['dateDon'],
                    'dateDistribution' => date('Y-m-d'),
                    'methode' => 'quantite'
                ];

                $quantite_besoin_restante -= $quantite_a_attribuer;
                $donData['quantite_restante'] -= $quantite_a_attribuer;

                if ($quantite_besoin_restante <= 0) {
                    break;
                }
            }
        }

        return $distributions;
    }

    // =========================================================================
    // MÉTHODE 3: DISPATCH PAR PROPORTIONNALITÉ
    // Les dons disponibles sont répartis au prorata des demandes
    // =========================================================================

    /**
     * Dispatch par proportionnalité : répartition au prorata des demandes
     * 
     * Calcule ratio = total_dons_disponibles / total_demandes
     * Puis applique ce pourcentage à chaque demande
     * 
     * @param array $besoins Besoins non satisfaits
     * @param array $dons Dons disponibles
     * @return array Distributions proposées
     */
    public function dispatchByProportionality(array $besoins, array $dons): array {
        $distributions = [];

        // Regrouper les besoins et dons par produit
        $besoinsByProduit = [];
        $donsByProduit = [];

        foreach ($besoins as $besoin) {
            $idProduit = $besoin['idProduit'];
            if (!isset($besoinsByProduit[$idProduit])) {
                $besoinsByProduit[$idProduit] = [];
            }
            $besoinsByProduit[$idProduit][] = $besoin;
        }

        foreach ($dons as $don) {
            $idProduit = $don['idProduit'];
            if ($idProduit === null) continue; // Ignorer dons financiers ici
            if (!isset($donsByProduit[$idProduit])) {
                $donsByProduit[$idProduit] = [
                    'total' => 0,
                    'dons' => []
                ];
            }
            $donsByProduit[$idProduit]['total'] += $don['quantite_restante'];
            $donsByProduit[$idProduit]['dons'][] = $don;
        }

        // Pour chaque produit, calculer le ratio et distribuer proportionnellement
        foreach ($besoinsByProduit as $idProduit => $besoinsP) {
            // Total des demandes pour ce produit
            $totalDemandes = array_sum(array_column($besoinsP, 'quantite_restante'));
            
            // Total des dons disponibles pour ce produit
            $totalDons = $donsByProduit[$idProduit]['total'] ?? 0;

            if ($totalDemandes <= 0 || $totalDons <= 0) {
                continue;
            }

            // Calculer le ratio (ne pas dépasser 100%)
            $ratio = min(1, $totalDons / $totalDemandes);

            // Copie des dons pour ce produit
            $donsRestants = [];
            foreach (($donsByProduit[$idProduit]['dons'] ?? []) as $don) {
                $donsRestants[$don['id']] = [
                    'don' => $don,
                    'quantite_restante' => $don['quantite_restante']
                ];
            }

            // Trier besoins par date pour équité
            usort($besoinsP, function($a, $b) {
                return strtotime($a['dateBesoin']) - strtotime($b['dateBesoin']);
            });

            // Attribuer proportionnellement à chaque besoin
            foreach ($besoinsP as $besoin) {
                // Quantité à attribuer = besoin * ratio (arrondi à l'entier inférieur)
                $quantite_prorata = floor($besoin['quantite_restante'] * $ratio);

                if ($quantite_prorata <= 0) {
                    continue;
                }

                $quantite_a_distribuer = $quantite_prorata;

                // Distribuer depuis les dons disponibles
                foreach ($donsRestants as $idDon => &$donData) {
                    if ($quantite_a_distribuer <= 0) break;
                    if ($donData['quantite_restante'] <= 0) continue;

                    $don = $donData['don'];
                    $quantite_attribuee = min($quantite_a_distribuer, $donData['quantite_restante']);

                    $distributions[] = [
                        'idBesoin' => $besoin['id'],
                        'idVille' => $besoin['idVille'],
                        'idDon' => $don['id'],
                        'idProduit' => $besoin['idProduit'],
                        'ville_nom' => $besoin['ville_nom'],
                        'produit_nom' => $besoin['produit_nom'],
                        'besoin_quantite_demandee' => $besoin['quantite'],
                        'besoin_quantite_restante' => $besoin['quantite_restante'],
                        'donateur_nom' => $don['donateur_nom'],
                        'don_quantite_disponible' => $don['quantite_restante'],
                        'quantite_attribuee' => $quantite_attribuee,
                        'ratio_applique' => round($ratio * 100, 1),
                        'dateBesoin' => $besoin['dateBesoin'],
                        'dateDon' => $don['dateDon'],
                        'dateDistribution' => date('Y-m-d'),
                        'methode' => 'proportionnalite'
                    ];

                    $quantite_a_distribuer -= $quantite_attribuee;
                    $donData['quantite_restante'] -= $quantite_attribuee;
                }
            }
        }

        return $distributions;
    }

    // =========================================================================
    // MÉTHODES UTILITAIRES
    // =========================================================================

    /**
     * Calculer les statistiques de dispatch
     */
    private function calculerStatistiques(array $besoins, array $distributions): array {
        $stats = [
            'total_besoins' => count($besoins),
            'total_dons' => 0,
            'total_distributions' => count($distributions),
            'quantite_totale_demandee' => 0,
            'quantite_totale_attribuee' => 0,
            'taux_satisfaction' => 0,
            'besoins_completement_satisfaits' => 0,
            'besoins_partiellement_satisfaits' => 0
        ];

        // Calculer quantité totale demandée
        foreach ($besoins as $b) {
            $stats['quantite_totale_demandee'] += $b['quantite_restante'];
        }

        // Calculer quantité totale attribuée et besoins satisfaits
        $besoinsTraites = [];
        foreach ($distributions as $dist) {
            $stats['quantite_totale_attribuee'] += $dist['quantite_attribuee'];
            
            $idBesoin = $dist['idBesoin'];
            if (!isset($besoinsTraites[$idBesoin])) {
                $besoinsTraites[$idBesoin] = [
                    'quantite_restante' => $dist['besoin_quantite_restante'],
                    'quantite_attribuee' => 0
                ];
            }
            $besoinsTraites[$idBesoin]['quantite_attribuee'] += $dist['quantite_attribuee'];
        }

        // Compter besoins satisfaits
        foreach ($besoinsTraites as $bt) {
            if ($bt['quantite_attribuee'] >= $bt['quantite_restante']) {
                $stats['besoins_completement_satisfaits']++;
            } else {
                $stats['besoins_partiellement_satisfaits']++;
            }
        }

        // Calculer taux de satisfaction
        if ($stats['quantite_totale_demandee'] > 0) {
            $stats['taux_satisfaction'] = round(
                ($stats['quantite_totale_attribuee'] / $stats['quantite_totale_demandee']) * 100
            );
        }

        return $stats;
    }

    /**
     * Mettre à jour les statuts des besoins après dispatch
     */
    private function mettreAJourStatutsBesoins(Besoin $besoinModel): void {
        $stmt = $this->db->prepare("
            SELECT b.id, b.quantite, COALESCE(SUM(d.quantite), 0) AS distribue 
            FROM besoin b 
            LEFT JOIN distribution d ON b.id = d.idBesoin 
            GROUP BY b.id
        ");
        $stmt->execute();
        $besoinsEtat = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($besoinsEtat as $b) {
            $reste = $b['quantite'] - $b['distribue'];
            // 1 = En attente, 2 = Partiel, 3 = Satisfait
            $status = ($reste <= 0) ? 3 : (($b['distribue'] > 0) ? 2 : 1);
            $besoinModel->update($b['id'], ['idStatus' => $status]);
        }
    }

    /**
     * Mettre à jour les statuts des dons après dispatch
     */
    private function mettreAJourStatutsDons(Don $donModel): void {
        $stmt = $this->db->prepare("
            SELECT don.id, don.quantite, don.montant, don.idProduit, 
                   COALESCE(SUM(distr.quantite), 0) AS distribue 
            FROM don 
            LEFT JOIN distribution distr ON don.id = distr.idDon 
            GROUP BY don.id
        ");
        $stmt->execute();
        $donsEtat = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($donsEtat as $dn) {
            $baseValeur = ($dn['idProduit'] !== null) ? ($dn['quantite'] ?? 0) : ($dn['montant'] ?? 0);
            $resteDon = $baseValeur - $dn['distribue'];
            // 1 = Disponible, 2 = Partiel, 3 = Distribué
            $statusDon = ($resteDon <= 0) ? 3 : (($dn['distribue'] > 0) ? 2 : 1);
            $donModel->update($dn['id'], ['idStatus' => $statusDon]);
        }
    }
}
