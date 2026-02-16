<?php
namespace app\controllers;

use App\models\Besoin;
use App\models\Don;
use App\models\Distribution;
use flight\Engine;

class SimulationController {
    private $db;
    private $app;

    public function __construct(\PDO $db, Engine $app) {
        $this->db = $db;
        $this->app = $app;
    }

    /**
     * Afficher la page de simulation avec dispatch automatique
     */
    public function afficherSimulation() {
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);
        $distributionModel = new Distribution($this->db);
        
        // Récupérer les besoins non satisfaits et dons disponibles
        $besoins = $besoinModel->getBesoinsNonSatisfaits();
        $dons = $donModel->getDonsDisponibles();
        
        // Exécuter l'algorithme de dispatch automatique
        $distributionsProposees = $this->executerDispatchAutomatique($besoins, $dons);
        
        // Calculer les statistiques
        $stats = [
            'total_besoins' => count($besoins),
            'total_dons' => count($dons),
            'total_distributions' => count($distributionsProposees),
            'taux_satisfaction' => 0
        ];
        
        // Calculer le taux de satisfaction
        if (count($besoins) > 0) {
            $besoins_satisfaits = 0;
            foreach ($distributionsProposees as $dist) {
                $besoin = $this->trouverBesoin($besoins, $dist['idBesoin']);
                if ($besoin && $dist['quantite_attribuee'] >= $besoin['quantite_restante']) {
                    $besoins_satisfaits++;
                }
            }
            $stats['taux_satisfaction'] = round(($besoins_satisfaits / count($besoins)) * 100);
        }
        
        // Récupérer les messages flash
        $success = $_SESSION['success_message'] ?? null;
        $error = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);
        
        // Afficher la vue
        $this->app->render('simulation', [
            'besoins' => $besoins,
            'dons' => $dons,
            'distributions' => $distributionsProposees,
            'stats' => $stats,
            'success' => $success,
            'error' => $error
        ]);
    }

    /**
     * Confirmer et enregistrer le dispatch en base de données
     */
    public function confirmerDispatch() {
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);
        $distributionModel = new Distribution($this->db);
        
        try {
            // Début de transaction
            $this->db->beginTransaction();
            
            // Récupérer les besoins et dons
            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            $dons = $donModel->getDonsDisponibles();
            
            // Exécuter le dispatch
            $distributionsProposees = $this->executerDispatchAutomatique($besoins, $dons);
            
            // Enregistrer chaque distribution
            $count = 0;
            foreach ($distributionsProposees as $dist) {
                $data = [
                    'idBesoin' => $dist['idBesoin'],
                    'idDon' => $dist['idDon'],
                    'quantite' => $dist['quantite_attribuee'],
                    'idStatus' => 1, // Status "En attente" ou "Effectué" selon votre base
                    'dateDistribution' => date('Y-m-d H:i:s')
                ];
                
                if ($distributionModel->create($data)) {
                    $count++;
                }
            }
            
            // Valider la transaction
            $this->db->commit();
            
            $_SESSION['success_message'] = "$count distribution(s) ont été créées avec succès !";
            
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
            $_SESSION['error_message'] = "Erreur lors de la création des distributions : " . $e->getMessage();
        }
        
        // Rediriger vers la page de simulation
        $this->app->redirect('/simulation');
    }

    /**
     * Algorithme de dispatch automatique
     * Match les besoins avec les dons par produit et par ordre chronologique
     */
    private function executerDispatchAutomatique($besoins, $dons) {
        $distributions = [];
        
        // Parcourir chaque besoin (déjà trié par date ASC)
        foreach ($besoins as $besoin) {
            $quantite_restante_besoin = $besoin['quantite_restante'];
            
            if ($quantite_restante_besoin <= 0) {
                continue;
            }
            
            // Chercher des dons correspondants (même produit)
            foreach ($dons as &$don) {
                // Vérifier que c'est le même produit
                if ($don['idProduit'] != $besoin['idProduit']) {
                    continue;
                }
                
                // Vérifier qu'il reste de la quantité dans le don
                if ($don['quantite_restante'] <= 0) {
                    continue;
                }
                
                // Calculer la quantité à attribuer (minimum entre besoin et don restants)
                $quantite_a_attribuer = min($quantite_restante_besoin, $don['quantite_restante']);
                
                // Créer la distribution
                $distributions[] = [
                    'idBesoin' => $besoin['id'],
                    'idDon' => $don['id'],
                    'ville_nom' => $besoin['ville_nom'],
                    'produit_nom' => $besoin['produit_nom'],
                    'besoin_quantite_demandee' => $besoin['quantite'],
                    'besoin_quantite_restante' => $quantite_restante_besoin,
                    'donateur_nom' => $don['donateur_nom'],
                    'don_quantite_disponible' => $don['quantite_restante'],
                    'quantite_attribuee' => $quantite_a_attribuer,
                    'dateBesoin' => $besoin['dateBesoin'],
                    'dateDon' => $don['dateDon'],
                    'dateDistribution' => date('Y-m-d')
                ];
                
                // Mettre à jour les quantités restantes
                $quantite_restante_besoin -= $quantite_a_attribuer;
                $don['quantite_restante'] -= $quantite_a_attribuer;
                
                // Si le besoin est satisfait, passer au suivant
                if ($quantite_restante_besoin <= 0) {
                    break;
                }
            }
        }
        
        return $distributions;
    }

    /**
     * Trouver un besoin par son ID
     */
    private function trouverBesoin($besoins, $idBesoin) {
        foreach ($besoins as $besoin) {
            if ($besoin['id'] == $idBesoin) {
                return $besoin;
            }
        }
        return null;
    }
}
