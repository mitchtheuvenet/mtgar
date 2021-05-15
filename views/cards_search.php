<?php

use app\core\form\Form;

$this->title = 'Card search';

$this->script = Form::script();

$this->script .= <<<'JS'
    function disableBtn(formId) {
        const btn = document.getElementById('b' + formId);

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:1em;height:1em;" role="status" aria-hidden="true"></span><span class="visually-hidden">Submitting...</span>';
    }
JS;

$this->style = <<<'CSS'
    .result-card > div {
        background: rgba(255, 255, 255, 0.6);
        visibility: hidden;
    }

    .result-card:hover > div {
        visibility: visible;
    }
CSS;

?>

<div class="col-md-4 offset-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/decks/view?d=<?= $deck->display_id; ?>" role="button" class="btn btn-sm btn-outline-secondary" tabindex="-1"><i class="bi bi-arrow-left"></i> Back</a>
        <div class="text-center">
            <h1>Card search</h1>
            <p class="lead">Deck: <strong><?= $deck->title; ?></strong></p>
        </div>
        <a href="#" role="button" class="invisible btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
    <form action="/cards/search" method="get" onsubmit="disableSubmitBtn()">
        <input type="hidden" name="d" value="<?= $deck->display_id; ?>">
        <div class="input-group input-group-lg mb-5">
            <input class="form-control" type="search" name="q" placeholder="Card name..." aria-label="Search"<?= $query ? " value=\"{$query}\"" : ''; ?>>
            <button id="submitBtn" class="btn btn-primary" type="submit" tabindex="-1" aria-hidden="true"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div>
<?php if (!empty($results)): ?>
    <div class="col-md-10 offset-md-1">
        <h2 class="text-center mb-4">Search results</h2>
        <div class="d-flex flex-row <?= count($results) > 7 ? 'overflow-auto' : 'justify-content-center'; ?>">
            <?php foreach ($results as $cardName => $card): ?>
                <div class="result-card mx-2" style="width:223px;height:311px;background-image:url('https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?= $card['multiverseid']; ?>&type=card');background-size:cover;">
                    <div class="d-flex flex-column justify-content-center rounded-3" style="width:223px;height:311px;">
                        <form id="cf__<?= $card['multiverseid']; ?>" action="/cards/add" method="post" onsubmit="disableBtn(this.id)">
                            <input type="hidden" name="d" value="<?= $deck->display_id; ?>">
                            <input type="hidden" name="c" value="<?= $card['multiverseid']; ?>">
                            <select class="form-select form-select-sm rounded-0" name="a">
                                <option value="1" selected>Amount: (1)</option>
                                <?php for ($i = 2; $i < 100; $i++): ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" id="bcf__<?= $card['multiverseid']; ?>" class="btn btn-success rounded-0 w-100"><i class="bi bi-plus"></i> Add to deck</button>
                        </form>
                        <?php if ($card['supertype'] === 'Legendary' && ($card['type'] === 'Creature' || $card['type'] === 'Planeswalker')): ?>
                            <form id="crf__<?= $card['multiverseid']; ?>" action="/cards/add" method="post" onsubmit="disableBtn(this.id)" class="mt-3">
                                <input type="hidden" name="d" value="<?= $deck->display_id; ?>">
                                <input type="hidden" name="c" value="<?= $card['multiverseid']; ?>">
                                <input type="hidden" name="cr" value="1">
                                <button type="submit" id="bcrf__<?= $card['multiverseid']; ?>" class="btn btn-secondary rounded-0 w-100"><i class="bi bi-plus"></i> Add as commander</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>