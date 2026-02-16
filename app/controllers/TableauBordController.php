<?php

namespace App\Controllers;

use app\models\Ville;
use flight\Engine;

class TableauBordController {
    
    private $app;
    private $villeModel;
	private $besoinModel;
	private $donModel;

    public function __construct($db, Engine $app) {
        $this->app = $app;
        $this->villeModel = new Ville($db);
		$this->besoinModel = new Besoin($db);
		$this->donModel = new Don($db);
		$this->DistributionModel = new Don($db);

    }

    public function getAllAboutVille() {
        // Récupérer toutes les villes
        $allVille = $this->villeModel->getAll();

		// recuperer tous besoin 
		$allVille = $this->villeModel->getAll();

		// recuperer tous dons
		$allVille = $this->villeModel->getAll();

		//dispatcher distributions 
		$allVille = $this->villeModel->getAll();
		
        
        // Grouper par objet
        $objetsAvecHistorique = [];
        foreach ($allVille as $ville) {
            $idObject = ville['idObject'];
            
            // Si l'objet n'est pas encore dans le tableau, l'initialiser
            if (!isset($objetsAvecHistorique[$idObject])) {
                $objet = $this->villeModel->getById($idObject);
                $mainPicture = $this->pictureModel->getMainPicture($idObject);
                
                $objetsAvecHistorique[$idObject] = [
                    'objet' => $objet,
                    'image' => $mainPicture ? $mainPicture['nom'] : null,
                    'historique' => []
                ];
            }
            
            // Ajouter l'historique à l'objet
            $objetsAvecHistorique[$idObject]['historique'][] = ville;
        }
        
        // Convertir en tableau indexé
        $objetsAvecHistorique = array_values($objetsAvecHistorique);
        
        // Rendre la vue
        $this->app->render('HistoriqueProprio', [
            'objetsAvecHistorique' => $objetsAvecHistorique
        ]);
    }
    
}
