<?php

namespace app\core\middlewares;

use app\core\Application;

use app\core\exceptions\NotFoundException;

class AdminMiddleware extends BaseMiddleware {

    public function execute() {
        if (!Application::isAdmin()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new NotFoundException();
            }
        }
    }

}