<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

use app\models\RegisterModel;

class AuthController extends Controller {

    public function login(Request $request) {
        if ($request->isGet()) {
            $this->layout = 'auth';

            return $this->render('login');
        }

        if ($request->isPost()) {
            $body = $request->getBody();

            var_dump($body); exit;
        }
    }

    public function register(Request $request) {
        if ($request->isGet()) {
            $this->layout = 'auth';

            return $this->render('register');
        }

        if ($request->isPost()) {
            $registerModel = new RegisterModel();
            $registerModel->loadData($request->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                return 'Success';
            }

            echo '<pre>';
            var_dump($registerModel);
            echo '</pre>';
            exit;

            return $this->render('register', [
                'model' => $registerModel
            ]);
        }
    }

}