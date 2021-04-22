<?php

use app\core\Migration;

class m0001_add_timestamp extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            ALTER TABLE `users` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            ALTER TABLE `users` DROP COLUMN `created_at`;
        SQL);
    }

}