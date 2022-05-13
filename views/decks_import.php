<?php

use app\core\form\Form;

$this->title = 'Import decks';

$this->script = Form::script();

?>

<div class="col-md-4 offset-md-4">
    <h1 class="text-center">Import decks</h1>
    <p class="lead text-center mb-4">Please select the CSV file containing your decks.</p>
    <?php $form = Form::begin('/decks/import', 'post', '', true); ?>
        <div class="offset-md-4 mb-5">
            <input type="file" name="decks" accept="text/csv">
        </div>
        <div class="d-grid">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Submit</button>
        </div>
    <?php Form::end(); ?>
</div>