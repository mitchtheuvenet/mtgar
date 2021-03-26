<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\middlewares\SiteMiddleware;

use app\models\Contact;

class SiteController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new SiteMiddleware(['profile']));

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
                    $this->setFlash('success', 'Thank you for contacting us. We will get in touch with you soon&trade;.');

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

}