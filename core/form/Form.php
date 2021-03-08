<?php

namespace app\core\form;

use app\core\Model;

class Form {

    public static function begin($action, $method) {
        echo sprintf('<form action="%s" method="%s">', $action, $method);

        return new Form();
    }

    public static function end() {
        echo '</form>';
    }

    public function field(Model $model, string $attribute, string $type, string $label, bool $required, int $mb = 3, array $rules = []) {
        return new Field($model, $attribute, $type, $label, $required, $mb, $rules);
    }

}