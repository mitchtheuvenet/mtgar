<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\middlewares\AuthMiddleware;

// use app\models\DbCard;
use app\models\DbDeck;

class DeckController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['decks', 'createDeck', 'editDeck', 'deleteDeck']));

        $this->layout = self::LAYOUT_MAIN;
    }

    public function decks() {
        $decks = DbDeck::findArray([
            'user' => [
                'value' => Application::$app->user->id
            ]
        ]);

        return $this->render('decks', [
            'decks' => $decks
        ]);
    }

    public function createDeck(Request $request, Response $response) {
        $deck = new DbDeck();

        if ($request->isPost()) {
            $deck->loadData($request->getBody());

            if ($deck->validate()) {
                if ($deck->save()) {
                    $this->setFlash('success', 'Your new deck has been created.');

                    $response->redirect('/decks');
                }
                
                $this->setFlash('error', 'Something went wrong while creating your new deck. Please try again later.');
            }
        }

        return $this->render('decks_create', [
            'model' => $deck
        ]);
    }

    public function editDeck(Request $request, Response $response) {
        $deck = new DbDeck();

        if ($request->isGet()) {
            $deckId = $request->getBody()['deck'] ?? '';

            $deck = DbDeck::findObject(['id' => ['value' => $deckId], 'user' => ['value' => $deck->user]]);

            if (empty($deck)) {
                $response->redirect('/decks');
            }

            $colorLetters = str_split($deck->colors);

            foreach ($colorLetters as $letter) {
                $attribute = 'color' . $letter;

                $deck->{$attribute} = $letter;
            }
        } else if ($request->isPost()) {
            $deck->loadData($request->getBody());

            if ($deck->validate()) {
                if ($deck->update()) {
                    $this->setFlash('success', 'Your deck has been updated.');

                    $response->redirect('/decks');
                }
                
                $this->setFlash('error', 'Something went wrong while updating your deck. Please try again later.');
            }
        }

        return $this->render('decks_edit', [
            'model' => $deck
        ]);
    }

    public function deleteDeck(Request $request, Response $response) {
        $deckId = $request->getBody()['deckId'] ?? '';

        if (!empty($deckId)) {
            $deck = DbDeck::findObject(['id' => ['value' => $deckId], 'user' => ['value' => Application::$app->user->id]]);

            if (!empty($deck) && $deck->delete()) {
                $this->setFlash('success', 'Your deck has been deleted.');

                $response->redirect('/decks');
            }
        }

        $this->setFlash('error', 'Something went wrong while deleting your deck. Please try again later.');
    }

}