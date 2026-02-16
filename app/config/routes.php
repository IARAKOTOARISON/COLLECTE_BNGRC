<?php

use app\controllers\TableauBordController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

	// Accueil: garder '/' comme alias mais exposer '/accueil' pour correspondre au menu
	$router->get('/', function() use ($app) {
		$app->render('accueil');
	});



	///////////////////////////////////////////////////////////////////////tableau de bord

	$router->get('/tableauBord', function() use ($app) {
		$db = $app->db();
		$controller = new TableauBordController($db, $app);
		$controller->getAllAboutVille();
	});

	$router->get('/simulation', function () use ($app) {
		$app->render('simulation');
	});

	// Pages statiques / formulaires / listes — routes alignées avec le menu

	$router->get('/besoins/formulaire', function() use ($app) {
		$db = $app->db();
		// charger les villes et types de besoin depuis la base
		$villeModel = new \App\models\Ville($db);
		$typeModel = new \App\models\TypeBesoin($db);

		$villes = $villeModel->getAllVilles();
		$types = $typeModel->getAllTypesBesoin();

		$app->render('besoinFormulaire', [
			'villes' => $villes,
			'types' => $types,
		]);
	});

	$router->get('/besoins/liste', function() use ($app) {
		$app->render('besoinListe');
	});

	$router->get('/dons/formulaire', function() use ($app) {
		$app->render('donFormulaire');
	});

	$router->get('/dons/liste', function() use ($app) {
		$app->render('donListe');
	});

	// Route additionnelle pour lister les villes (peut rester)
	$router->get('/listeVille', function() use ($app) {
		$app->render('listeVille');
	});

	


	
}, [ SecurityHeadersMiddleware::class ]);
