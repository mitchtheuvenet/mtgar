<?php

use app\core\form\Form;

$this->title = 'Edit deck';

$this->script = Form::script();

?>

<div class="col-md-4 offset-md-4">
    <h1 class="text-center mb-4">Edit your deck</h1>
    <?php $form = Form::begin('/decks/edit', 'post'); ?>
        <input type="hidden" name="id" id="id" value="<?= $model->id; ?>">
        <?= $form->inputField($model, 'title', 3, 'Can be up to 48 characters.'); ?>
        <?= $form->inputField($model, 'description', 3, 'Can be up to 128 characters.'); ?>
        <div class="mb-5">
            <p class="text-center">Colors</p>
            <div class="d-flex justify-content-around mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="colorW" id="colorW" value="W"<?php echo !empty($model->colorW) ? ' checked' : ''; ?>>
                    <label class="form-check-label" for="colorW"><img src="/images/mana/W.svg" alt="White" width="28" height="28"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="colorU" id="colorU" value="U"<?php echo !empty($model->colorU) ? ' checked' : ''; ?>>
                    <label class="form-check-label" for="colorU"><img src="/images/mana/U.svg" alt="Blue" width="28" height="28"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="colorB" id="colorB" value="B"<?php echo !empty($model->colorB) ? ' checked' : ''; ?>>
                    <label class="form-check-label" for="colorB"><img src="/images/mana/B.svg" alt="Black" width="28" height="28"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="colorR" id="colorR" value="R"<?php echo !empty($model->colorR) ? ' checked' : ''; ?>>
                    <label class="form-check-label" for="colorR"><img src="/images/mana/R.svg" alt="Red" width="28" height="28"></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="colorG" id="colorG" value="G"<?php echo !empty($model->colorG) ? ' checked' : ''; ?>>
                    <label class="form-check-label" for="colorG"><img src="/images/mana/G.svg" alt="Green" width="28" height="28"></label>
                </div>
            </div>
            <div class="form-text text-center">Leave unselected for <img src="/images/mana/C.svg" alt="Colorless" width="20" height="20"> (colorless) decks.</div>
        </div>
        <div class="d-grid mb-2">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Save</button>
        </div>
    <?php Form::end(); ?>
    <div class="text-center">
        <span>Changed your mind? Go back to <a href="/decks">your decks</a>.</span>
    </div>
</div>