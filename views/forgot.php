<?php

use app\core\Application;

use app\core\form\Form;

$this->title = 'Forgot password';

$errorFlash = Application::$app->session->getFlash('error');
$infoFlash = Application::$app->session->getFlash('info');

?>

<div class="row align-items-center h-100">
    <div class="col-md-4 offset-md-4 card bg-light">
        <a href="/" class="p-5" tabindex="-1">
            <img src="../images/logo.png" class="card-img-top" alt="Logo">
        </a>
        <div class="card-body">
            <?php if (!empty($infoFlash)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <?= $infoFlash; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errorFlash)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= $errorFlash; ?>
                </div>
            <?php endif; ?>
            <h2 class="card-title text-center mb-4">Verify your identity</h2>
            <?php $form = Form::begin('/login/forgot', 'post'); ?>
                <?= $form->inputField($model, 'email', 5, 'A verification code will be sent to this e-mail address.')->emailField(); ?>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Send code</button>
                </div>
            <?php Form::end(); ?>
            <div class="card-text text-center">
                <div class="row">
                    <span>Remembered your password? <a href="/login">Log in</a>.</span>
                </div>
            </div>
        </div>
    </div>
</div>