<?php

namespace app\core;

abstract class DbModel extends Model {

    abstract public static function tableName(): string;

    abstract public static function columnNames(): array;

    abstract public static function primaryKey(): string;

    public function save() {
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

        $statement->execute();

        return true;
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

        $statement->execute();

        return $statement->fetchObject(static::class);
    }

    public static function prepare(string $sql) {
        return Application::$app->db->pdo->prepare($sql);
    }

}