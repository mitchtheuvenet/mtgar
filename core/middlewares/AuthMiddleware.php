<?php

namespace app\core\middlewares;

use app\core\Application;

use app\core\exceptions\UnauthorizedException;

class AuthMiddleware extends BaseMiddleware {

    public function execute() {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new UnauthorizedException();
            }
        }
    }

}