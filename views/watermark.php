<?php

use app\core\form\Form;

$this->title = 'Watermark';

$this->script = Form::script();

?>

<div class="col-md-4 offset-md-4">
    <h1 class="text-center">Watermark</h1>
    <p class="lead text-center mb-4">Please select an image you would like to add our watermark to.</p>
    <?php $form = Form::begin('/watermark', 'post', '', true); ?>
        <div class="offset-md-4 mb-5">
            <input type="file" name="image" accept="image/jpeg">
        </div>
        <div class="d-grid">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Submit</button>
        </div>
    <?php Form::end(); ?>
</div>