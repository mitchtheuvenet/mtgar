<?php

use app\core\Application;

$successFlash = Application::$app->session->getFlash('success');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Metadata -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="application-name" content="MTG Akashic Records">
        <meta name="author" content="mitchtheuvenet">
        <meta name="description" content="A concise card collection management web application for Magic: the Gathering">
        <meta name="keywords" content="mtg, magic, the, gathering, collection, management, tcg, ccg, collectible, trading, card, game">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="images/favicon.png">

        <!-- Page title -->
        <title><?= $this->title; ?> &centerdot; MTGAR</title>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a href="/" class="navbar-brand">
                    <img src="/images/logo_small.png" alt="Logo" style="max-height:3rem;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="/" class="nav-link">Home</a>
                        </li>
                    </ul>
                    <?php if (Application::isGuest()): ?>
                        <a href="/login" class="btn btn-outline-primary" role="button">Log in</a>
                    <?php else: ?>
                        <div class="navbar-text me-2">
                            Logged in as <b><?php echo Application::$app->user->username; ?></b>
                        </div>
                        <a href="/profile" class="btn btn-outline-info me-2" role="button">Profile</a>
                        <form action="/logout" method="post">
                            <button class="btn btn-outline-danger" type="submit">Log out</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="container">
            <?php if (!empty($successFlash)): ?>
                <div class="alert alert-success" role="alert">
                    <?= $successFlash; ?>
                </div>
            <?php endif; ?>
            {{content}}
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>
