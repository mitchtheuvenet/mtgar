<?php

namespace app\core;

abstract class Model {

    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_MATCH = 'match';
    protected const RULE_PATTERN = 'pattern';
    protected const RULE_UNIQUE = 'unique';
    protected const RULE_EXISTS = 'exists';

    public array $errors = [];

    public function loadData(array $data) {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }

    public function rules(): array {
        return [];
    }

    public function labels(): array {
        return [];
    }

    public function getLabel(string $attribute): string {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public function validate(): bool {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute} ?? '';

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
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($attribute, $ruleName);
                        }

                        break;
                    case self::RULE_MIN:
                        if (!empty($value) && strlen($value) < $rule['min']) {
                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_MAX:
                        if (!empty($value) && strlen($value) > $rule['max']) {
                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_MATCH:
                        if (!empty($value) && $value !== $this->{$rule['match']}) {
                            $rule['match'] = strtolower($this->getLabel($rule['match']));

                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_PATTERN:
                        if (!empty($value) && !preg_match($rule['pattern'], $value)) {
                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_UNIQUE:
                        if (!empty($value)) {
                            $record = $rule['class']::findObject([$attribute => ['value' => $value]]);

                            if (!empty($record)) {
                                $this->addError($attribute, $ruleName, ['field' => strtolower($this->getLabel($attribute))]);
                            }
                        }

                        break;
                    case self::RULE_EXISTS:
                        if (!empty($value)) {
                            $record = $rule['class']::findObject([$attribute => ['value' => $value]]);

                            if (empty($record)) {
                                $this->addError($attribute, $ruleName, ['field' => strtolower($this->getLabel($attribute))]);
                            }
                        }
                }
            }
        }

        return empty($this->errors);
    }

    public function hasError($attribute): bool {
        return isset($this->errors[$attribute]);
    }

    public function getFirstError($attribute): string {
        return $this->errors[$attribute][0] ?? 'Unknown error.';
    }

    protected function addError(string $attribute, string $rule, $params = []) {
        $message = self::errorMessages()[$rule] ?? '';

        foreach ($params as $key => $val) {
            $message = str_replace("{{$key}}", $val, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    protected function addCustomError(string $attribute, string $message) {
        $this->errors[$attribute][] = $message;
    }

    private static function errorMessages(): array {
        return [
            self::RULE_REQUIRED => 'This field is required.',
            self::RULE_EMAIL => 'This field must contain a valid e-mail address.',
            self::RULE_MIN => 'This field\'s length must be at least {min} characters.',
            self::RULE_MAX => 'This field\'s length must not exceed {max} characters.',
            self::RULE_MATCH => 'This field must match with \'{match}\'.',
            self::RULE_PATTERN => 'This field must match the specified pattern: {description}.',
            self::RULE_UNIQUE => 'This {field} is already in use.',
            self::RULE_EXISTS => 'This {field} is not registered at our website.'
        ];
    }

}