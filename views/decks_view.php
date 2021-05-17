<?php

use app\core\Application;

$this->title = $deck->title . ' (MTG deck)';

$this->script = <<<'JS'
    const cardView = document.getElementById('card-view');

    function updateCardView(multiverseid) {
        const cardName = document.getElementById('cn__' + multiverseid).innerText;
        const imgLink = 'https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' + multiverseid + '&type=card';

        cardView.src = imgLink;
        cardView.alt = cardName;
    }

    function disableBtn(formId) {
        const multiverseid = formId.substring(4);

        const btn = document.getElementById('sb__' + multiverseid);

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:1em;height:1em;" role="status" aria-hidden="true"></span><span class="visually-hidden">Submitting...</span>';
    }
JS;

$this->style = <<<'CSS'
    .table-hover > tbody > tr > td:last-child {
        visibility: hidden;
    }

    .table-hover > tbody > tr:hover > td:last-child {
        visibility: visible;
    }

    .table-hover > tbody > tr > td:last-child > button[type="submit"],
    .table-hover > tbody > tr > td:last-child > form > button[type="submit"] {
        background: none;
        color: inherit;
        border: none;
        padding: 0;
        font: inherit;
        cursor: pointer;
        outline: inherit;
    }
CSS;

$cardTotal = 0;
$cardCount = 0;
$columnCount = 1;
$maxCardsPerColumn = 0;

$currentUserIsOwner = $deck->user === Application::$app->user->id;

?>

<div class="col-md-12">
    <div class="col-md-6 offset-md-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/decks<?= Application::isAdmin() ? "?u={$deck->user}" : ''; ?>" role="button" class="btn btn-sm btn-outline-secondary" tabindex="-1"><i class="bi bi-arrow-left"></i> Back</a>
            <div class="text-center">
                <h1><?= $deck->title; ?></h1>
                <p class="lead"><?= $deck->description; ?></p>
            </div>
            <a href="#" role="button" class="invisible btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>
    <?php if ($currentUserIsOwner): ?>
        <div class="text-center mb-4">
            <a href="/cards/search?d=<?= $deck->display_id; ?>" class="btn btn-success"><i class="bi bi-plus"></i> Add cards</a>
        </div>
    <?php endif; ?>
    <?php if (empty($commanders) && empty($cards)): ?>
        <?php if ($currentUserIsOwner): ?>
            <p class="lead text-center">Your deck contains no cards. Click the button above to add them.</p>
        <?php else: ?>
            <p class="lead text-center">This deck contains no cards.</p>
        <?php endif; ?>
    <?php else: ?>
        <div class="d-flex flex-row justify-content-center">
            <div class="col-md-2">
                <div class="sticky-top" style="top:5.625rem;">
                    <?php if (!empty($commanders)): ?>
                        <img id="card-view" width="223" height="311" class="d-block mx-auto img-fluid" src="https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?= $commanders[0]['multiverseid']; ?>&type=card" alt="<?= $commanders[0]['name']; ?>">
                    <?php elseif (!empty($cards)): ?>
                        <img id="card-view" width="223" height="311" class="d-block mx-auto img-fluid" src="https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?= $cards[array_key_first($cards)][0]['multiverseid']; ?>&type=card" alt="<?= $cards[array_key_first($cards)][0]['name']; ?>">
                    <?php else: ?>
                        <img id="card-view" width="223" height="311" class="d-block mx-auto img-fluid" src="https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=0&type=card" alt="Card image">
                    <?php endif; ?>
                    <button class="d-block mx-auto btn btn-outline-primary mt-5" type="submit"><i class="bi bi-file-earmark-text-fill"></i> Export to PDF</button>
                </div>
            </div>
            <div class="col-md-2">
                <?php if (!empty($commanders)): ?>
                    <?php $cardTotal = $cardCount = count($commanders); ?>
                    <table class="table table-sm table-hover table-borderless">
                        <thead>
                            <tr>
                                <th scope="col" colspan="3">Commander (<?= count($commanders); ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commanders as $commander): ?>
                                <tr id="<?= $commander['multiverseid']; ?>" onmouseover="updateCardView(this.id)">
                                    <td class="py-0 text-end text-secondary" style="width:10%;">1</td>
                                    <td id="cn__<?= $commander['multiverseid']; ?>" class="py-0" style="width:80%;"><?= $commander['name']; ?></td>
                                    <?php if ($currentUserIsOwner): ?>
                                        <td class="py-0 text-end" style="width:10%;">
                                            <form id="rf__<?= $commander['multiverseid']; ?>" action="/cards/remove" method="post" onsubmit="disableBtn(this.id)">
                                                <input type="hidden" name="d" value="<?= $deck->display_id; ?>">
                                                <input type="hidden" name="c" value="<?= $commander['multiverseid']; ?>">
                                                <input type="hidden" name="cr" value="1">
                                                <button id="sb__<?= $commander['multiverseid']; ?>" type="submit"><i class="bi bi-trash-fill text-danger"></i></button>
                                            </form>
                                        </td>
                                    <?php else: ?>
                                        <td class="py-0 invisible" style="width:10%;"></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <?php if (!empty($cards)): ?>
                    <?php

                        foreach ($cards as $typeCards) {
                            $cardTotal += count($typeCards);
                        }

                        $maxCardsPerColumn = intval(floor($cardTotal / 4));

                    ?>
                    <?php foreach ($cards as $typeKey => $typeCards): ?>
                        <?php

                            $typeCardTotal = 0;
                            
                            foreach ($typeCards as $card) {
                                $typeCardTotal += intval($card['amount']);
                            }

                        ?>
                        <table class="table table-sm table-hover table-borderless">
                            <thead>
                                <th scope="col" colspan="3"><?= $typeKey; ?> (<?= $typeCardTotal; ?>)</th>
                            </thead>
                            <tbody>
                                <?php foreach ($typeCards as $card): ?>
                                    <?php $cardCount++; ?>
                                    <tr id="<?= $card['multiverseid']; ?>" onmouseover="updateCardView(this.id)">
                                        <td class="py-0 text-end text-secondary" style="width:10%;"><?= $card['amount']; ?></td>
                                        <td id="cn__<?= $card['multiverseid']; ?>" class="py-0" style="width:80%;"><?= $card['name']; ?></td>
                                        <?php if ($currentUserIsOwner): ?>
                                            <td class="py-0 text-end" style="width:10%;">
                                                <form id="rf__<?= $card['multiverseid']; ?>" action="/cards/remove" method="post" onsubmit="disableBtn(this.id)">
                                                    <input type="hidden" name="d" value="<?= $deck->display_id; ?>">
                                                    <input type="hidden" name="c" value="<?= $card['multiverseid']; ?>">
                                                    <button id="sb__<?= $card['multiverseid']; ?>" type="submit"><i class="bi bi-trash-fill text-danger"></i></button>
                                                </form>
                                            </td>
                                        <?php else: ?>
                                            <td class="py-0 invisible" style="width:10%;"></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if ($cardCount >= $maxCardsPerColumn && $columnCount < 4): ?>
                            <?php $cardCount = 0; $columnCount++; ?>
                            </div><div class="col-md-2">
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php

                    while ($columnCount < 4) {
                        echo '</div><div class="col-md-2">';

                        $columnCount++;
                    }

                ?>
            </div>
        </div>
    <?php endif; ?>
</div>