<?php

use app\controllers\TableauBordController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\VilleController;
use app\controllers\SimulationController;
use app\controllers\AchatController;
use app\controllers\RecapController;
use app\controllers\ReinitializeController;

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
		$baseUrl = $app->get('baseUrl') ?? '';
		$app->render('accueil', ['baseUrl' => $baseUrl]);
	});



	///////////////////////////////////////////////////////////////////////tableau de bord

	$router->get('/tableauBord', function() use ($app) {
		$db = $app->db();
		$controller = new TableauBordController($db, $app);
		$controller->getAllAboutVille();
	});

	$router->get('/simulation', function () use ($app) {
		$db = $app->db();
		$controller = new SimulationController($db, $app);
		$controller->afficherSimulation();
	});

	$router->post('/simulation/confirmer', function () use ($app) {
		$db = $app->db();
		$controller = new SimulationController($db, $app);
		$controller->confirmerDispatch();
	});

	// Nouvelles routes simulation
	$router->get('/api/simulation/lancer', function () use ($app) {
		$db = $app->db();
		$controller = new SimulationController($db, $app);
		$controller->lancerSimulation();
	});

	$router->post('/simulation/lancer', function () use ($app) {
		$db = $app->db();
		$controller = new SimulationController($db, $app);
		$controller->lancerSimulation();
	});

	$router->post('/simulation/valider', function () use ($app) {
		$db = $app->db();
		$controller = new SimulationController($db, $app);
		$controller->validerSimulation();
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

	// ACHATS - pages et APIs
	$router->get('/besoins-restants', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->getBesoinsRestantsPage();
	});

	$router->get('/achats', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->afficherPageAchats();
	});

	$router->get('/achats/auto/proposer', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->proposerAchatsAuto();
	});

	$router->post('/achats/auto/valider', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->validerAchatsAuto();
	});

	// API endpoints pour achats / besoins
	$router->get('/api/achats/filter', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->getListeAchats();
	});

	// API JSON pour les propositions d'achat (utilisé par AJAX)
	$router->get('/api/achats/propositions', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->getPropositionsAchatsJson();
	});

	$router->get('/api/achats/verifier', function() use ($app) {
		$db = $app->db();
		$service = new \app\models\AchatAutoService($db);
		$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
		$res = $service->getBesoinsPrioritaires($limit);
		header('Content-Type: application/json');
		echo json_encode($res);
		exit;
	});

	$router->get('/api/besoins-restants', function() use ($app) {
		$db = $app->db();
		$besoin = new \app\models\Besoin($db);
		$res = $besoin->getBesoinsRestants();
		header('Content-Type: application/json');
		echo json_encode($res);
		exit;
	});

	$router->get('/api/calcul-cout', function() use ($app) {
		$db = $app->db();
		$service = new \app\models\AchatAutoService($db);
		$id = isset($_GET['idBesoin']) ? (int)$_GET['idBesoin'] : null;
		if ($id) {
			$besoin = (new \app\models\Besoin($db))->getById($id);
			$cout = $service->calculerCoutTotal($besoin);
			header('Content-Type: application/json');
			echo json_encode(['cout' => $cout]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(['error' => 'idBesoin manquant']);
		}
		exit;
	});

	// Route additionnelle pour compatibilité (redirige vers /villes/liste)
	$router->get('/listeVille', function() use ($app) {
		$baseUrl = $app->get('baseUrl') ?? '';
		$app->redirect($baseUrl . '/villes/liste');
	});

	// RECAP CONTROLLER - Statistiques
	$router->get('/recap', function() use ($app) {
		$db = $app->db();
		$controller = new RecapController($db, $app);
		$controller->afficherRecap();
	});

	$router->get('/api/stats', function() use ($app) {
		$db = $app->db();
		$controller = new RecapController($db, $app);
		$controller->getStats();
	});

	$router->get('/recap/stats', function() use ($app) {
		$db = $app->db();
		$controller = new RecapController($db, $app);
		$controller->getStats();
	});

	$router->get('/api/stats/besoins', function() use ($app) {
		$db = $app->db();
		$controller = new RecapController($db, $app);
		$controller->getStatsBesoins();
	});

	$router->get('/api/stats/dons', function() use ($app) {
		$db = $app->db();
		$controller = new RecapController($db, $app);
		$controller->getStatsDons();
	});

	$router->get('/api/stats/villes', function() use ($app) {
		$db = $app->db();
		$controller = new RecapController($db, $app);
		$controller->getStatsParVille();
	});

	// ACHAT - Nouvelles routes
	$router->post('/achats/auto', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->acheterAuto();
	});

	$router->get('/api/achats/verifier-besoin/@id', function(int $id) use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->verifierBesoin($id);
	});

	$router->get('/api/achats/par-ville/@idVille', function(int $idVille) use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->getAchatsParVille($idVille);
	});

	// API stats global (retour JSON)
	$router->get('/api/stats/global', function() use ($app) {
		$db = $app->db();
		$controller = new RecapController($db, $app);
		$controller->getStats();
	});

	// API achat auto (retour JSON)
	$router->get('/api/achats/auto', function() use ($app) {
		$db = $app->db();
		$controller = new AchatController($db, $app);
		$controller->proposerAchatsAuto();
	});

	// API simulation (retour JSON)
	$router->get('/api/simulation', function() use ($app) {
		$db = $app->db();
		$controller = new SimulationController($db, $app);
		$controller->lancerSimulation();
	});

	// API validation simulation (retour JSON)
	$router->post('/api/simulation/valider', function() use ($app) {
		$db = $app->db();
		$controller = new SimulationController($db, $app);
		$controller->validerSimulation();
	});





	//////////////////////////////////////////////////////////////////////////////////////////////////reinitialize
	$router->get('/reinitialize', function() use ($app) {
		$db = $app->db();
		$controller = new ReinitializeController($db, $app);
		$controller->reanitialize();
	});

	
	
}, [ SecurityHeadersMiddleware::class ]);
