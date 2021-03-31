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

    public function loadData($data) {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }

    abstract public function rules(): array;

    public function labels(): array {
        return [];
    }

    public function getLabel(string $attribute) {
        return $this->labels()[$attribute] ?? $attribute;
    }

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
                            $rule['match'] = $this->getLabel($rule['match']);

                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_PATTERN:
                        if (!preg_match($rule['pattern'], $value)) {
                            switch ($rule['pattern']) {
                                case '/[a-zA-Z0-9]{4,16}/':
                                    $rule['pattern'] = 'between 4&ndash;16 characters long, only consisting of alphabetical and/or numerical characters';
                            }

                            $this->addError($attribute, $ruleName, $rule);
                        }

                        break;
                    case self::RULE_UNIQUE:
                        $record = $this->getRecordByValue($rule['class'], $attribute, $value);

                        if (!empty($record)) {
                            $this->addError($attribute, $ruleName, ['field' => strtolower($this->getLabel($attribute))]);
                        }

                        break;
                    case self::RULE_EXISTS:
                        $record = $this->getRecordByValue($rule['class'], $attribute, $value);

                        if (empty($record)) {
                            $this->addError($attribute, $ruleName, ['field' => strtolower($this->getLabel($attribute))]);
                        }
                }
            }
        }

        return empty($this->errors);
    }

    public function hasError($attribute) {
        return isset($this->errors[$attribute]);
    }

    public function getFirstError($attribute) {
        return $this->errors[$attribute][0] ?? false;
    }

    // public function addErrorByMessage(string $attribute, string $message) {
    //     $this->errors[$attribute][] = $message;
    // }

    protected function getRecordByValue(string $class, string $attribute, $value) {
        $tableName = $class::tableName();

        $statement = Application::$app->db->prepare("SELECT `id` FROM `{$tableName}` WHERE `{$attribute}` = :val;");
        $statement->bindValue(':val', $value);

        $statement->execute();

        return $statement->fetchObject();
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
            self::RULE_REQUIRED => 'This field is required.',
            self::RULE_EMAIL => 'This field must contain a valid e-mail address.',
            self::RULE_MIN => 'This field\'s length must be at least {min} characters.',
            self::RULE_MAX => 'This field\'s length must not exceed {max} characters.',
            self::RULE_MATCH => 'This field must match with \'{match}\'.',
            self::RULE_PATTERN => 'This field must match the specified pattern: {pattern}.',
            self::RULE_UNIQUE => 'This {field} is already in use.',
            self::RULE_EXISTS => 'This {field} is not registered at our website.'
        ];
    }

}