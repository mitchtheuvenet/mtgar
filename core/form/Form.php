<?php

namespace app\core\form;

use app\core\Model;

class Form {

    public static function begin($action, $method, $id = '') {
        echo sprintf('<form action="%s" method="%s" onsubmit="disableSubmitBtn();"%s>', $action, $method, !empty($id) ? " id=\"{$id}\"" : '');

        return new Form();
    }

    public static function end() {
        echo '</form>';
    }

    public static function script() {
        return <<<'EOT'
            function disableSubmitBtn() {
                const button = document.getElementById('submitBtn');

                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:1em;height:1em;" role="status" aria-hidden="true"></span><span class="visually-hidden">Submitting...</span>';
            }
        EOT;
    }

    public function inputField(Model $model, string $attribute, int $mb = 3, string $description = '') {
        return new InputField($model, $attribute, $mb, $description);
    }

    public function textareaField(Model $model, string $attribute, int $mb = 3, int $max = 500, int $heightMultiplier = 4) {
        return new TextareaField($model, $attribute, $mb, $max, $heightMultiplier);
    }

}