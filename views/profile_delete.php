<?php

use app\core\form\Form;

$this->title = 'Delete account';

?>

<h2 class="card-title text-center mb-4">Delete your account</h2>
<?php $form = Form::begin('/profile/delete', 'post'); ?>
    <?= $form->inputField($model, 'password', 5)->passwordField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">Send code</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <span>Changed your mind? Go back to <a href="/profile">profile</a>.</span>
</div>