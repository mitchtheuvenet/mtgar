<?php

namespace app\core;

class Controller {

    protected const LAYOUT_MAIN = 'main';
    protected const LAYOUT_AUTH = 'auth';

    protected string $layout = self::LAYOUT_MAIN;

    public function render($view, $params = []) {
        return Application::$app->router->renderView($view, $params);
    }

    public function getLayout() {
        return $this->layout;
    }

}