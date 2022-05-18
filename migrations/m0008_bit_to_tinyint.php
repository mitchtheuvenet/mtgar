<?php

use app\core\Migration;

class m0008_bit_to_tinyint extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            ALTER TABLE `users` MODIFY `admin` TINYINT(1) NOT NULL;
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `used` TINYINT(1) NOT NULL;
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `expired` TINYINT(1) NOT NULL;
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            ALTER TABLE `users` MODIFY `admin` BIT(1) NOT NULL;
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `used` BIT(1) NOT NULL;
        SQL);
        self::exec(<<<'SQL'
            ALTER TABLE `verifications` MODIFY `expired` BIT(1) NOT NULL;
        SQL);
    }

}