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

    public function inputField(Model $model, string $attribute, int $mb = 3, string $description = '') {
        return new InputField($model, $attribute, $mb, $description);
    }

    public function textareaField(Model $model, string $attribute, int $mb = 3, string $description = '') {
        return new TextareaField($model, $attribute, $mb, $description);
    }

}