<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

$router->group('', function (Router $router) use ($app) {
}, [SecurityHeadersMiddleware::class]);
