<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\models\DbUser;
use app\models\Login;

class AuthController extends Controller {

    public function __construct() {
        $this->layout = self::LAYOUT_AUTH;
    }

    public function login(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);
        
        $login = new Login();

        if ($request->isPost()) {
            $login->loadData($request->getBody());

            if ($login->validate()) {
                if ($login->logIn()) {
                    $response->redirect('/');
                } else {
                    $this->setFlash('error', 'Login failed.');
                }
            }
        }

        return $this->render('login', [
            'model' => $login
        ]);
    }

    public function register(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);

        $user = new DbUser();

        if ($request->isPost()) {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()) {
                $this->setFlash('success', 'Your new account has been created. You can now log in using your entered credentials.');

                $response->redirect('/login');
            }
        }

        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response) {
        Application::$app->logOut();

        $this->setFlash('success', 'You have been logged out.');

        $response->redirect('/login');
    }

    private function redirectHomeIfLoggedIn(Response $response) {
        if (!Application::isGuest()) {
            $response->redirect('/');
        }
    }

}