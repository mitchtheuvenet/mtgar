<?php

namespace app\core\form;

use app\core\Model;

class Form {

    public static function begin($action, $method, $id = '') {
        echo sprintf('<form action="%s" method="%s"%s>', $action, $method, !empty($id) ? " id=\"{$id}\"" : '');

        return new Form();
    }

    public static function end() {
        echo '</form>';
    }

    public function inputField(Model $model, string $attribute, int $mb = 3, string $description = '') {
        return new InputField($model, $attribute, $mb, $description);
    }

    public function textareaField(Model $model, string $attribute, int $mb = 3, int $max = 500, int $heightMultiplier = 4) {
        return new TextareaField($model, $attribute, $mb, $max, $heightMultiplier);
    }

}