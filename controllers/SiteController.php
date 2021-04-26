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
        $this->registerMiddleware(new AuthMiddleware(['profile']));

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

    public function users(Request $request, Response $response) {
        $index = $request->getBody()['index'] ?? 0;

        if (is_numeric($index)) {
            $index = intval($index);
        } else {
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

        $users = DbUser::findArray($where, ['id', 'username', 'email', 'status', 'created_at'], $index, 'status', false);

        if (!empty($users)) {
            $rowCount = DbUser::countRows($where);

            $pageCount = ceil(intval($rowCount) / DbUser::queryLimit());

            $rowsLeft = intval($rowCount) - ($index + 1) * DbUser::queryLimit();
        }

        return $this->render('users', [
            'users' => $users,
            'index' => $index,
            'pageCount' => $pageCount ?? 0,
            'rowsLeft' => $rowsLeft ?? 0
        ]);
    }

}