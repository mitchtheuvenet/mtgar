<?php

use app\core\Application;

use app\controllers\SiteController;
use app\controllers\AuthController;

require_once  __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS']
    ],
    'smtp' => [
        'host' => $_ENV['SMTP_HOST'],
        'user' => $_ENV['SMTP_USER'],
        'pass' => $_ENV['SMTP_PASS']
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/profile', [SiteController::class, 'profile']);

$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->post('/logout', [AuthController::class, 'logout']);

$app->run();