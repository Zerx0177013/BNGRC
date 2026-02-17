<?php

use app\controllers\ArticleController;
use app\controllers\BesoinController;
use app\controllers\DashboardController;
use app\controllers\DonController;
use app\controllers\RegionController;
use app\controllers\VilleController;
use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\DispatchController ;
use app\controllers\AchatController;
use app\controllers\RecapController;
use flight\Engine;
use flight\net\Router;

$router->group('', function (Router $router) use ($app) {

	// ========== Dashboard ==========
	$dashboardController = new DashboardController($app);
	$router->get('/', [$dashboardController, 'renderDashboard']);

	$router->group('/dispatch', function (Router $router) use ($app) {
		$besoinController = new BesoinController($app);
		$router->get('/besoinsParVille', [$besoinController, 'renderBesoinsParVille']);

	});
	// ========== Régions ==========
	$router->group('/regions', function (Router $router) use ($app) {
		$regionController = new RegionController($app);

		$router->get('/', [$regionController, 'renderRegionList']);
		$router->get('/add', [$regionController, 'renderAddForm']);
		$router->get('/@id', [$regionController, 'renderRegionDetail']);
		$router->get('/@id/edit', [$regionController, 'renderEditForm']);
		$router->post('/', [$regionController, 'createRegion']);
		$router->put('/@id', [$regionController, 'updateRegion']);
		$router->delete('/@id', [$regionController, 'deleteRegion']);
	});

	// ========== Villes ==========
	$router->group('/villes', function (Router $router) use ($app) {
		$villeController = new VilleController($app);

		$router->get('/', [$villeController, 'renderVilleList']);
		$router->get('/add', [$villeController, 'renderAddForm']);
		$router->get('/@id', [$villeController, 'renderVilleDetail']);
		$router->get('/@id/edit', [$villeController, 'renderEditForm']);
		$router->post('/', [$villeController, 'createVille']);
		$router->put('/@id', [$villeController, 'updateVille']);
		$router->delete('/@id', [$villeController, 'deleteVille']);
	});

	// ========== Articles ==========
	$router->group('/articles', function (Router $router) use ($app) {
		$articleController = new ArticleController($app);

		$router->get('/', [$articleController, 'renderArticleList']);
		$router->get('/add', [$articleController, 'renderAddForm']);
		$router->get('/@id', [$articleController, 'renderArticleDetail']);
		$router->get('/@id/edit', [$articleController, 'renderEditForm']);
		$router->post('/', [$articleController, 'createArticle']);
		$router->put('/@id', [$articleController, 'updateArticle']);
		$router->delete('/@id', [$articleController, 'deleteArticle']);
	});

	// ========== Besoins ==========
	$router->group('/besoins', function (Router $router) use ($app) {
		$besoinController = new BesoinController($app);

		$router->get('/', [$besoinController, 'renderBesoinList']);
		$router->get('/add', [$besoinController, 'renderAddForm']);
		$router->get('/@id', [$besoinController, 'renderBesoinDetail']);
		$router->get('/@id/edit', [$besoinController, 'renderEditForm']);
		$router->post('/', [$besoinController, 'createBesoin']);
		$router->put('/@id', [$besoinController, 'updateBesoin']);
		$router->delete('/@id', [$besoinController, 'deleteBesoin']);
	});

	// ========== Dons ==========
	$router->group('/dons', function (Router $router) use ($app) {
		$donController = new DonController($app);

		$router->get('/', [$donController, 'renderDonList']);
		$router->get('/add', [$donController, 'renderAddForm']);
		$router->get('/@id', [$donController, 'renderDonDetail']);
		$router->get('/@id/edit', [$donController, 'renderEditForm']);
		$router->post('/', [$donController, 'createDon']);
		$router->put('/@id', [$donController, 'updateDon']);
		$router->delete('/@id', [$donController, 'deleteDon']);
	});

	// ========== Dispatch ==========
	$router->group('/dispatch', function (Router $router) use ($app) {
		$dispatchController = new DispatchController($app);

		$router->get('/', [$dispatchController, 'renderDispatchPage']);
		$router->get('/history', [$dispatchController, 'renderDispatchHistory']);
		$router->get('/simulation', [$dispatchController, 'renderSimulationPage']);
		$router->post('/simulate-data', [$dispatchController, 'getSimulationData']);
		$router->post('/simulate-data-valeur', [$dispatchController, 'getSimulationDataWithValeur']);
		$router->post('/execute', [$dispatchController, 'executeDispatch']);
		$router->post('/execute-chronologically-valeur', [$dispatchController, 'executeDispatchAllChronologicallyWithValeur']);
		$router->delete('/clear', [$dispatchController, 'clearDispatches']);
	});

	// ========== Achats ==========
	$router->group('/achats', function (Router $router) use ($app) {
		$achatController = new AchatController($app);

		$router->get('/', [$achatController, 'renderBesoinsRestants']);
		$router->get('/liste', [$achatController, 'renderAchatList']);
		$router->get('/json', [$achatController, 'getAchatsJson']);
		$router->post('/', [$achatController, 'createAchat']);
		$router->delete('/@id', [$achatController, 'deleteAchat']);
	});

	// ========== Récapitulation ==========
	$router->group('/recap', function (Router $router) use ($app) {
		$recapController = new RecapController($app);

		$router->get('/', [$recapController, 'renderRecapPage']);
		$router->get('/data', [$recapController, 'getRecapDataJson']);
	});

}, [SecurityHeadersMiddleware::class]);
