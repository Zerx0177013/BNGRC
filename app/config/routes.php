<?php

use app\controllers\CategoryController;
use app\controllers\RegisterController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use app\controllers\ItemController;
use app\controllers\LoginController;
use app\controllers\StatController;
use app\controllers\AuthController;
use app\controllers\DemandeController;
use app\controllers\ExchangeController;
use app\controllers\HistoriqueController;

$router->group('', function (Router $router) use ($app) {
	$router->get('/',function () use ($app){
		$app->render('index') ;
	} ) ;
}, [SecurityHeadersMiddleware::class]);
