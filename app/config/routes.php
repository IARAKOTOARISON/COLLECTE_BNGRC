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

	$router->get('/', function() use ($app) {
		$app->render('accueil');
	});

	///////////////////////////////////////////////////////////////////////tableau de bord

	$router->get('/tableauBord', function() use ($app) {
		$db = $app->db();
		$controller = new TableauBordController($db, $app);
		$controller->getAllAboutVille();
	});
	
}, [ SecurityHeadersMiddleware::class ]);
