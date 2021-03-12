<?php

use app\core\Migration;

class m0001_add_timestamp extends Migration {

    public function up() {
        $this->exec("ALTER TABLE `users` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;");
    }

    public function down() {
        $this->exec("ALTER TABLE `users` DROP COLUMN `created_at`;");
    }

}