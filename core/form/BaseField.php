<?php

namespace app\core\form;

use app\core\Model;

abstract class BaseField {

    public Model $model;
    public string $attribute;
    public int $mb;
    
    public string $description = '';

    public function __construct(Model $model, string $attribute, int $mb) {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->mb = $mb;
    }

    abstract protected function renderInput(bool $hasError): string;

    private function description(bool $hasError, bool $hasDescription) {
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

        return $description;
    }

    public function __toString() {
        $hasError = $this->model->hasError($this->attribute);
        $hasDescription = !empty($this->description);

        $description = $this->description($hasError, $hasDescription);

        return sprintf('
            <div class="mb-%s">
                <label for="%s" class="form-label">%s</label>
                %s
                %s
            </div>
        ',
            strval($this->mb),
            $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->renderInput($hasError),
            $description
        );
    }

}