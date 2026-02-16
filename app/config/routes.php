<?php

use app\controllers\ApiExampleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function (Router $router) use ($app) {

	// Page d'accueil
	$router->get('/', function () use ($app) {
		$app->render('accueil');
	});

	// Page formulaire de saisie des besoins
	$router->get('/besoins/formulaire', function () use ($app) {
		$app->render('besoinFormulaire');
	});

	// Page liste des besoins
	$router->get('/besoins/liste', function () use ($app) {
		$app->render('besoinListe');
	});

}, [SecurityHeadersMiddleware::class]);