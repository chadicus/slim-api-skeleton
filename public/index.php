<?php

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/settings.php';

// Instantiate the app
$app = new \Slim\App($settings);

$container = $app->getContainer();

$files = glob(realpath(__DIR__ . '/../autoload') . '*.php');
foreach ($files as $file) {
    $config = require_once $file;
    $routes = $config['routes'] ?? [];
    foreach ($routes as $route) {
        $app->map($route['methods'], $route['pattern'], $route['callable']);
    }

    $services = $config['services'] ?? [];
    foreach ($services as $key => $value) {
       $container[$key] = $value;
    }
}

// Run app
$app->run();
