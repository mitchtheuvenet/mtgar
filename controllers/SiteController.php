<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class SiteController extends Controller {

    public function home() {
        $params = [
            'name' => 'Test'
        ];

        return $this->render('home', $params);
    }

    public function test() {
        return $this->render('test');
    }

    public function handleTest(Request $request) {
        $body = $request->getBody();

        echo '<pre>';
        var_dump($body);
        echo '</pre>';
        exit;
    }

}