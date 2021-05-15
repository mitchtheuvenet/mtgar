<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\exceptions\ForbiddenException;

use app\core\middlewares\AuthMiddleware;

use app\models\DbCard;
use app\models\DbDeck;

class CardController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['searchCards', 'addCardToDeck']));

        $this->layout = self::LAYOUT_MAIN;
    }

    public function searchCards(Request $request, Response $response) {
        $requestBody = $request->getBody();
        
        $deckDisplayId = $requestBody['d'] ?? '';

        if (!empty($deckDisplayId) && DbDeck::validateDisplayId($deckDisplayId)) {
            $deck = DbDeck::findObject(['display_id' => ['value' => $deckDisplayId]]);

            if (empty($deck)) {
                $response->redirect('/decks');
            }

            if ($deck->user !== Application::$app->user->id) {
                throw new ForbiddenException();
            }

            if (!empty($requestBody['q'])) {
                $cardQuery = $requestBody['q'];

                $cardQueryResponse = \mtgsdk\Card::where(['name' => $cardQuery])->all();

                if (!empty($cardQueryResponse)) {
                    $results = [];

                    foreach ($cardQueryResponse as $card) {
                        if (isset($card->imageUrl)) {
                            if (isset($results[$card->name])) {
                                if (intval($card->multiverseid) > $results[$card->name]['multiverseid']) {
                                    continue;
                                }
                            }

                            $results[$card->name] = [
                                'supertype' => $card->supertypes[0] ?? '',
                                'type' => $card->types[0],
                                'multiverseid' => intval($card->multiverseid)
                            ];
                        }
                    }
                } else {
                    $this->setFlash('error', 'No cards found with such name.');
                }
            }

            return $this->render('cards_search', [
                'deck' => $deck,
                'query' => $cardQuery ?? '',
                'results' => $results ?? []
            ]);
        }

        $response->redirect('/decks');
    }

    public function addCardToDeck(Request $request, Response $response) {
        $requestBody = $request->getBody();

        $deckDisplayId = $requestBody['d'] ?? '';
        $cardMultiverseid = $requestBody['c'] ?? '';

        if ((!empty($deckDisplayId) && DbDeck::validateDisplayId($deckDisplayId)) && !empty($cardMultiverseid)) {
            $deck = DbDeck::findObject(['display_id' => ['value' => $deckDisplayId]], ['id', 'user']);

            if (empty($deck)) {
                $response->redirect('/decks');
            }

            if ($deck->user !== Application::$app->user->id) {
                throw new ForbiddenException();
            }

            $card = DbCard::findObject(['multiverseid' => ['value' => intval($cardMultiverseid)]], ['id', 'name']);

            $cardId = 0;
            $cardName = '';

            if (empty($card)) {
                $newCard = new DbCard();
                $newCard->multiverseid = intval($cardMultiverseid);

                if (!$newCard->validate() || !$newCard->save()) {
                    $this->setFlash('danger', 'Something went wrong while adding the card to your deck.');

                    $response->redirect("/cards/search?d={$deckDisplayId}");
                }

                $cardId = DbCard::lastInsertId();
                $cardName = $newCard->name;
            } else {
                $cardId = $card->id;
                $cardName = $card->name;
            }

            if (isset($requestBody['a'])) {
                $amount = $requestBody['a'];

                if ($deck->addCard($cardId, intval($amount))) {
                    $this->setFlash('success', "<strong>{$amount}x {$cardName}</strong> has been added to your deck.");

                    $response->redirect("/cards/search?d={$deckDisplayId}");
                }
            } else if (isset($requestBody['cr'])) {
                // TODO: check if deck already has 2 commanders, cancel if true

                if (!$deck->containsCard($cardId, true) && $deck->addCommander($cardId)) {
                    $this->setFlash('success', "<strong>{$cardName}</strong> has been added as commander to your deck.");
                } else {
                    $this->setFlash('warning', "<strong>{$cardName}</strong> has already been added as commander to your deck.");
                }

                $response->redirect("/cards/search?d={$deckDisplayId}");
            }
            
            $this->setFlash('danger', "Something went wrong while adding the card to your deck.");

            $response->redirect("/cards/search?d={$deckDisplayId}");
        }

        $response->redirect('/decks');
    }

    public function removeCardFromDeck(Request $request, Response $response) {
        echo '<pre>';
        var_dump($request->getBody());
        echo '</pre>';
        die;
    }

}