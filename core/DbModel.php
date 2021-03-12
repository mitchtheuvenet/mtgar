<?php

namespace app\core;

abstract class DbModel extends Model {

    abstract public function tableName(): string;

    abstract public function columnNames(): array;

    public function save() {
        $tableName = $this->tableName();
        $columnNames = $this->columnNames();

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

    public static function prepare(string $sql) {
        return Application::$app->db->pdo->prepare($sql);
    }

}