<?php

namespace app\core\form;

use app\core\Model;

class Form {

    public static function begin(string $action, string $method, string $id = '', bool $multipart = false): Form {
        echo sprintf(
            '<form action="%s" method="%s" onsubmit="disableSubmitBtn();"%s%s>',
            $action, $method, !empty($id) ? " id=\"{$id}\"" : '', $multipart ? ' enctype="multipart/form-data"' : ''
        );

        return new Form();
    }

    public static function end() {
        echo '</form>';
    }

    public static function script(): string {
        return <<<'JS'
            function disableSubmitBtn() {
                const button = document.getElementById('submitBtn');

                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:1em;height:1em;" role="status" aria-hidden="true"></span><span class="visually-hidden">Submitting...</span>';
            }
        JS;
    }

    public function inputField(Model $model, string $attribute, int $mb = 3, string $description = ''): InputField {
        return new InputField($model, $attribute, $mb, $description);
    }

    public function textareaField(Model $model, string $attribute, int $mb = 3, int $max = 500, int $heightMultiplier = 4): textareaField {
        return new TextareaField($model, $attribute, $mb, $max, $heightMultiplier);
    }

}