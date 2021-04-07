<?php

use app\core\form\Form;

$this->title = 'Register';

$this->script = Form::script();

?>

<h2 class="card-title text-center mb-4">Create an account</h2>
<?php $form = Form::begin('/register', 'post'); ?>
    <?= $form->inputField($model, 'username', 3,
            'Must be between 4&ndash;16 characters long, only consisting of alphabetical and/or numerical characters.'); ?>
    <?= $form->inputField($model, 'password', 2,
            'Must be at least 8 characters long.')->passwordField(); ?>
    <?= $form->inputField($model, 'passwordConfirm')->passwordField(); ?>
    <?= $form->inputField($model, 'email', 2, 'Will never be shared with third parties.')->emailField(); ?>
    <?= $form->inputField($model, 'emailConfirm', 5)->emailField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Register</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <div class="row">
        <span>Already registered? <a href="/login">Log in</a>.</span>
    </div>
</div>