<?php
namespace app\controllers;

use App\models\Achat;
use App\models\Besoin;
use App\models\Don;
use App\models\Produit;
use App\models\Distribution;
use App\models\Setting;
use flight\Engine;

class AchatController {
    private Engine $app;
    private \PDO $db;

    public function __construct(\PDO $db, Engine $app) {
        $this->app = $app;
        $this->db = $db;
    }

    /** Afficher la liste des achats (filtrable par ville via GET param `ville`) */
    public function afficherListe(): void {
        $achatModel = new Achat($this->db);
        $villeId = isset($_GET['ville']) && is_numeric($_GET['ville']) ? (int)$_GET['ville'] : null;
        $achats = $achatModel->getAllAvecDetails($villeId);

        // Récupérer la liste des villes pour le filtre
        $villesStmt = $this->db->prepare('SELECT id, nom FROM ville ORDER BY nom');
        $villesStmt->execute();
        $villes = $villesStmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->app->render('achatListe', [
            'achats' => $achats,
            'villeFilter' => $villeId,
            'villes' => $villes
        ]);
    }

    /** Effectuer un achat en utilisant un don en argent disponible */
    public function acheter(): void {
        try {
            $idBesoin = $_POST['idBesoin'] ?? null;
            $idDon = $_POST['idDon'] ?? null;
            $quantite = $_POST['quantite'] ?? null; // quantité en unités produit

            if (empty($idBesoin) || empty($idDon) || empty($quantite)) {
                throw new \Exception('Tous les champs requis doivent être fournis.');
            }

            $besoinModel = new Besoin($this->db);
            $donModel = new Don($this->db);
            $produitModel = new Produit($this->db);
            $achatModel = new Achat($this->db);
            $distributionModel = new Distribution($this->db);

            $besoin = $besoinModel->getById($idBesoin);
            if (!$besoin) throw new \Exception('Besoin introuvable.');

            $produit = $produitModel->getById($besoin['idProduit']);
            if (!$produit) throw new \Exception('Produit du besoin introuvable.');

            $don = $donModel->getById($idDon);
            if (!$don) throw new \Exception('Don introuvable.');

            // Vérifier que c'est bien un don en argent
            if (!empty($don['idProduit'])) {
                throw new \Exception('Le don sélectionné n\'est pas un don financier.');
            }

            // Calculer montant nécessaire
            $prixUnitaire = floatval($produit['prixUnitaire'] ?? 0);
            $quantite = floatval($quantite);
            if ($quantite <= 0) throw new \Exception('Quantité invalide.');

            $montantSansFrais = $quantite * $prixUnitaire;

            // Charger la config d'achats depuis la DB (fallback au fichier config)
            $settingModel = new Setting($this->db);
            $config = require(__DIR__ . '/../config/config.php');
            $defaultFrais = $config['achats']['frais_percent'] ?? 0;
            $fraisPercentValue = $settingModel->get('achats.frais_percent', $defaultFrais);
            $fraisPercent = is_numeric($fraisPercentValue) ? floatval($fraisPercentValue) : floatval($defaultFrais);
            $frais = round($montantSansFrais * ($fraisPercent / 100), 2);
            $montantTotal = round($montantSansFrais + $frais, 2);

            // Calculer montant restant du don (sum des quantites déjà utilisées)
            $stmt = $this->db->prepare("SELECT COALESCE(SUM(quantite),0) AS deja_utilise FROM distribution WHERE idDon = :idDon");
            $stmt->execute([':idDon' => $idDon]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $dejaUtilise = floatval($row['deja_utilise'] ?? 0);
            $donMontant = floatval($don['montant'] ?? 0);
            $restant = $donMontant - $dejaUtilise;

            if ($restant < $montantTotal) {
                throw new \Exception('Le don sélectionné n\'a pas suffisamment de fonds restants pour couvrir l\'achat (y compris les frais).');
            }

            // Début de transaction
            $this->db->beginTransaction();

            // Enregistrer l'achat
            $achatData = [
                'idBesoin' => $idBesoin,
                'idDon' => $idDon,
                'idVille' => $besoin['idVille'],
                'idProduit' => $besoin['idProduit'],
                'quantiteAchetee' => $quantite,
                'montant_sans_frais' => $montantSansFrais,
                'frais' => $frais,
                'montant_total' => $montantTotal,
                'dateAchat' => date('Y-m-d H:i:s')
            ];

            $achatId = $achatModel->create($achatData);
            if (!$achatId) throw new \Exception('Erreur lors de l\'enregistrement de l\'achat.');

            // Créer une distribution qui consomme le don (on enregistre le montant consommé dans la colonne `quantite` existante pour rester cohérent avec le reste du code)
            $distributionData = [
                'idBesoin' => $idBesoin,
                'idDon' => $idDon,
                'quantite' => $montantTotal,
                'idStatus' => 2,
                'dateDistribution' => date('Y-m-d H:i:s')
            ];

            $distResult = $distributionModel->create($distributionData);
            if (!$distResult) throw new \Exception('Erreur lors de la création de la distribution pour l\'achat.');

            $this->db->commit();

            $_SESSION['success_message'] = 'Achat enregistré avec succès.';

        } catch (\Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            $_SESSION['error_message'] = $e->getMessage();
        }

        // Rediriger vers la page de simulation (besoins restants)
        $this->app->redirect('/simulation');
    }
}
