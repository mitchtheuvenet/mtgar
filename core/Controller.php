<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller {

    protected const LAYOUT_MAIN = 'main';
    protected const LAYOUT_AUTH = 'auth';

    protected string $layout = self::LAYOUT_MAIN;
    protected array $middlewares = [];

    public string $action = '';

    public function render($view, $params = []) {
        return Application::$app->router->renderView($view, $params);
    }

    public function getLayout() {
        return $this->layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware) {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares() {
        return $this->middlewares;
    }

}