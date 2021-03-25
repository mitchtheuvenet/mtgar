<?php

use app\core\Application;

use app\core\form\InputField;
use app\core\form\Form;

$this->title = 'Contact';

$successFlash = Application::$app->session->getFlash('success');

?>

<div class="row">
    <div class="col-md-4 offset-md-4 mt-5">
        <h1 class="text-center mb-4">Contact us</h1>
        <?php if (!empty($successFlash)): ?>
            <div class="alert alert-success" role="alert">
                <?= $successFlash; ?>
            </div>
        <?php endif; ?>
        <?php $form = Form::begin('/contact', 'post'); ?>
            <?= $form->inputField($model, 'email')->emailField(); ?>
            <?= $form->inputField($model, 'name'); ?>
            <?= $form->inputField($model, 'subject'); ?>
            <?= $form->textareaField($model, 'body', 5); ?>
            <div class="d-grid mb-2">
                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
            </div>
        <?php Form::end(); ?>
    </div>
</div>