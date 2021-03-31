<?php

use app\core\Application;

$infoFlash = Application::$app->session->getFlash('info');
$successFlash = Application::$app->session->getFlash('success');
$errorFlash = Application::$app->session->getFlash('error');

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
        <link rel="icon" type="image/png" href="<?= in_array($this->title, ['Forgot password']) ? '../' : ''; ?>images/favicon.png">

        <!-- Page title -->
        <title><?= $this->title; ?> &centerdot; MTGAR</title>
    </head>
    <body>
        <div class="container-fluid vh-100">
            <div class="row<?php echo $this->title !== 'Register' ? ' align-items-center h-100' : ''; ?>">
                <div class="col-md-4 offset-md-4 card bg-light my-4">
                    <a href="/" class="p-5" tabindex="-1">
                        <img src="<?= in_array($this->title, ['Forgot password']) ? '../' : ''; ?>images/logo.png" class="card-img-top" alt="Logo">
                    </a>
                    <div class="card-body">
                        <?php if (!empty($infoFlash)): ?>
                            <div class="alert alert-info text-center" role="alert">
                                <?= $infoFlash; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($successFlash)): ?>
                            <div class="alert alert-success text-center" role="alert">
                                <?= $successFlash; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($errorFlash)): ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?= $errorFlash; ?>
                            </div>
                        <?php endif; ?>
                        <!-- Content -->
                        {{content}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>
