<?php

use app\core\form\InputField;
use app\core\form\Form;

$this->title = 'Register';

?>

<div class="row">
    <div class="col-md-4 offset-md-4 card bg-light mt-4 mb-4">
        <a href="/" class="p-5">
            <img src="images/logo.png" class="card-img-top" alt="Logo">
        </a>
        <div class="card-body">
            <h2 class="card-title text-center">Create an account</h2>
            <?php $form = Form::begin('/register', 'post'); ?>
                <?= $form->inputField($model, 'username', 3,
                        'Must be between 4&ndash;16 characters long, only consisting of alphabetical and/or numerical characters.'); ?>
                <?= $form->inputField($model, 'password', 2,
                        'Must be at least 8 characters long. It is advised to use a combination of lower-/uppercase letters, numbers and special characters.')->passwordField(); ?>
                <?= $form->inputField($model, 'passwordConfirm')->passwordField(); ?>
                <?= $form->inputField($model, 'email', 2, 'Will never be shared with third parties.')->emailField(); ?>
                <?= $form->inputField($model, 'emailConfirm', 5)->emailField(); ?>
                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>
            <?php Form::end(); ?>
        </div>
    </div>
</div>