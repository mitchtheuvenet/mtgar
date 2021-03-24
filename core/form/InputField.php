<?php

namespace app\core\form;

use app\core\Model;

class InputField extends BaseField {

    private const TYPE_TEXT = 'text';
    private const TYPE_PASSWORD = 'password';
    private const TYPE_EMAIL = 'email';

    public string $type;

    public function __construct(Model $model, string $attribute, int $mb = 3, string $description = '') {
        parent::__construct($model, $attribute, $mb);

        $this->type = self::TYPE_TEXT;
        $this->description = $description;
    }

    protected function renderInput(bool $hasError): string {
        return sprintf('
            <input type="%s" name="%s" id="%s" value="%s" class="form-control%s"%s>
        ',
            $this->type,
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute},
            $hasError ? ' is-invalid' : '',
            !empty($description) ? " aria-describedby=\"{$this->attribute}_desc\"" : ''
        );
    }

    public function passwordField() {
        $this->type = self::TYPE_PASSWORD;

        return $this;
    }

    public function emailField() {
        $this->type = self::TYPE_EMAIL;

        return $this;
    }

}