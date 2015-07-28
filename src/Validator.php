<?php

/*
 * Ntentan Framework
 * Copyright (c) 2008-2015 James Ekow Abaka Ainooson
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */

namespace ntentan\utils;

/**
 * Base validator class for validating data found in arrays.
 */
class Validator {

    /**
     * An array which represnts the validation rules
     * @var array
     */
    private $rules = [];
    
    /**
     * An array which holds the validation errors found after the last
     * validation was run.
     * @var array
     */
    private $invalidFields = [];
    
    /**
     * A temporal storage for validation messages
     * @var string
     */
    private $message;

    /**
     * Returns a new instance of the Validator
     * @return \ntentan\utils\Validator
     */
    public static function getInstance() {
        return new Validator();
    }

    /**
     * Set the validation rules
     * @param array $rules
     */
    public function setRules($rules) {
        $this->rules = $rules;
    }

    /**
     * Call the method for a given validation.
     * 
     * @param string|integer $ruleIndex
     * @param string $rule
     * @param array $messages
     * @param mixed $data
     * @return boolean
     */
    private function callValidation($ruleIndex, $rule, &$messages, $data) {
        if (!is_numeric($ruleIndex)) {
            $method = $ruleIndex;
            $options = $rule;
        } else {
            $method = $rule;
            $options = null;
        }

        $method = "validate$method";

        $response = $this->$method($data['data'], $data['field'], $options);
        if ($response) {
            return true;
        } else {
            $messages[] = $this->message;
            return false;
        }
    }

    /**
     * Returns an associative array of all the errors that occured after the
     * last validation was run.
     * @return array
     */
    public function getInvalidFields() {
        return $this->invalidFields;
    }

    /**
     * Validate data according to validation rules that have been set into
     * this validator.
     * @param array $data The data to be validated
     */
    public function validate($data) {
        $passed = true;
        $this->invalidFields = [];
        foreach ($this->rules as $field => $fieldRules) {
            $fieldMessages = [];
            foreach ($fieldRules as $ruleIndex => $rule) {
                $this->callValidation(
                        $ruleIndex, $rule, $fieldMessages, ['field' => $field, 'data' => $data[$field]]
                );
            }
            if (count($fieldMessages) > 0) {
                $this->invalidFields[$field] = $fieldMessages;
                $passed = false;
            }
        }
        return $passed;
    }

    protected function evaluateResult($result, $message, $options) {
        if ($result) {
            return true;
        } else {
            $this->message = isset($options['message']) ? $options['message'] : $message;
            return false;
        }
    }

    protected function validateRequired($data, $name, $options) {
        return $this->evaluateResult(
            $data !== null && $data !== '', 
            "The {$name} field is required", $options
        );
    }

    protected function validateRegexp($data, $name, $options) {
        return $this->evaluateResult(
            preg_match_all(is_string($options) ? $options : $options[0], $data), 
            "The {$name} field is required", $options
        );
    }

    protected function validateNumeric($data, $name, $options) {
        return $this->evaluateResult(
            is_numeric($data), "The {$name} field is not numeric", $options
        );
    }
    
    protected function validateLength($data, $name, $options) {
        return $this->evaluateResult(
            strlen($data) > $options,
            "The {$name} field cannot have its lenght greater than {$options}"
        );
    }

}
