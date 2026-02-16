<?php

use app\controllers\ApiExampleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

	$router->get('/', function() use ($app) {
		$app->render('welcome', [ 'message' => 'You are gonna do great things!' ]);
	});

	///////////////////////////////////////////////////////////////////////tableau de bord

	$router->get('/tableauBord', function($name) {
		$db = $app->db();
		$controller = new TableauBordController($db, $app);
		$controller->getAllAboutVille();
	});

	
}, [ SecurityHeadersMiddleware::class ]);
