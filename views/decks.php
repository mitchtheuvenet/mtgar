<?php

use app\core\form\Form;

$this->title = 'Decks';

$this->script = Form::script();
$this->script .= <<<'JS'
    const modalDeckName = document.getElementById('deckName');
    const modalDeleteBtn = document.getElementById('submitBtn');

    function updateModal(deckId) {
        const deckName = document.getElementById('deckName' + deckId);
        const deckForm = document.getElementById('deleteForm' + deckId);

        modalDeckName.innerHTML = deckName.innerHTML;
        modalDeleteBtn.setAttribute('form', deckForm.id);
    }
JS;

/*

Rarity CSS colors
- Common: black
- Uncommon: lightblue
- Rare: orange
- Mythic: redorange

*/

?>

<div class="col-md-8 offset-md-2">
    <h1 class="text-center mb-4">Decks</h1>
    <div class="text-center mb-4">
        <a href="/decks/create" class="btn btn-success"><i class="bi bi-plus"></i> Create new deck</a>
    </div>
    <?php if (!empty($decks)): ?>
        <div class="d-flex flex-row justify-content-center mb-3">
            <?php foreach($decks as $i => $deck): ?>
                <div class="card mx-2" style="width:23%;">
                    <div class="d-flex justify-content-center py-4 bg-light card-img-top">
                        <?php $colorArray = str_split($deck['colors']); ?>
                        <?php foreach ($colorArray as $color): ?>
                            <img src="/images/mana/<?= $color; ?>.svg" alt="<?= $color; ?>" width="48" height="48">
                        <?php endforeach; ?>
                    </div>
                    <div class="d-flex flex-column card-body">
                        <h5 id="deckName<?= $deck['id']; ?>" class="card-title"><?= $deck['name']; ?></h5>
                        <p class="card-text"><?= $deck['description']; ?></p>
                        <div class="d-flex justify-content-between mt-auto">
                            <a href="/decks/view?deck=<?= $deck['id']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i> View</a>
                            <a href="/decks/edit?deck=<?= $deck['id']; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            <form id= "deleteForm<?= $deck['id']; ?>" action="/decks/delete" method="post" onsubmit="disableSubmitBtn();">
                                <input type="hidden" name="deckId" value="<?= $deck['id']; ?>">
                                <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#confirmModal" value="<?= $deck['id']; ?>" onclick="updateModal(this.value)"><i class="bi bi-trash-fill"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php echo (1 + $i) % 4 === 0 ? '</div><div class="d-flex flex-row justify-content-center mb-3">' : ''; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Confirmation modal -->
<div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLbl" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLbl">Deck deletion confirmation</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your deck: <strong id="deckName"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" id="submitBtn" form="" class="btn btn-outline-danger">Delete</button>
            </div>
        </div>
    </div>
</div>