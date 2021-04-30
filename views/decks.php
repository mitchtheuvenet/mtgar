<?php

use app\core\Application;

use app\core\form\Form;

$this->title = 'Decks';

$this->script = Form::script();
$this->script .= <<<'JS'
    const modalDeckTitle = document.getElementById('deckTitle');
    const modalDeleteBtn = document.getElementById('submitBtn');

    function updateModal(deckId) {
        const deckTitle = document.getElementById('dt__' + deckId);
        const deckForm = document.getElementById('df__' + deckId);

        modalDeckTitle.innerHTML = deckTitle.innerHTML;
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
    <?php if ($userId !== Application::$app->user->id): ?>
        <h1 class="text-center mb-4"><?= $username; ?>&apos;s decks</h1>
    <?php else: ?>
        <h1 class="text-center mb-4">Your decks</h1>
    <?php endif; ?>
    <?php if (Application::isAdmin()): ?>
        <div class="d-flex justify-content-center mb-4">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="usersDropdown" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-bounding-box"></i> Select user</button>
                <ul class="dropdown-menu" style="height:auto;max-height:30rem;overflow-x:hidden;" aria-labelledby="usersDropdown">
                    <li><a href="/decks" class="dropdown-item"><?= Application::$app->user->username; ?></a></li>
                    <?php foreach ($usersArray as $user): ?>
                        <li><a href="/decks?u=<?= $user['id']; ?>" class="dropdown-item"><?= $user['username']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($userId === Application::$app->user->id): ?>
        <div class="text-center mb-4">
            <a href="/decks/create" class="btn btn-success"><i class="bi bi-plus"></i> Create new deck</a>
        </div>
    <?php endif; ?>
    <?php if (!empty($decks)): ?>
        <div class="d-flex flex-row justify-content-center mb-4">
            <a class="btn btn-primary<?= $pageNumber < 2 ? ' invisible' : '' ?>" href="/decks?u=<?= $userId; ?>&p=<?= $pageNumber - 1; ?>" role="button"><i class="bi bi-arrow-left"></i></a>
            <p class="lead align-self-center mx-4 mb-0">Page <?= $pageNumber; ?> of <?= $pageCount; ?></p>
            <a class="btn btn-primary<?= $rowsLeft < 1 ? ' invisible' : '' ?>" href="/decks?u=<?= $userId; ?>&p=<?= $pageNumber + 1; ?>" role="button"><i class="bi bi-arrow-right"></i></a>
        </div>
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
                        <h5 id="dt__<?= $deck['display_id']; ?>" class="card-title"><?= $deck['title']; ?></h5>
                        <p class="card-text"><?= $deck['description']; ?></p>
                        <div class="d-flex justify-content-between mt-auto">
                            <a href="/decks/view?d=<?= $deck['display_id']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i> View</a>
                            <a href="/decks/edit?d=<?= $deck['display_id']; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            <form id= "df__<?= $deck['display_id']; ?>" action="/decks/delete" method="post" onsubmit="disableSubmitBtn();">
                                <input type="hidden" name="d" value="<?= $deck['display_id']; ?>">
                                <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#confirmModal" value="<?= $deck['display_id']; ?>" onclick="updateModal(this.value)"><i class="bi bi-trash-fill"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php echo (1 + $i) % 4 === 0 ? '</div><div class="d-flex flex-row justify-content-center mb-3">' : ''; ?>
            <?php endforeach; ?>
        </div>
    <?php elseif ($pageNumber === 1): ?>
        <?php if ($userId === Application::$app->user->id): ?>
            <p class="lead text-center">You have no decks saved. Click the button above to create one.</p>
        <?php else: ?>
            <p class="lead text-center">This user has no decks saved.</p>
        <?php endif; ?>
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
                <p>Are you sure you want to delete your deck: <strong id="deckTitle"></strong>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" id="submitBtn" form="" class="btn btn-outline-danger">Delete</button>
            </div>
        </div>
    </div>
</div>