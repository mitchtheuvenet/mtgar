<?php

use app\core\Application;

use app\controllers\AuthController;
use app\controllers\CardController;
use app\controllers\DeckController;
use app\controllers\SiteController;

require_once  __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Amsterdam');

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
    ],
    'mollie' => [
        'api_key' => $_ENV['MOLLIE_API_KEY']
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/profile', [SiteController::class, 'profile']);
$app->router->get('/profile/change/email', [AuthController::class, 'changeEmail']);
$app->router->post('/profile/change/email', [AuthController::class, 'changeEmail']);
$app->router->get('/profile/change/email/verify', [AuthController::class, 'verifyNewEmail']);
$app->router->post('/profile/change/email/verify', [AuthController::class, 'verifyNewEmail']);
$app->router->get('/profile/change/password', [AuthController::class, 'changePassword']);
$app->router->post('/profile/change/password', [AuthController::class, 'changePassword']);
$app->router->get('/profile/delete', [AuthController::class, 'deleteAccount']);
$app->router->post('/profile/delete', [AuthController::class, 'deleteAccount']);
$app->router->get('/profile/delete/confirm', [AuthController::class, 'confirmDeleteAccount']);
$app->router->post('/profile/delete/confirm', [AuthController::class, 'confirmDeleteAccount']);

$app->router->get('/decks', [DeckController::class, 'decks']);
$app->router->get('/decks/create', [DeckController::class, 'createDeck']);
$app->router->post('/decks/create', [DeckController::class, 'createDeck']);
$app->router->get('/decks/edit', [DeckController::class, 'editDeck']);
$app->router->post('/decks/edit', [DeckController::class, 'editDeck']);
$app->router->post('/decks/delete', [DeckController::class, 'deleteDeck']);
$app->router->get('/decks/view', [DeckController::class, 'viewDeck']);
$app->router->get('/decks/pdf', [DeckController::class, 'generatePdf']);
$app->router->get('/decks/export', [DeckController::class, 'exportDecks']);
$app->router->get('/decks/import', [DeckController::class, 'importDecks']);
$app->router->post('/decks/import', [DeckController::class, 'importDecks']);
$app->router->get('/decks/api', [DeckController::class, 'apiJSON']);

$app->router->get('/cards/search', [CardController::class, 'searchCards']);
$app->router->post('/cards/add', [CardController::class, 'addCardToDeck']);
$app->router->post('/cards/remove', [CardController::class, 'removeCardFromDeck']);

$app->router->get('/watermark', [SiteController::class, 'watermark']);
$app->router->post('/watermark', [SiteController::class, 'watermark']);

$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

$app->router->get('/donate', [SiteController::class, 'donate']);
$app->router->post('/donate', [SiteController::class, 'donate']);
$app->router->post('/mollie', [SiteController::class, 'mollie']);
$app->router->get('/thanks', [SiteController::class, 'thankYou']);

$app->router->get('/users', [SiteController::class, 'users']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/login/forgot', [AuthController::class, 'forgotPassword']);
$app->router->post('/login/forgot', [AuthController::class, 'forgotPassword']);
$app->router->get('/login/reset', [AuthController::class, 'resetPassword']);
$app->router->post('/login/reset', [AuthController::class, 'resetPassword']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/register/verify', [AuthController::class, 'verifyRegistration']);
$app->router->post('/register/verify', [AuthController::class, 'verifyRegistration']);

$app->router->post('/logout', [AuthController::class, 'logout']);

$app->run();