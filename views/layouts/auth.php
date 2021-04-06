<?php

use app\core\Application;

$flashMessages = $this->getFlashMessages();

$nesting = substr_count($this->path(), '/', 1);

$proot = $nesting > 0 ? str_repeat('../', $nesting) : '';

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
        <link rel="icon" type="image/png" href="<?= $proot; ?>images/favicon.png">

        <!-- Page title -->
        <title><?= $this->title; ?> &centerdot; MTGAR</title>
    </head>
    <body>
        <div class="container-fluid vh-100">
            <div class="row<?php echo $this->title !== 'Register' ? ' align-items-center h-100' : ''; ?>">
                <div class="col-md-4 offset-md-4 card bg-light my-4">
                    <a href="/" class="p-5" tabindex="-1">
                        <img src="<?= $proot; ?>images/logo.png" class="card-img-top" alt="Logo">
                    </a>
                    <div class="card-body">
                        <?php
                        
                        foreach ($flashMessages as $key => $message) {
                            $key = $key === 'error' ? 'danger' : $key;
                            $message = $message['value'] ?? '';

                            echo "
                                <div class=\"alert alert-{$key} alert-dismissible fade show text-center\" role=\"alert\">
                                    {$message}
                                    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                                </div>
                            ";
                        }
                        
                        ?>
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
