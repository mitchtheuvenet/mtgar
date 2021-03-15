<?php

namespace app\core\form;

use app\core\Model;

class Field {

    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_EMAIL = 'email';

    public Model $model;
    public string $attribute;
    public string $type;
    public int $mb;
    public string $description;

    public function __construct(Model $model, string $attribute, string $type, int $mb = 3, string $description = '') {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->type = $type;
        $this->mb = $mb;
        $this->description = $description;
    }

    public function __toString() {
        $hasError = $this->model->hasError($this->attribute);
        $hasDescription = !empty($this->description);

        $description = '';

        if ($hasError || $hasDescription) {
            $description = "<div id=\"{$this->attribute}_desc\" class=\"";

            if ($hasError) {
                $errorMsg = $this->model->getFirstError($this->attribute);
    
                $description .= "invalid-feedback\">{$errorMsg}</div>";
            } else {
                $attrDescription = $this->description;

                if (!empty($attrDescription)) {
                    $description .= "form-text\">{$attrDescription}</div>";
                } else {
                    $description = '';
                }
            }
        }

        return sprintf('
            <div class="mb-%s">
                <label for="%s" class="form-label">%s</label>
                <input type="%s" name="%s" id="%s" value="%s" class="form-control%s"%s>
                %s
            </div>
        ',
            strval($this->mb),
            $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->type,
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute},
            $hasError ? ' is-invalid' : '',
            !empty($description) ? " aria-describedby=\"{$this->attribute}_desc\"" : '',
            $description
        );
    }

}