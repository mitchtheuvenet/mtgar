<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\middlewares\AdminMiddleware;
use app\core\middlewares\AuthMiddleware;

use app\models\Contact;
use app\models\DbUser;

class SiteController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AdminMiddleware(['users']));
        $this->registerMiddleware(new AuthMiddleware(['profile', 'decks']));

        $this->layout = self::LAYOUT_MAIN;
    }

    public function home() {
        return $this->render('home', [
            'name' => Application::$app->user->username ?? 'guest'
        ]);
    }

    public function profile() {
        return $this->render('profile', [
            'user' => Application::$app->user
        ]);
    }

    public function contact(Request $request, Response $response) {
        $contact = new Contact();

        if ($request->isPost()) {
            $contact->loadData($request->getBody());

            if ($contact->validate()) {
                if ($contact->send()) {
                    $this->setFlash('success', 'Thank you for contacting us. We will get in touch with you soon.');

                    $response->redirect('/contact');
                } else {
                    $this->setFlash('error', 'Something went wrong while submitting your message. Please try again later.');
                }
            }
        }

        return $this->render('contact', [
            'model' => $contact
        ]);
    }

    public function decks() {
        return $this->render('decks');
    }

    public function users(Request $request, Response $response) {
        $index = 0;
        $users = [];
        $rowsLeft = 0;

        if ($request->isGet()) {
            $indexStr = $request->getBody()['index'] ?? 0;

            $index = intval($indexStr);

            if ($index < 0) {
                $index = 0;
            }

            $where = [
                'status' => [
                    'value' => DbUser::STATUS_DELETED,
                    'operator' => '!='
                ],
                'admin' => [
                    'value' => false
                ]
            ];

            $users = DbUser::findArray($where, ['id', 'username', 'email', 'status', 'created_at'], $index);

            $rowCount = DbUser::countRows($where, $index + 1);

            $rowsLeft = intval($rowCount) - ($index + 1) * DbUser::QUERY_LIMIT;
        }

        return $this->render('users', [
            'users' => $users,
            'index' => $index,
            'rowsLeft' => $rowsLeft
        ]);
    }

}