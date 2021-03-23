<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\middlewares\AuthMiddleware;

use app\models\DbUser;
use app\models\Login;

class AuthController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response) {
        $login = new Login();

        if ($request->isPost()) {
            $login->loadData($request->getBody());

            if ($login->validate() && $login->logIn()) {
                $response->redirect('/');
            } else {
                Application::$app->session->setFlash('error', 'Login failed.');
            }
        }

        $this->layout = self::LAYOUT_AUTH;

        return $this->render('login', [
            'model' => $login
        ]);
    }

    public function register(Request $request, Response $response) {
        $user = new DbUser();

        if ($request->isPost()) {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash('success', 'Account created successfully. You can now login using your entered credentials.');

                $response->redirect('/login');
            }
        }

        $this->layout = self::LAYOUT_AUTH;

        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response) {
        Application::$app->logOut();

        Application::$app->session->setFlash('success', 'Logged out successfully.');

        $response->redirect('/login');
    }

    public function profile() {
        $this->layout = self::LAYOUT_MAIN;

        return $this->render('profile');
    }

}