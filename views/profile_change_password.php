<?php

use app\core\form\Form;

$this->title = 'Change password';

$this->script = Form::script();

?>

<h2 class="card-title text-center mb-4">Change your password</h2>
<?php $form = Form::begin('/profile/change/password', 'post'); ?>
    <?= $form->inputField($model, 'password')->passwordField(); ?>
    <?= $form->inputField($model, 'newPassword', 2, 'Must be at least 8 characters long.')->passwordField(); ?>
    <?= $form->inputField($model, 'newPasswordConfirm', 5)->passwordField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Save</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <span>Changed your mind? Go back to <a href="/profile">profile</a>.</span>
</div>