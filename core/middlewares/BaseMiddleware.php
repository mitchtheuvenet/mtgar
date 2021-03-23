<?php

namespace app\core\middlewares;

abstract class BaseMiddleware {

    public array $actions;

    abstract public function execute();

}