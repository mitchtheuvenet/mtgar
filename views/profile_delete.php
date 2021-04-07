<?php

use app\core\form\Form;

$this->title = 'Delete account';

$this->script = Form::script();

?>

<h2 class="card-title text-center mb-4">Enter your password</h2>
<?php $form = Form::begin('/profile/delete', 'post'); ?>
    <?= $form->inputField($model, 'password', 5, 'For security reasons, an additional verification code will be sent to your e-mail address.')->passwordField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Send code</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <span>Changed your mind? Go back to <a href="/profile">profile</a>.</span>
</div>