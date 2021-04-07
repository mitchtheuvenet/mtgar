<?php

use app\core\form\Form;

$this->title = 'Change e-mail address';

$this->script = Form::script();

?>

<h2 class="card-title text-center mb-4">Change your e-mail address</h2>
<?php $form = Form::begin('/profile/change/email', 'post'); ?>
    <?= $form->inputField($model, 'password')->passwordField(); ?>
    <?= $form->inputField($model, 'newEmail')->emailField(); ?>
    <?= $form->inputField($model, 'newEmailConfirm', 5)->emailField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Send code</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <span>Changed your mind? Go back to <a href="/profile">profile</a>.</span>
</div>