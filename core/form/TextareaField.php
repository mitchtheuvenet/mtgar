<?php

namespace app\core\form;

use app\core\Model;

class TextareaField extends BaseField {

    private const INPUT_HEIGHT = 3.5; // 3.5rem

    private float $height;

    public function __construct(Model $model, string $attribute, int $mb = 3, int $max = 500, int $heightMultiplier = 4) {
        parent::__construct($model, $attribute, $mb);

        $this->description = "0/{$max}";
        $this->height = self::INPUT_HEIGHT * $heightMultiplier;
    }

    protected function renderInput(bool $hasError): string {
        return sprintf('
            <textarea name="%s" id="%s" class="form-control%s" style="height:calc(%srem + 2px);min-height:calc(3.5rem + 2px);" placeholder="%s" aria-describedby="%s">%s</textarea>
        ',
            $this->attribute,
            $this->attribute,
            $hasError ? ' is-invalid' : '',
            strval($this->height),
            $this->placeholder(),
            "{$this->attribute}_desc",
            $this->model->{$this->attribute}
        );
    }

    protected function placeholder(): string {
        return 'sample text';
    }

}