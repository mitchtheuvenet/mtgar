<?php

use app\core\form\Form;

$this->title = 'Contact';

$this->script = Form::script();
$this->script .= <<<'JS'
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
JS;

?>

<div class="col-md-4 offset-md-4">
    <h1 class="text-center">Contact us</h1>
    <p class="lead text-center mb-4">Please fill out the contact form below.</p>
    <?php $form = Form::begin('/contact', 'post'); ?>
        <?= $form->inputField($model, 'email')->emailField(); ?>
        <?= $form->inputField($model, 'name'); ?>
        <?= $form->inputField($model, 'subject'); ?>
        <?= $form->textareaField($model, 'body', 5); ?>
        <div class="d-grid">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Submit</button>
        </div>
    <?php Form::end(); ?>
</div>