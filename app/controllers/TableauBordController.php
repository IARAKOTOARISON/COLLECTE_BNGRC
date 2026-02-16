<?php
namespace App\Controllers;

use app\models\Ville;
use app\models\Besoin;
use app\models\Don;
use app\models\Distribution;
use flight\Engine;

class TableauBordController {
    private Engine $app;
    private Ville $villeModel;
    private Besoin $besoinModel;
    private Don $donModel;
    private Distribution $distributionModel;

    public function __construct(\PDO $db, Engine $app) {
        $this->app = $app;
        $this->villeModel = new Ville($db);
        $this->besoinModel = new Besoin($db);
        $this->donModel = new Don($db);
        $this->distributionModel = new Distribution($db);
    }

    /** Préparer les données pour le tableau de bord */
    public function getAllAboutVille(): void {
        $allVille = $this->villeModel->getAll();
        $allBesoin = $this->besoinModel->getAll();
        $allDon = $this->donModel->getAll();

        // Simuler la distribution et calculer progression
        $allBesoin = $this->distributionModel->distribuer($allVille, $allDon, $allBesoin);

        // Rendu vers la vue
        $this->app->render('tableauBord', [
            'aboutVille' => $allBesoin
        ]);
    }
}
