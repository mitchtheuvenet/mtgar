<?php

namespace app\core;

abstract class DbModel extends Model {

    abstract public static function tableName(): string;

    abstract public static function columnNames(): array;

    abstract public static function primaryKey(): string;

    public function save(): bool {
        $tableName = static::tableName();
        $columnNames = static::columnNames();

        $columns = array_map(fn($col) => "`{$col}`", $columnNames);
        $values = array_map(fn($col) => ":{$col}", $columnNames);

        $statement = self::prepare("
            INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ")
            VALUES (" . implode(', ', $values) . ");
        ");

        foreach ($columnNames as $attribute) {
            $statement->bindValue(":{$attribute}", $this->{$attribute});
        }

        try {
            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            // add exception handling

            return false;
        }
    }

    public function update(array $columns = []): bool {
        if (!isset($this->id)) {
            return false;
        }

        $tableName = static::tableName();
        $columnNames = !empty($columns) ? $columns : static::columnNames();

        $columnValues = array_map(fn($col) => "`{$col}` = :{$col}", $columnNames);

        $statement = self::prepare("
            UPDATE `{$tableName}`
            SET " . implode(', ', $columnValues) . "
            WHERE `id` = {$this->id};
        ");

        foreach ($columnNames as $attribute) {
            $statement->bindValue(":{$attribute}", $this->{$attribute});
        }

        try {
            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            // add exception handling

            return false;
        }
    }

    public static function findObject(array $where) {
        $tableName = static::tableName();
        $columnNames = array_keys($where);

        $whereSql = implode(' AND ', array_map(fn($col) => "`{$col}` = :{$col}", $columnNames));

        $statement = self::prepare("
            SELECT *
            FROM `{$tableName}`
            WHERE {$whereSql}
            LIMIT 1;
        ");

        foreach ($where as $col => $val) {
            $statement->bindValue(":{$col}", $val);
        }

        try {
            $statement->execute();

            return $statement->fetchObject(static::class);
        } catch (\PDOException $e) {
            // add exception handling

            return false;
        }
    }

    public static function prepare(string $sql): \PDOStatement {
        return Application::$app->db->pdo->prepare($sql);
    }

}