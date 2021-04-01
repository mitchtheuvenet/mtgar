<?php

use app\core\form\Form;

$this->title = 'Reset password';

$digits = $model->getCodeDigits();

?>

<h2 class="card-title text-center mb-4">Reset your password</h2>
<?php $form = Form::begin('/login/reset', 'post'); ?>
    <input type="hidden" id="email" name="email" value="<?= $model->email; ?>">
    <?= $form->inputField($model, 'verificationCode', 4, "Must be a {$digits}-digit numerical code."); ?>
    <?= $form->inputField($model, 'password', 2, 'Must be at least 8 characters long.')->passwordField(); ?>
    <?= $form->inputField($model, 'passwordConfirm', 5)->passwordField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">Save</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <div class="row">
        <span>Remember your password? <a href="/login">Log in</a>.</span>
    </div>
</div>