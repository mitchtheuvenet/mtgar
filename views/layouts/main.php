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
        <meta name="application-name" content="{{siteTitle}}">
        <meta name="application-version" content="{{siteVersion}}">
        <meta name="author" content="mitchtheuvenet">
        <meta name="description" content="A concise card collection management web application for Magic: the Gathering.">
        <meta name="keywords" content="mtg, magic, the, gathering, collection, management, tcg, ccg, collectible, trading, card, game">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="images/favicon.png">

        <!-- Page title -->
        <title>{{pageTitle}} &centerdot; MTGAR</title>
    </head>
    <body>
        <div class="container-fluid vh-100">
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
