<?php

namespace app\core\middlewares;

abstract class BaseMiddleware {

    public array $actions;

    public function __construct(array $actions = []) {
        $this->actions = $actions;
    }

    abstract public function execute();

}