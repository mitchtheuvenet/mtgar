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
    public string $label;
    public bool $required;
    public array $rules;
    public int $mb;

    public function __construct(Model $model, string $attribute, string $type, string $label, bool $required, int $mb = 3, array $rules = []) {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->type = $type;
        $this->label = $label;
        $this->required = $required;
        $this->mb = $mb;
        $this->rules = $rules;
    }

    public function __toString() {
        $hasError = $this->model->hasError($this->attribute);
        $hasRules = !empty($this->rules);

        $description = '';
        $validation = '';

        if ($hasError || $hasRules) {
            $description = "<div id=\"{$this->attribute}_desc\" class=\"";

            if ($hasError) {
                $firstError = $this->model->getFirstError();
    
                $description .= "invalid-feedback\">{$firstError}</div>";
            } else {
                $attrDescription = $this->rules['description'] ?? '';

                if (!empty($attrDescription)) {
                    $description .= "form-text\">{$attrDescription}</div>";
                } else {
                    $description = '';
                }
            }
        }

        if ($hasRules && isset($this->rules['htmlAttrs'])) {
            foreach ($this->rules['htmlAttrs'] as $key => $val) {
                $val = strval($val);

                $validation .= " {$key}=\"{$val}\"";
            }
        }

        return sprintf('
            <div class="mb-%s">
                <label for="%s" class="form-label">%s</label>
                <input type="%s" name="%s" id="%s" value="%s" class="form-control%s"%s%s%s>
                %s
            </div>
        ',
            strval($this->mb),
            $this->attribute,
            $this->label,
            $this->type,
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute},
            $hasError ? ' is-invalid' : '',
            $validation,
            !empty($description) ? " aria-describedby=\"{$this->attribute}_desc\"" : '',
            $this->required ? ' required' : '',
            $description
        );
    }

}