<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\models\DbUser;
use app\models\DbVerification;
use app\models\Login;
use app\models\PasswordReset;

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

    public function forgot(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);

        $verification = new DbVerification();

        if ($request->isPost()) {
            $verification->loadData($request->getBody());

            if ($verification->validate()) {
                if ($verification->sendCode(DbVerification::TYPE_PASSWORD_RESET)) {
                    $email = $verification->email;

                    $this->setFlash('info', "Verification code sent to <strong>{$email}</strong>. Please check your inbox (or spam folder).");

                    $response->redirect("/login/reset?email={$email}");
                } else {
                    $this->setFlash('error', 'Something went wrong while sending the verification code. Please try again later.');
                }
            }
        }

        return $this->render('forgot', [
            'model' => $verification
        ]);
    }

    public function reset(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);

        $passwordReset = new PasswordReset();

        if ($request->isGet()) {
            $email = $request->getBody()['email'] ?? '';

            if (empty($email)) {
                $response->redirect('/login/forgot');
            }

            $passwordReset->email = $email;
        } else if ($request->isPost()) {
            $passwordReset->loadData($request->getBody());

            if ($passwordReset->validate()) {
                if ($passwordReset->apply()) {
                    $this->setFlash('success', 'Your password has been reset. You can now log in using your new password.');

                    $response->redirect('/login');
                } else {
                    $this->setFlash('error', 'Something went wrong while resetting your password. Please try again later.');
                }
            }
        }

        return $this->render('reset', [
            'model' => $passwordReset
        ]);
    }

    public function register(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);

        $user = new DbUser();

        if ($request->isPost()) {
            $user->loadData($request->getBody());

            if ($user->validate()) {
                if ($user->save()) {
                    $this->setFlash('success', 'Your new account has been created. You can now log in using your entered credentials.');

                    $response->redirect('/login');
                } else {
                    $this->setFlash('error', 'Something went wrong while creating your new account. Please try again later.');
                }
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