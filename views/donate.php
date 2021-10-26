<?php

use app\core\form\Form;

$this->title = 'Donate';

$this->script = Form::script();

?>

<div class="col-md-4 offset-md-4">
    <h1 class="text-center">Donate</h1>
    <p class="lead text-center mb-4">Please enter the amount of EUR you wish to donate.</p>
    <?php $form = Form::begin('/donate', 'post'); ?>
        <?= $form->inputField($model, 'amount', 5, 'Example: 7.25') ?>
        <div class="d-grid">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Donate</button>
        </div>
    <?php Form::end(); ?>
</div>