<?php

namespace app\core\form;

use app\core\Model;

class TextareaField extends BaseField {

    public function __construct(Model $model, string $attribute, int $mb = 3, string $description = '') {
        parent::__construct($model, $attribute, $mb);

        $this->description = $description;
    }

    protected function renderInput(bool $hasError): string {
        return sprintf('
            <textarea name="%s" id="%s" class="form-control%s" placeholder="%s"%s>%s</textarea>
        ',
            $this->attribute,
            $this->attribute,
            $hasError ? ' is-invalid' : '',
            $this->placeholder(),
            !empty($description) ? " aria-describedby=\"{$this->attribute}_desc\"" : '',
            $this->model->{$this->attribute}
        );
    }

    protected function placeholder(): string {
        return 'sample text';
    }

}