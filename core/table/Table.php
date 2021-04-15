<?php

namespace app\core\table;

use app\core\Model;

class Table {

    public static function print(array $columns, string $dbModel, array $data, string $id = '') {
        echo sprintf('<table class="table table-hover"%s>', !empty($id) ? " id=\"{$id}\"" : '');
        echo sprintf('<thead><tr>%s</tr></thead>', self::thead($columns));
        echo sprintf('<tbody>%s</tbody>', self::tbody($columns, $dbModel, $data));
        echo '</table>';
    }

    private static function thead(array $columns): string {
        $thead = '';

        foreach ($columns as $c) {
            $thead .= "<th>{$c}</th>";
        }
        
        return $thead;
    }

    private static function tbody(array $columns, string $dbModel, array $data): string {
        $tbody = '';

        foreach ($data as $row) {
            $tbody .= '<tr>';

            foreach ($row as $colName => $colValue) {
                $val = $dbModel::getDisplayValue($colName, $colValue);

                if ($colName === $dbModel::primaryKey()) {
                    $tbody .= "<th scope=\"row\">{$val}</th>";

                    continue;
                }

                $tbody .= "<td>{$val}</td>";
            }

            $tbody .= '</tr>';
        }

        return $tbody;
    }

}