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
use app\models\DbUser;

class DeckController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['decks', 'createDeck', 'editDeck', 'deleteDeck', 'viewDeck']));

        $this->layout = self::LAYOUT_MAIN;
    }

    public function decks(Request $request) {
        $requestBody = $request->getBody();

        $userId = Application::$app->user->id;
        $username = Application::$app->user->username;

        if (Application::isAdmin()) {
            $userId = $requestBody['u'] ?? $userId;

            if (!is_int($userId) && is_numeric($userId)) {
                $userId = intval($userId);
            }

            $where = [
                'status' => [
                    'value' => DbUser::STATUS_ACTIVE
                ],
                'admin' => [
                    'value' => false
                ]
            ];

            $usersArray = DbUser::findArray($where, ['id', 'username'], DbUser::BYPASS_QUERY_LIMIT, 'username');

            foreach ($usersArray as $user) {
                if (intval($user['id']) === $userId) {
                    $username = $user['username'];

                    break;
                }
            }
        }

        $where = [
            'user' => ['value' => $userId]
        ];

        $pageNumber = $request->getBody()['p'] ?? 1;

        if (!is_int($pageNumber) && is_numeric($pageNumber)) {
            $pageNumber = intval($pageNumber);
        } else {
            $pageNumber = 1;
        }

        $index = $pageNumber - 1;

        $decks = DbDeck::findArray($where, ['id', 'display_id', 'user', 'title', 'description', 'colors'], $index, 'title');

        if (!empty($decks)) {
            $rowCount = DbDeck::countRows($where);

            $pageCount = ceil(intval($rowCount) / DbDeck::queryLimit());
            
            $rowsLeft = intval($rowCount) - ($pageNumber) * DbDeck::queryLimit();
        }

        return $this->render('decks', [
            'userId' => $userId,
            'username' => $username,
            'usersArray' => $usersArray ?? [],
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

            if (!empty($deckId) && DbDeck::validateDisplayId($deckId)) {
                $deck = DbDeck::findObject(['display_id' => ['value' => $deckId]]);

                if (empty($deck)) {
                    throw new NotFoundException();
                }

                if (!Application::isAdmin() && $deck->user !== Application::$app->user->id) {
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
                $oldDeck = !empty($deck->display_id) ? DbDeck::findObject(['display_id' => ['value' => $deck->display_id]]) : false;

                if (!empty($oldDeck) && (Application::isAdmin() || $oldDeck->user === Application::$app->user->id)) {
                    $deck->id = $oldDeck->id;

                    if ($deck->update(['title', 'description', 'colors'])) {
                        $this->setFlash('success', 'Your deck has been updated.');
    
                        if (Application::isAdmin()) {
                            $response->redirect("/decks?u={$oldDeck->user}");
                        } else {
                            $response->redirect('/decks');
                        }
                    }
                }

                $this->setFlash('error', 'Something went wrong while updating your deck. Please try again later.');
            }
        }

        return $this->render('decks_edit', [
            'model' => $deck
        ]);
    }

    public function deleteDeck(Request $request, Response $response) {
        $deckId = $request->getBody()['d'] ?? '';

        if (!empty($deckId) && DbDeck::validateDisplayId($deckId)) {
            $deck = DbDeck::findObject(['display_id' => ['value' => $deckId]]);

            if (!empty($deck) && (Application::isAdmin() || $deck->user === Application::$app->user->id)) {
                if ($deck->delete()) {
                    $this->setFlash('success', 'Your deck has been deleted.');
    
                    if (Application::isAdmin()) {
                        $response->redirect("/decks?u={$deck->user}");
                    } else {
                        $response->redirect('/decks');
                    }
                }
            }
        }

        $this->setFlash('error', 'Something went wrong while deleting your deck. Please try again later.');
    }

    public function viewDeck(Request $request) {
        $deckId = $request->getBody()['d'] ?? '';

        if (!empty($deckId) && DbDeck::validateDisplayId($deckId)) {
            $deck = DbDeck::findObject(['display_id' => ['value' => $deckId]]);

            if (empty($deck)) {
                throw new NotFoundException();
            }

            if (!Application::isAdmin() && $deck->user !== Application::$app->user->id) {
                throw new ForbiddenException();
            }

            $cards = DbDeck::findCards($deck->id);
            $commanders = DbDeck::findCards($deck->id, true);

            $cardsByType = [];

            foreach ($cards as $card) {
                $cardsByType[$card['type']][] = [
                    'multiverseid' => $card['multiverseid'],
                    'name' => $card['name'],
                    'amount' => $card['amount']
                ];
            }

            return $this->render('decks_view', [
                'deck' => $deck,
                'cards' => $cardsByType,
                'commanders' => $commanders
            ]);
        }

        throw new NotFoundException();
    }

}