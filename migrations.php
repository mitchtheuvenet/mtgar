<?php

use app\core\Application;

require_once  __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
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

$app = new Application(__DIR__, $config);

$app->db->applyMigrations();