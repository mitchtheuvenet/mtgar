<?php

use app\core\Application;

use app\core\form\InputField;
use app\core\form\Form;

$this->title = 'Login';

$successFlash = Application::$app->session->getFlash('success');
$errorFlash = Application::$app->session->getFlash('error');

?>

<div class="row align-items-center h-100">
    <div class="col-md-4 offset-md-4 card bg-light">
        <a href="/" class="p-5" tabindex="-1">
            <img src="images/logo.png" class="card-img-top" alt="Logo">
        </a>
        <div class="card-body">
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
            <h2 class="card-title text-center">Log in to proceed</h2>
            <?php $form = Form::begin('/login', 'post'); ?>
                <?= $form->inputField($model, 'username'); ?>
                <?= $form->inputField($model, 'password', 5)->passwordField(); ?>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Log in</button>
                </div>
                <div class="card-text text-center">
                    <div class="mb-1">
                        <a href="#">Forgot password</a>?
                    </div>
                    New here? <a href="/register">Create an account</a>.
                </div>
            <?php Form::end(); ?>
        </div>
    </div>
</div>