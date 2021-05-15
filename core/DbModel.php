<?php

namespace app\core;

abstract class DbModel extends Model {

    public const BYPASS_QUERY_LIMIT = -1;

    abstract public static function tableName(): string;

    abstract public static function columnNames(): array;

    abstract public static function primaryKey(): string;

    public static function queryLimit(): int {
        return 10;
    }

    public static function getDisplayValue(string $column, $value): string {
        return strval($value);
    }

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
            // TODO: add exception handling

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
            WHERE `id` = :id;
        ");

        foreach ($columnNames as $attribute) {
            $statement->bindValue(":{$attribute}", $this->{$attribute});
        }

        $statement->bindValue(':id', $this->id);

        try {
            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public function delete(): bool {
        if (!isset($this->id)) {
            return false;
        }

        $tableName = static::tableName();

        $statement = self::prepare("
            DELETE FROM `{$tableName}`
            WHERE `id` = :id;
        ");

        $statement->bindValue(':id', $this->id);

        try {
            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public static function findObject(array $where, array $columns = []) {
        $tableName = static::tableName();
        $columnNames = array_keys($where);

        $selectSql = !empty($columns) ? implode(', ', array_map(fn($col) => "`{$col}`", $columns)) : '*';

        $whereSql = [];

        foreach ($columnNames as $col) {
            $operator = $where[$col]['operator'] ?? '=';

            $whereSql[] = "`{$col}` {$operator} :{$col}";
        }

        $whereSql = implode(' AND ', $whereSql);

        $statement = self::prepare("
            SELECT {$selectSql}
            FROM `{$tableName}`
            WHERE {$whereSql}
            LIMIT 1;
        ");

        foreach ($where as $col => $args) {
            $statement->bindValue(":{$col}", $args['value']);
        }

        try {
            $statement->execute();

            return $statement->fetchObject(static::class);
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public static function findArray(array $where = [], array $columns = [], int $index = 0, string $orderBy = '', bool $ascending = true) {
        $tableName = static::tableName();

        $selectSql = !empty($columns) ? implode(', ', array_map(fn($col) => "`{$col}`", $columns)) : '*';

        $whereSql = '';

        if (!empty($where)) {
            $columnNames = array_keys($where);

            $whereArray = [];

            foreach ($columnNames as $col) {
                $operator = $where[$col]['operator'] ?? '=';

                $whereArray[] = "`{$col}` {$operator} :{$col}";
            }

            $whereSql = 'WHERE ' . implode(' AND ', $whereArray);
        }

        $limitSql = '';

        if ($index !== self::BYPASS_QUERY_LIMIT) {
            $limit = static::queryLimit();

            $startRow = !empty($index) ? strval($index * $limit) . ', ' : '';

            $limitSql = 'LIMIT ' . $startRow . $limit;
        }

        if (empty($orderBy)) {
            $orderBy = static::primaryKey();
        }

        $order = $ascending ? 'ASC' : 'DESC';

        $statement = self::prepare("
            SELECT {$selectSql}
            FROM `{$tableName}`
            {$whereSql}
            ORDER BY `{$orderBy}` {$order}
            {$limitSql};
        ");

        foreach ($where as $col => $args) {
            $statement->bindValue(":{$col}", $args['value']);
        }

        try {
            $statement->execute();

            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public static function countRows(array $where = []) {
        $tableName = static::tableName();

        $whereSql = '';
        $limitSql = '';

        if (!empty($where)) {
            $columnNames = array_keys($where);

            $whereArray = [];

            foreach ($columnNames as $col) {
                $operator = $where[$col]['operator'] ?? '=';
    
                $whereArray[] = "`{$col}` {$operator} :{$col}";
            }
    
            $whereSql = 'WHERE ' . implode(' AND ', $whereArray);
        }

        $statement = self::prepare("
            SELECT COUNT(*)
            FROM `{$tableName}`
            {$whereSql};
        ");

        if (!empty($where)) {
            foreach ($where as $col => $args) {
                $statement->bindValue(":{$col}", $args['value']);
            }
        }

        try {
            $statement->execute();

            return $statement->fetchColumn();
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public static function lastInsertId(): string {
        return Application::$app->db->pdo->lastInsertId();
    }

    protected static function prepare(string $sql): \PDOStatement {
        return Application::$app->db->pdo->prepare($sql);
    }

}