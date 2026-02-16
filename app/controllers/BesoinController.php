<?php
namespace App\Controllers;

use App\models\Ville;
use App\models\Produit;
use App\models\StatusBesoin;
use App\models\Besoin;
use flight\Engine;

class BesoinController {
    private Engine $app;
    private \PDO $db;
    private Ville $villeModel;
    private Produit $produitModel;
    private StatusBesoin $statusBesoinModel;
    private Besoin $besoinModel;

    public function __construct(\PDO $db, Engine $app) {
        $this->app = $app;
        $this->db = $db;
        $this->villeModel = new Ville($db);
        $this->produitModel = new Produit($db);
        $this->statusBesoinModel = new StatusBesoin($db);
        $this->besoinModel = new Besoin($db);
    }

    /**
     * Afficher le formulaire de saisie des besoins
     */
    public function afficherFormulaire(): void {
        // Charger les données nécessaires pour le formulaire
        $villes = $this->villeModel->getAllVilles();
        $produits = $this->produitModel->getAllProduits();
        $statusList = $this->statusBesoinModel->getAllStatusBesoin();

        // Récupérer les messages flash si présents
        $success = $_SESSION['success_message'] ?? null;
        $error = $_SESSION['error_message'] ?? null;
        
        // Nettoyer les messages flash
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        // Rendre la vue
        $this->app->render('besoinFormulaire', [
            'villes' => $villes,
            'produits' => $produits,
            'statusList' => $statusList,
            'success' => $success,
            'error' => $error
        ]);
    }

    /**
     * Traiter l'ajout d'un nouveau besoin
     */
    public function ajouterBesoin(): void {
        try {
            // Récupérer les données du formulaire
            $idVille = $_POST['ville'] ?? null;
            $idProduit = $_POST['produit'] ?? null;
            $quantite = $_POST['quantite'] ?? null;
            $dateBesoin = $_POST['date'] ?? date('Y-m-d');
            
            // Validation basique
            if (empty($idVille) || empty($idProduit) || empty($quantite)) {
                throw new \Exception("Tous les champs obligatoires doivent être remplis.");
            }

            if ($quantite <= 0) {
                throw new \Exception("La quantité doit être supérieure à zéro.");
            }

            // Récupérer le status par défaut (par exemple "En attente" ou le premier status)
            $statusList = $this->statusBesoinModel->getAllStatusBesoin();
            $idStatus = !empty($statusList) ? $statusList[0]['id'] : 1;

            // Préparer les données pour l'insertion
            $data = [
                'idVille' => $idVille,
                'idProduit' => $idProduit,
                'quantite' => $quantite,
                'idStatus' => $idStatus,
                'dateBesoin' => $dateBesoin . ' ' . date('H:i:s')
            ];

            // Insérer dans la base de données
            $success = $this->besoinModel->create($data);

            if ($success) {
                $_SESSION['success_message'] = "Le besoin a été enregistré avec succès !";
            } else {
                throw new \Exception("Erreur lors de l'enregistrement du besoin.");
            }

        } catch (\Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        // Rediriger vers le formulaire
        $this->app->redirect('/besoins/formulaire');
    }

    /**
     * Afficher la liste des besoins
     */
    public function afficherListe(): void {
        $besoins = $this->besoinModel->getAllBesoin();
        
        $this->app->render('besoinListe', [
            'besoins' => $besoins
        ]);
    }
}
