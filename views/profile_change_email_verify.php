<?php

use app\core\form\Form;

$this->title = 'Verify new e-mail address';

$digits = $model::getCodeDigits();

?>

<h2 class="card-title text-center mb-4">Verify your new e-mail address</h2>
<?php $form = Form::begin('/profile/change/email/verify', 'post'); ?>
    <input type="hidden" id="email" name="email" value="<?= $model->email; ?>">
    <input type="hidden" id="newEmail" name="newEmail" value="<?= $model->newEmail; ?>">
    <?= $form->inputField($model, 'verificationCode', 5, "Must be a {$digits}-digit numerical code."); ?>
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">Verify</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <span>Changed your mind? Go back to <a href="/profile">profile</a>.</span>
</div>