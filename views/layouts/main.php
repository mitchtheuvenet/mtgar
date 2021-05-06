<?php

use app\core\Application;

$flashMessages = $this->getFlashMessages();

?>

<!DOCTYPE html>
<html lang="en" class="h-100">
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
        
        <?php if (!empty($this->style)): ?>
            <!-- Page CSS -->
            <style>
                <?= $this->style; ?>
            </style>
        <?php endif; ?>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/images/favicon.png">

        <!-- Page title -->
        <title><?= $this->title; ?> &centerdot; MTGAR</title>
    </head>
    <body class="d-flex flex-column h-100">
        <header>
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
                <div class="container-fluid">
                    <a href="/" class="navbar-brand" tabindex="-1">
                        <img src="/images/logo_small.png" alt="Logo" style="max-height:3rem;">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="/" class="nav-link<?php echo $this->title === 'Home' ? ' active' : '' ?>">Home</a>
                            </li>
                            <?php if (!Application::isGuest()): ?>
                                <li class="nav-item">
                                    <a href="/profile" class="nav-link<?php echo $this->title === 'Profile' ? ' active' : '' ?>">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/decks" class="nav-link<?php echo $this->title === 'Decks' ? ' active' : '' ?>">Decks</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="/contact" class="nav-link<?php echo $this->title === 'Contact' ? ' active' : '' ?>">Contact</a>
                            </li>
                            <?php if (Application::isAdmin()): ?>
                                <li class="nav-item border-start ms-3 ps-3">
                                    <a href="/users" class="nav-link<?php echo $this->title === 'Users' ? ' active' : '' ?>">Users</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="d-flex">
                            <?php if (Application::isGuest()): ?>
                                <a href="/login" class="btn btn-primary" role="button"><i class="bi bi-box-arrow-in-right"></i> Log in</a>
                            <?php else: ?>
                                <span class="navbar-text mx-2">Logged in as <b><?= Application::$app->user->username; ?></b></span>
                                <form action="/logout" method="post">
                                    <button class="btn btn-outline-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Log out</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-shrink-0" style="margin-top:4.625rem;">
            <div class="container-fluid">
                <div class="row mt-5">
                    <?php
                        
                    foreach ($flashMessages as $key => $message) {
                        $key = $key === 'error' ? 'danger' : $key;
                        $message = $message['value'] ?? '';

                        echo "
                            <div class=\"alert alert-{$key} alert-dismissible fade show text-center\" style=\"border-radius:0;\" role=\"alert\">
                                {$message}
                                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\" tabindex=\"-1\"></button>
                            </div>
                        ";
                    }
                        
                    ?>
                    <!-- Content -->
                    {{content}}
                </div>
            </div>
        </main>

        <footer class="footer mt-auto py-2 bg-light">
            <div class="container-fluid">
                <div class="row">
                    <span class="text-muted text-center">MTG Akashic Records &copy; <?= date('Y'); ?></span>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        
        <?php if (!empty($this->script)): ?>
            <!-- Page script -->
            <script>
                <?= $this->script; ?>
            </script>
        <?php endif; ?>
    </body>
</html>
