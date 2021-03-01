<?php

namespace app\core;

class Controller {

    protected string $layout = 'main';

    public function render($view, $params = []) {
        return Application::$app->router->renderView($view, $params);
    }

    public function getLayout() {
        return $this->layout;
    }

}