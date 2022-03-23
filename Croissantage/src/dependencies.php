<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // PDO
    $container['pdo'] = function ($container) {
        $settings = $container->get('settings');
        $dsn = 'mysql:host='.$settings['db']['host'].';dbname=' . $settings['db']['database'];
        $pdo = new PDO($dsn, $settings['db']['login'], $settings['db']['mdp']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Disable emulate prepared statements
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // Set default fetch mode
        return $pdo;
    };
    // -----------------------------------------------------------------------------
    // Model factories
    // -----------------------------------------------------------------------------
    $container['cfgModel'] = function ($container) {
        $settings = $container->get('settings');
        $cfgModel = new Src\Model\ConfigurationModel($container->get('pdo'));
        return $cfgModel;
    };
    // -----------------------------------------------------------------------------
    // Controller factories
    // -----------------------------------------------------------------------------

    $container['Src\Controller\IndexController'] = function ($container) {
        $view = $container->get('renderer');
        $logger = $container->get('logger');
        return new Src\Controller\IndexController($view, $logger, $container);
    };

    $container['Src\Controller\SystemController'] = function ($container) {
        $logger = $container->get('logger');
        $cfgModel = $container->get('cfgModel');
        // $cfgModel = $container->get('cfgModelFPDO');
        // $cfgModel = $container->get('cfgModelMock');
        return new Src\Controller\SystemController($logger, $cfgModel);
    };
};