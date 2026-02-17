<?php
namespace app\controllers;

use app\models\Achat;
use app\models\AchatDetails;
use app\models\Besoin;
use app\models\Don;
use app\models\AchatAutoService;
use app\models\Ville;
use flight\Engine;

class AchatController extends BaseController {
    private Achat $achatModel;
    private AchatDetails $achatDetailsModel;
    private Besoin $besoinModel;
    private Don $donModel;
    private AchatAutoService $achatService;

    public function __construct(\PDO $db, Engine $app) {
        parent::__construct($db, $app);
        $this->achatModel = new Achat($db);
        $this->achatDetailsModel = new AchatDetails($db);
        $this->besoinModel = new Besoin($db);
        $this->donModel = new Don($db);
        $this->achatService = new AchatAutoService($db);
    }

    /**
     * Afficher la page principale des achats
     */
    public function afficherPageAchats(): void {
        $achats = $this->achatModel->getAllAchats();
        
        // Charger les villes pour le filtre
        $villeModel = new Ville($this->db);
        $villes = $villeModel->getAllVilles();

        // Appliquer le filtre par ville si présent
        $idVille = isset($_GET['ville']) ? (int)$_GET['ville'] : null;
        if ($idVille) {
            $achats = $this->achatModel->getAchatsByVille($idVille);
        }

        $success = $_SESSION['success_message'] ?? null;
        $error = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        // render the `achatListe` view we created earlier
        $this->app->render('achatListe', [
            'achats' => $achats,
            'villes' => $villes,
            'villeSelectionnee' => $idVille,
            'success' => $success,
            'error' => $error,
            'baseUrl' => $this->getBaseUrl()
        ]);
    }

    /**
     * Retourne la liste des achats (JSON) — utile pour API/AJAX
     */
    public function getListeAchats(): void {
        $achats = $this->achatModel->getAllAchats();
        header('Content-Type: application/json');
        echo json_encode($achats);
        exit;
    }

    /**
     * Afficher la page listant les besoins restants pour proposition d'achat
     */
    public function getBesoinsRestantsPage(): void {
        $besoins = $this->besoinModel->getBesoinsRestants();

        $this->app->render('besoinsRestantsPourAchat', [
            'besoins' => $besoins,
            'baseUrl' => $this->getBaseUrl()
        ]);
    }

    /**
     * Proposer automatiquement des achats pour les besoins prioritaires
     * Affiche une page HTML avec les propositions d'achat
     */
    public function proposerAchatsAuto(): void {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        $besoins = $this->achatService->getBesoinsPrioritaires($limit);
        
        // Récupérer les dons en argent disponibles
        $donsArgent = $this->donModel->getDonsArgentDisponibles();
        $totalDonsArgent = array_sum(array_column($donsArgent, 'montant_restant'));
        
        // Récupérer le taux de frais depuis les paramètres
        $stmt = $this->db->query("SELECT valeur FROM parametres WHERE cle = 'frais_achat_pourcent'");
        $fraisPourcent = (float)($stmt->fetchColumn() ?: 10);
        
        $propositions = [];
        foreach ($besoins as $b) {
            $cout = $this->achatService->calculerCoutTotal($b);
            $frais = $cout * ($fraisPourcent / 100);
            $total = $cout + $frais;
            
            $propositions[] = [
                'idBesoin' => $b['id'] ?? null,
                'idVille' => $b['idVille'] ?? null,
                'ville_nom' => $b['ville_nom'] ?? '',
                'idProduit' => $b['idProduit'] ?? null,
                'produit_nom' => $b['produit_nom'] ?? '',
                'quantite' => $b['quantite'] ?? 0,
                'prixUnitaire' => $b['prixUnitaire'] ?? 0,
                'coutEstime' => $cout,
                'frais' => $frais,
                'total' => $total,
                'dateBesoin' => $b['dateBesoin'] ?? '',
            ];
        }

        $success = $_SESSION['success_message'] ?? null;
        $error = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->app->render('achatProposition', [
            'propositions' => $propositions,
            'donsArgent' => $donsArgent,
            'totalDonsArgent' => $totalDonsArgent,
            'fraisPourcent' => $fraisPourcent,
            'success' => $success,
            'error' => $error,
            'baseUrl' => $this->getBaseUrl()
        ]);
    }
    
