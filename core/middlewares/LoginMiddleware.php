<?php

namespace app\core\middlewares;

use app\core\Application;

class LoginMiddleware extends BaseMiddleware {

    public function execute() {
        if (!Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                Application::$app->response->redirect('/');
            }
        }
    }

}