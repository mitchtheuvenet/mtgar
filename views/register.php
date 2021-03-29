<?php

use app\core\Application;

use app\core\form\Form;

$this->title = 'Register';

$errorFlash = Application::$app->session->getFlash('error');

?>

<div class="row">
    <div class="col-md-4 offset-md-4 card bg-light mt-4 mb-4">
        <a href="/" class="p-5" tabindex="-1">
            <img src="images/logo.png" class="card-img-top" alt="Logo">
        </a>
        <div class="card-body">
            <?php if (!empty($errorFlash)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= $errorFlash; ?>
                </div>
            <?php endif; ?>
            <h2 class="card-title text-center mb-4">Create an account</h2>
            <?php $form = Form::begin('/register', 'post'); ?>
                <?= $form->inputField($model, 'username', 3,
                        'Must be between 4&ndash;16 characters long, only consisting of alphabetical and/or numerical characters.'); ?>
                <?= $form->inputField($model, 'password', 2,
                        'Must be at least 8 characters long. It is advised to use a combination of lower-/uppercase letters, numbers and special characters.')->passwordField(); ?>
                <?= $form->inputField($model, 'passwordConfirm')->passwordField(); ?>
                <?= $form->inputField($model, 'email', 2, 'Will never be shared with third parties.')->emailField(); ?>
                <?= $form->inputField($model, 'emailConfirm', 5)->emailField(); ?>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>
            <?php Form::end(); ?>
            <div class="card-text text-center">
                <div class="row">
                    <span>Already registered? <a href="/login">Log in</a>.</span>
                </div>
            </div>
        </div>
    </div>
</div>