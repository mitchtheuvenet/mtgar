<?php

namespace app\core;

abstract class Model {

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_PATTERN = 'pattern';

    public array $errors = [];

    public function loadData($data) {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }

    abstract public function rules(): array;

    public function validate() {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};

            foreach ($rules as $rule) {
                $ruleName = $rule;

                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                switch ($ruleName) {
                    case self::RULE_REQUIRED:
                        if (empty($value)) {
                            $this->addError($attribute, $ruleName);
                        }

                        break;
                    case self::RULE_EMAIL:
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($attribute, $ruleName);
                        }

                        break;
                    case self::RULE_MIN:
                        if (strlen($value) < $rule['min']) {
                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_MAX:
                        if (strlen($value) > $rule['max']) {
                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_MATCH:
                        if ($value !== $this->{$rule['match']}) {
                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_PATTERN:
                        if (!preg_match($rule['pattern'], $value)) {
                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                }
            }
        }

        return empty($this->errors);
    }

    protected function addError(string $attribute, string $rule, $params = []) {
        $msg = $this->errorMessages()[$rule] ?? '';

        foreach ($params as $key => $val) {
            $msg = str_replace("{{$key}}", $val, $msg);
        }

        $this->errors[$attribute][] = $msg;
    }

    protected function errorMessages() {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must contain a valid e-mail address',
            self::RULE_MIN => 'This field\'s length must be at least {min}',
            self::RULE_MAX => 'This field\'s length must not exceed {max}',
            self::RULE_MATCH => 'This field must match the value of field \'{match}\'',
            self::RULE_PATTERN => 'This field must match the specified pattern: {pattern}'
        ];
    }

    public function hasError($attribute) {
        return isset($this->errors[$attribute]);
    }

    public function getFirstError($attribute) {
        return $this->errors[$attribute][0] ?? false;
    }

}