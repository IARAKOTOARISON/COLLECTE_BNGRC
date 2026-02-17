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
        $villes = $villeModel->getAll();

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
     * Retourne une liste de propositions JSON sans exécution.
     */
    public function proposerAchatsAuto(): void {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
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
     * Expects POST['propositions'] = JSON array of objects { idBesoin, idDon }
     */
    public function validerAchatsAuto(): void {
        try {
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
