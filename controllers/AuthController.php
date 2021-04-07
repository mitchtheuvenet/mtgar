<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\middlewares\AuthMiddleware;

use app\models\AccountDeleteConfirmation;
use app\models\DbUser;
use app\models\DbVerification;
use app\models\EmailChangeRequest;
use app\models\EmailChangeVerification;
use app\models\Login;
use app\models\PasswordChange;
use app\models\PasswordReset;
use app\models\RegistrationVerification;
use app\models\VerificationWithPassword;

class AuthController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['changePassword', 'changeEmail', 'verifyNewEmail', 'deleteAccount', 'confirmDeleteAccount', 'logout']));

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

                    $login->password = '';
                }
            }
        }

        return $this->render('login', [
            'model' => $login
        ]);
    }

    public function forgotPassword(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);

        $verification = new DbVerification();

        if ($request->isGet()) {
            $email = $request->getBody()['email'] ?? '';

            if (!empty($email)) {
                $verification->email = $email;
            }
        } else if ($request->isPost()) {
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

    public function resetPassword(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);

        $passwordReset = new PasswordReset();

        if ($request->isGet()) {
            $email = $request->getBody()['email'] ?? '';

            if (empty($email)) {
                $response->redirect('/login/forgot');
            } else {
                $passwordReset->email = $email;
            }
        } else if ($request->isPost()) {
            $passwordReset->loadData($request->getBody());

            if ($passwordReset->validate(DbVerification::TYPE_PASSWORD_RESET)) {
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
                    $verification = new DbVerification($user->email);

                    if ($verification->sendCode(DbVerification::TYPE_REGISTRATION)) {
                        $this->setFlash('info', "Your new account has been created, but requires verification. A code has been sent to <strong>{$user->email}</strong>. Please check your inbox (or spam folder).");

                        $urlEmail = urlencode($user->email);

                        $response->redirect("/register/verify?email={$urlEmail}");
                    }
                }
                
                $this->setFlash('error', 'Something went wrong while creating your new account. Please try again later.');
            }
        }

        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function verifyRegistration(Request $request, Response $response) {
        $this->redirectHomeIfLoggedIn($response);

        $registrationVerification = new RegistrationVerification();

        if ($request->isGet()) {
            $email = $request->getBody()['email'] ?? '';

            if (empty($email)) {
                $response->redirect('/register');
            }
            
            $registrationVerification->email = $email;
        } else if ($request->isPost()) {
            $registrationVerification->loadData($request->getBody());

            if ($registrationVerification->validate(DbVerification::TYPE_REGISTRATION)) {
                if ($registrationVerification->confirm()) {
                    $this->setFlash('success', 'Your new account has been verified. You can now log in using your entered credentials.');

                    $response->redirect('/login');
                } else {
                    $this->setFlash('error', 'Something went wrong while verifying your e-mail address. Please try again later.');
                }
            }
        }

        return $this->render('verify', [
            'model' => $registrationVerification
        ]);
    }

    public function changeEmail(Request $request, Response $response) {
        $emailChangeRequest = new EmailChangeRequest();

        if ($request->isPost()) {
            $emailChangeRequest->loadData($request->getBody());

            if ($emailChangeRequest->validate()) {
                if ($emailChangeRequest->send()) {
                    $newEmail = $emailChangeRequest->newEmail;

                    $this->setFlash('info', "Verification code sent to <strong>{$newEmail}</strong>. Please check your inbox (or spam folder).");

                    $newEmail = urlencode($newEmail);

                    $response->redirect("/profile/change/email/verify?newEmail={$newEmail}");
                } else {
                    $this->setFlash('error', 'Something went wrong while sending the verification code. Please try again later.');
                }
            }
        }

        return $this->render('profile_change_email', [
            'model' => $emailChangeRequest
        ]);
    }

    public function verifyNewEmail(Request $request, Response $response) {
        $emailChangeVerification = new EmailChangeVerification();

        if ($request->isGet()) {
            $newEmail = $request->getBody()['newEmail'] ?? '';

            if (empty($newEmail)) {
                $response->redirect('/profile/change/email');
            }

            $emailChangeVerification->newEmail = $newEmail;
            $emailChangeVerification->email = Application::$app->user->email;
        } else if ($request->isPost()) {
            $emailChangeVerification->loadData($request->getBody());

            if ($emailChangeVerification->validate(DbVerification::TYPE_EMAIL_CHANGE)) {
                if ($emailChangeVerification->confirm()) {
                    $this->setFlash('success', 'Your e-mail address has been changed.');

                    $response->redirect('/profile');
                } else {
                    $this->setFlash('error', 'Something went wrong while verifying your new e-mail address. Please try again later.');
                }
            }
        }

        return $this->render('profile_change_email_verify', [
            'model' => $emailChangeVerification
        ]);
    }

    public function changePassword(Request $request, Response $response) {
        $passwordChange = new PasswordChange();

        if ($request->isPost()) {
            $passwordChange->loadData($request->getBody());

            if ($passwordChange->validate()) {
                if ($passwordChange->apply()) {
                    $this->setFlash('success', 'Your password has been changed.');

                    $response->redirect('/profile');
                } else {
                    $passwordChange = new PasswordChange();

                    $this->setFlash('error', 'Something went wrong while changing your password. Please try again later.');
                }
            }
        }

        return $this->render('profile_change_password', [
            'model' => $passwordChange
        ]);
    }

    public function logout(Request $request, Response $response) {
        Application::$app->logOut();

        $this->setFlash('success', 'You have been logged out.');

        $response->redirect('/login');
    }

    public function deleteAccount(Request $request, Response $response) {
        $verification = new VerificationWithPassword();

        if ($request->isPost()) {
            $verification->loadData($request->getBody());

            if ($verification->validate()) {
                if ($verification->sendCode(DbVerification::TYPE_ACCOUNT_DELETION)) {
                    $email = $verification->email;

                    $this->setFlash('info', "Verification code sent to <strong>{$email}</strong>. Please check your inbox (or spam folder).");

                    $response->redirect('/profile/delete/confirm');
                } else {
                    $verification->password = '';

                    $this->setFlash('error', 'Something went wrong while sending the verification code. Please try again later.');
                }
            }
        }

        return $this->render('profile_delete', [
            'model' => $verification
        ]);
    }

    public function confirmDeleteAccount(Request $request, Response $response) {
        $accountDeleteConfirmation = new AccountDeleteConfirmation();

        if ($request->isGet()) {
            $accountDeleteConfirmation->email = Application::$app->user->email;
        } else if ($request->isPost()) {
            $accountDeleteConfirmation->loadData($request->getBody());

            if ($accountDeleteConfirmation->validate(DbVerification::TYPE_ACCOUNT_DELETION)) {
                if ($accountDeleteConfirmation->confirm()) {
                    Application::$app->logOut();

                    $this->setFlash('success', 'Your account has been deleted. Thank you for using MTGAR!');

                    $response->redirect('/login');
                } else {
                    $this->setFlash('error', 'Something went wrong while deleting your account. Please try again later.');
                }
            }
        }

        return $this->render('profile_delete_confirm', [
            'model' => $accountDeleteConfirmation
        ]);
    }

    private function redirectHomeIfLoggedIn(Response $response) {
        if (!Application::isGuest()) {
            $response->redirect('/');
        }
    }

}