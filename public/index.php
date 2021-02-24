<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use app\core\Application;

$app = new Application(dirname(__DIR__));

$app->router->get('/', 'home');

$app->router->get('/test', 'test');
$app->router->post('/test', function() {
    return 'Handling post data...';
});

$app->run();
