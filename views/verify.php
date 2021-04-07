<?php

use app\core\form\Form;

$this->title = 'Verify e-mail address';

$this->script = Form::script();

$digits = $model::getCodeDigits();

?>

<h2 class="card-title text-center mb-4">Verify your e-mail address</h2>
<?php $form = Form::begin('/register/verify', 'post'); ?>
    <input type="hidden" id="email" name="email" value="<?= $model->email; ?>">
    <?= $form->inputField($model, 'verificationCode', 5, "Must be a {$digits}-digit numerical code."); ?>
    <div class="d-grid mb-3">
        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Verify</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <div class="row">
        <span>Already registered? <a href="/login">Log in</a>.</span>
    </div>
</div>