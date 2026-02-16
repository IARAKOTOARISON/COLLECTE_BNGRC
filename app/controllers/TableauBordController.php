<?php

namespace App\Controllers;



use app\models\Ville;
use app\models\Don;
use app\models\Besoin;
use app\models\Distribution;
use flight\Engine;


class TableauBordController {
    
    private $app;
    private $villeModel;
	private $besoinModel;
	private $donModel;
	private $distributionModel;

    public function __construct($db, Engine $app) {
        $this->app = $app;
        $this->villeModel = new Ville($db);
		$this->besoinModel = new Besoin($db);
		$this->donModel = new Don($db);
		$this->distributionModel = new Distribution($db);

    }

    public function getAllAboutVille() {
        // Récupérer toutes les villes
        $allVille = $this->villeModel->getAllVille();

		// // recuperer tous besoin 
		// $allBesoin = $this->besoinModel->getAll();

		// // recuperer tous dons
		// $allDon = $this->donModel->getAll();

		// //dispatcher distributions 
		// $allDistribution = $this->distributionModel->distribuer($allVille,$allDon,$allBesoin);

		//test base connected
		 $this->app->render('listeVille', [
             'listeVille' => $allVille
         ]);

        // Rendre la vue
        // $this->app->render('TableauBord', [
        //     'aboutVille' => $allDistribution
        // ]);
    }
    
}
