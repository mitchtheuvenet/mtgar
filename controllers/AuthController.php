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
                    Application::$app->session->setFlash('error', 'Login failed.');
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
                Application::$app->session->setFlash('success', 'Account created successfully. You can now log in using your entered credentials.');

                $response->redirect('/login');
            }
        }

        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response) {
        Application::$app->logOut();

        Application::$app->session->setFlash('success', 'Logged out successfully.');

        $response->redirect('/login');
    }

    private function redirectHomeIfLoggedIn(Response $response) {
        if (!Application::isGuest()) {
            $response->redirect('/');
        }
    }

}