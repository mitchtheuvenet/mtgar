<?php

use app\core\form\Form;

$this->title = 'Confirm account deletion';

$digits = $model::getCodeDigits();

?>

<h2 class="card-title text-center mb-4">Confirm account deletion</h2>
<?php $form = Form::begin('/profile/delete/confirm', 'post', 'confirmForm'); ?>
    <input type="hidden" id="email" name="email" value="<?= $model->email; ?>">
    <?= $form->inputField($model, 'verificationCode', 5, "Must be a {$digits}-digit numerical code."); ?>
    <div class="d-grid mb-3">
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#confirmModal">Delete account</button>
    </div>
<?php Form::end(); ?>
<div class="card-text text-center">
    <span>Changed your mind? Go back to <a href="/profile">profile</a>.</span>
</div>

<!-- Confirmation modal -->
<div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLbl" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLbl">Confirm account deletion</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your MTGAR account? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="confirmForm" class="btn btn-outline-danger">Delete</button>
            </div>
        </div>
    </div>
</div>