    /**
     * API JSON pour les propositions d'achat (utilisé par AJAX)
     */
    public function getPropositionsAchatsJson(): void {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        $besoins = $this->achatService->getBesoinsPrioritaires($limit);
        $propositions = [];

        foreach ($besoins as $b) {
            $cout = $this->achatService->calculerCoutTotal($b);
            $dons = $this->achatService->verifierDonsDisponibles($b);
            $propositions[] = [
                'idBesoin' => $b['id'] ?? null,
                'idProduit' => $b['idProduit'] ?? null,
                'quantite' => $b['quantite'] ?? 0,
                'coutEstime' => $cout,
                'donsDisponibles' => $dons,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($propositions);
        exit;
    }

    /**
     * Valider et exécuter les achats automatiques sélectionnés.
     * Accepts either:
     * - POST['propositions'] = JSON array of objects { idBesoin, idDon }
     * - POST['besoin_ids'] = array of besoin IDs (from HTML form)
     */
    public function validerAchatsAuto(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        try {
            // Check for HTML form submission (besoin_ids array)
            $besoinIds = $_POST['besoin_ids'] ?? null;
            
            if ($besoinIds && is_array($besoinIds)) {
                // Handle HTML form submission
                $this->executerAchatsDepuisFormulaire($besoinIds);
                return;
            }
            
            // Handle JSON propositions (legacy/API)
            $payload = $_POST['propositions'] ?? null;
            if (is_null($payload)) throw new \Exception('Aucune proposition reçue.');

            $propositions = json_decode($payload, true);
            if (!is_array($propositions)) throw new \Exception('Format de propositions invalide.');

            $createdAchatIds = [];

            foreach ($propositions as $p) {
                $idBesoin = $p['idBesoin'] ?? null;
                $idDon = $p['idDon'] ?? null;
                if (empty($idBesoin) || empty($idDon)) continue;

                $idAchat = $this->achatService->acheterBesoin($this->besoinModel->getById($idBesoin), (int)$idDon);
                if ($idAchat) {
                    $createdAchatIds[] = $idAchat;
                    // Créer une distribution minimale liant le besoin à l'achat (quantité = besoin.quantite)
                    $besoin = $this->besoinModel->getById($idBesoin);
                    $mapping = [[
                        'idBesoin' => $idBesoin,
                        'idDon' => $idDon,
                        'idVille' => $besoin['idVille'] ?? 1,
                        'quantite' => $besoin['quantite'] ?? 0,
                        'idStatusDistribution' => 2,
                        'dateDistribution' => date('Y-m-d H:i:s')
                    ]];
                    $this->achatService->creerDistributionDepuisAchat($idAchat, $mapping);
                }
            }

            $_SESSION['success_message'] = 'Achats automatiques exécutés: ' . count($createdAchatIds);
        } catch (\Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        $this->app->redirect($this->getBaseUrl() . '/achats');
    }
    
    /**
     * Exécuter les achats depuis le formulaire HTML (besoin_ids)
     */
    private function executerAchatsDepuisFormulaire(array $besoinIds): void {
        try {
            // Récupérer les dons en argent disponibles
            $donsArgent = $this->donModel->getDonsArgentDisponibles();
            if (empty($donsArgent)) {
                throw new \Exception('Aucun don financier disponible pour effectuer des achats.');
            }
            
            // Récupérer le taux de frais
            $stmt = $this->db->query("SELECT valeur FROM parametres WHERE cle = 'frais_achat_pourcent'");
            $fraisPourcent = (float)($stmt->fetchColumn() ?: 10);
            
            $createdCount = 0;
            $donIndex = 0;
            $montantRestantDon = (float)($donsArgent[$donIndex]['montant_restant'] ?? 0);
            $idDonCourant = (int)($donsArgent[$donIndex]['id'] ?? 0);
            
            $this->db->beginTransaction();
            
            foreach ($besoinIds as $idBesoin) {
                $besoin = $this->besoinModel->getById((int)$idBesoin);
                if (!$besoin) continue;
                
                // Calculer le coût total avec frais
                $prixUnitaire = (float)($besoin['prixUnitaire'] ?? 0);
                $quantite = (float)($besoin['quantite'] ?? 0);
                $cout = $prixUnitaire * $quantite;
                $frais = $cout * ($fraisPourcent / 100);
                $total = $cout + $frais;
                
                // Vérifier si on a assez de budget dans le don courant
                while ($montantRestantDon < $total && $donIndex < count($donsArgent) - 1) {
                    $donIndex++;
                    $montantRestantDon += (float)($donsArgent[$donIndex]['montant_restant'] ?? 0);
                    $idDonCourant = (int)($donsArgent[$donIndex]['id'] ?? 0);
                }
                
                if ($montantRestantDon < $total) {
                    // Pas assez de budget, on s'arrête
                    break;
                }
                
                // Créer l'achat
                $achatData = [
                    'idDon' => $idDonCourant,
                    'montant' => $cout,
                    'frais' => $frais,
                    'dateAchat' => date('Y-m-d H:i:s'),
                ];
                
                $idAchat = $this->achatModel->create($achatData);
                
                if ($idAchat) {
                    // Créer le détail de l'achat
                    $this->achatDetailsModel->create([
                        'idAchat' => $idAchat,
                        'idProduit' => $besoin['idProduit'],
                        'quantite' => $quantite,
                        'prixUnitaire' => $prixUnitaire
                    ]);
                    
                    // Mettre à jour le statut du besoin (satisfait = 2)
                    $this->besoinModel->update((int)$idBesoin, ['idStatusBesoin' => 2]);
                    
                    // Déduire du montant restant
                    $montantRestantDon -= $total;
                    $createdCount++;
                }
            }
            
            $this->db->commit();
            $_SESSION['success_message'] = "$createdCount achat(s) effectué(s) avec succès !";
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['error_message'] = 'Erreur: ' . $e->getMessage();
        }
        
        $this->app->redirect($this->getBaseUrl() . '/achats');
    }

    /**
     * Annuler des propositions en cours (simple suppression côté session)
     */
    public function annulerAchats(): void {
        // Selon implémentation, on pourrait stocker des propositions en session
        unset($_SESSION['propositions_achats']);
        $_SESSION['success_message'] = 'Propositions d\'achats annulées.';
        $this->app->redirect($this->getBaseUrl() . '/achats');
    }

    /**
     * Acheter automatiquement les besoins sélectionnés (JSON)
     * Route: POST /achats/auto
     */
    public function acheterAuto(): void {
        try {
            $besoinIds = $_POST['besoin_ids'] ?? null;
            
            if (is_string($besoinIds)) {
                $besoinIds = json_decode($besoinIds, true);
            }
            
            if (empty($besoinIds) || !is_array($besoinIds)) {
                throw new \Exception('Aucun besoin sélectionné');
            }

            // Utiliser le service d'achat automatique
            $resultat = $this->achatService->acheterBesoins($besoinIds);

            // Retourner JSON si requête AJAX
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($resultat);
                exit;
            }

            // Sinon redirect avec message flash
            if ($resultat['success']) {
                $_SESSION['success_message'] = $resultat['message'];
            } else {
                $_SESSION['error_message'] = $resultat['message'];
            }
            $this->app->redirect($this->getBaseUrl() . '/achats');

        } catch (\Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }

            $_SESSION['error_message'] = $e->getMessage();
            $this->app->redirect($this->getBaseUrl() . '/achats');
        }
    }

    /**
     * Vérifier si un besoin peut être acheté (disponibilité fonds)
     * Route: GET /api/achats/verifier-besoin/{id}
     */
    public function verifierBesoin(int $id): void {
        try {
            $besoin = $this->besoinModel->getById($id);
            
            if (!$besoin) {
                throw new \Exception("Besoin #$id introuvable");
            }

            // Calculer le coût avec frais
            $coutDetails = $this->achatService->calculerCoutAvecFrais($besoin);
            
            // Vérifier la disponibilité
            $disponibilite = $this->achatService->verifierDisponibilite($coutDetails['cout_total']);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'besoin' => [
                    'id' => $besoin['id'],
                    'idProduit' => $besoin['idProduit'],
                    'quantite' => $besoin['quantite'],
                    'idVille' => $besoin['idVille'],
                ],
                'cout' => $coutDetails,
                'disponibilite' => $disponibilite,
                'achat_possible' => $disponibilite['disponible'],
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Filtrer les achats par ville (JSON)
     * Route: GET /api/achats/par-ville/{idVille}
     */
    public function getAchatsParVille(int $idVille): void {
        try {
            $achats = $this->achatModel->getAchatsByVille($idVille);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'achats' => $achats,
                'count' => count($achats)
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
}
