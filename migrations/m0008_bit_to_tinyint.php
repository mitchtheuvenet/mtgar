<?php

use app\core\Migration;

class m0008_bit_to_tinyint extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            ALTER TABLE `users` MODIFY `admin` TINYINT(1);
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `used` TINYINT(1);
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `expired` TINYINT(1);
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            ALTER TABLE `users` MODIFY `admin` BIT(1);
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `used` BIT(1);
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `expired` BIT(1);
        SQL);
    }

}