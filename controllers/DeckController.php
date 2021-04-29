<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\exceptions\ForbiddenException;
use app\core\exceptions\NotFoundException;

use app\core\middlewares\AuthMiddleware;

use app\models\DbDeck;

class DeckController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['decks', 'createDeck', 'editDeck', 'deleteDeck', 'viewDeck']));

        $this->layout = self::LAYOUT_MAIN;
    }

    public function decks(Request $request) {
        $pageNumber = $request->getBody()['p'] ?? 1;

        if (!is_int($pageNumber) && is_numeric($pageNumber)) {
            $pageNumber = intval($pageNumber);
        } else {
            $pageNumber = 1;
        }

        $index = $pageNumber - 1;

        $where = [
            'user' => [
                'value' => Application::$app->user->id
            ]
        ];

        $decks = DbDeck::findArray($where, ['id', 'display_id', 'user', 'title', 'description', 'colors'], $index, 'title');

        if (!empty($decks)) {
            $rowCount = DbDeck::countRows($where);

            $pageCount = ceil(intval($rowCount) / DbDeck::queryLimit());
            
            $rowsLeft = intval($rowCount) - ($pageNumber) * DbDeck::queryLimit();
        }

        return $this->render('decks', [
            'decks' => $decks,
            'pageNumber' => $pageNumber,
            'pageCount' => $pageCount ?? 0,
            'rowsLeft' => $rowsLeft ?? 0
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
            $deckId = $request->getBody()['d'] ?? '';

            if (!empty($deckId)) {
                $deck = DbDeck::findObject(['display_id' => ['value' => $deckId, 'operator' => '= BINARY']]);

                if (empty($deck)) {
                    throw new NotFoundException();
                }

                if ($deck->user !== Application::$app->user->id) {
                    throw new ForbiddenException();
                }

                $colorLetters = str_split($deck->colors);

                foreach ($colorLetters as $letter) {
                    $attribute = 'color' . $letter;

                    $deck->{$attribute} = $letter;
                }
            } else {
                throw new NotFoundException();
            }
        } else if ($request->isPost()) {
            $deck->loadData($request->getBody());

            if ($deck->validate()) {
                $oldDeck = !empty($deck->id) ? DbDeck::findObject(['id' => ['value' => $deck->id], 'user' => ['value' => Application::$app->user->id]]) : false;

                if (!empty($oldDeck) && $deck->update(['title', 'description', 'colors'])) {
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

    public function viewDeck(Request $request) {
        $deckId = $request->getBody()['d'] ?? '';

        if (!empty($deckId)) {
            $deck = DbDeck::findObject(['display_id' => ['value' => $deckId, 'operator' => '= BINARY']]);

            if (empty($deck)) {
                throw new NotFoundException();
            }

            if ($deck->user !== Application::$app->user->id) {
                throw new ForbiddenException();
            }

            return $this->render('decks_view', [
                'deckTitle' => $deck->title
            ]);
        }

        throw new NotFoundException();
    }

}