<?php

use app\core\form\Form;

$this->title = 'Login';

?>

<h2 class="card-title text-center mb-4">Log in to your account</h2>
<?php $form = Form::begin('/login', 'post'); ?>
    <?= $form->inputField($model, 'username'); ?>
    <?= $form->inputField($model, 'password', 5)->passwordField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">Log in</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <div class="row mb-1">
        <span><a href="/login/forgot">Forgot password</a>?</span>
    </div>
    <div class="row">
        <span>New here? <a href="/register">Create an account</a>.</span>
    </div>
</div>