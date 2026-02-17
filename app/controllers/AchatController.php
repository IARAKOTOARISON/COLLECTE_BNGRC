<?php
namespace app\controllers;

use app\models\Achat;
use app\models\AchatDetails;
use app\models\Besoin;
use app\models\Don;
use app\models\AchatAutoService;
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

        $success = $_SESSION['success_message'] ?? null;
        $error = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        // render the `achatListe` view we created earlier
        $this->app->render('achatListe', [
            'achats' => $achats,
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
}
