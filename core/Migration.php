<?php

namespace app\core;

abstract class Migration {

    protected \PDO $pdo;

    public function __construct() {
        $this->pdo = Application::$app->db->pdo;
    }

    abstract public function up();

    abstract public function down();

}