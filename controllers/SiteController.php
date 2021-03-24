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
        $params = [
            'name' => 'Test'
        ];

        return $this->render('home', $params);
    }

    public function profile() {
        return $this->render('profile');
    }

    public function contact(Request $request, Response $response) {
        $contact = new Contact();

        if ($request->isPost()) {
            $contact->loadData($request->getBody());

            if ($contact->validate() && $contact->send()) {
                Application::$app->session->setFlash('success', 'Thank you for contacting us.');

                $response->redirect('/contact');
            }
        }

        return $this->render('contact', [
            'model' => $contact
        ]);
    }

}