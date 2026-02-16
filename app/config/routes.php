<?php

use app\controllers\TableauBordController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\VilleController;
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
		$controller = new BesoinController($db, $app);
		$controller->afficherFormulaire();
	});

	$router->post('/besoins/ajouter', function() use ($app) {
		$db = $app->db();
		$controller = new BesoinController($db, $app);
		$controller->ajouterBesoin();
	});

	$router->get('/besoins/liste', function() use ($app) {
		$db = $app->db();
		$controller = new BesoinController($db, $app);
		$controller->afficherListe();
	});

	$router->get('/dons/formulaire', function() use ($app) {
		$db = $app->db();
		$controller = new DonController($db, $app);
		$controller->afficherFormulaire();
	});

	$router->post('/dons/ajouter', function() use ($app) {
		$db = $app->db();
		$controller = new DonController($db, $app);
		$controller->ajouterDon();
	});

	$router->get('/dons/liste', function() use ($app) {
		$db = $app->db();
		$controller = new DonController($db, $app);
		$controller->afficherListe();
	});

	$router->get('/villes/liste', function() use ($app) {
		$db = $app->db();
		$controller = new VilleController($db, $app);
		$controller->afficherListe();
	});

	// Route additionnelle pour compatibilité (redirige vers /villes/liste)
	$router->get('/listeVille', function() use ($app) {
		$app->redirect('/villes/liste');
	});

	


	
}, [ SecurityHeadersMiddleware::class ]);
