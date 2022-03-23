<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/a/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    $app->get('/login', function (Request $request, Response $response) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/login' route");

        // Render index view
        return $container->get('renderer')->render($response, 'adminLTE_login.phtml');
    });

    $app->get('/', 'Src\Controller\IndexController:index')
    ->setName('index');

	$app->group('/api/v1', function () {
	    $this->get('/sys/config[/{id}]', 'Src\Controller\SystemController:getConfig')
	         ->setName('api_get_config');
	         
	    $this->get('/sys/version', 'Src\Controller\SystemController:getVersion')
	         ->setName('api_get_app_version');
	});
};
