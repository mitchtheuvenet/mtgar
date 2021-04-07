<?php

use app\core\form\Form;

$this->title = 'Forgot password';

$this->script = Form::script();

?>

<h2 class="card-title text-center mb-4">Verify your identity</h2>
<?php $form = Form::begin('/login/forgot', 'post'); ?>
    <?= $form->inputField($model, 'email', 5, 'A verification code will be sent to this e-mail address.')->emailField(); ?>
    <div class="d-grid mb-3">
        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Send code</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <div class="row">
        <span>Remember your password? <a href="/login">Log in</a>.</span>
    </div>
</div>