<?php

use app\core\Application;

use app\core\form\InputField;
use app\core\form\Form;

$this->title = 'Contact';

$this->script = <<<'EOT'
    const textarea = document.getElementById('body');
    const textareaDesc = document.getElementById('body_desc');
    const maxLength = 500;

    textarea.addEventListener('input', event => {
        const target = event.currentTarget;
        const currentLength = target.value.length;

        if (currentLength > maxLength) {
            textarea.classList.add('is-invalid');
            textareaDesc.classList.add('invalid-feedback');
            textareaDesc.classList.remove('form-text');
        } else {
            if (textarea.classList.contains('is-invalid')) {
                textarea.classList.remove('is-invalid');
                textareaDesc.classList.remove('invalid-feedback');
                textareaDesc.classList.add('form-text');
            }
        }

        textareaDesc.innerHTML = `${currentLength}/${maxLength}`;
    });
EOT;

$successFlash = Application::$app->session->getFlash('success');
$errorFlash = Application::$app->session->getFlash('error');

?>

<div class="col-md-4 offset-md-4">
    <h1 class="text-center">Contact us</h1>
    <p class="lead text-center mb-4">Please fill out the contact form below.</p>
    <?php if (!empty($successFlash)): ?>
        <div class="alert alert-success text-center" role="alert">
            <?= $successFlash; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($errorFlash)): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?= $errorFlash; ?>
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