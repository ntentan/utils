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
 * Base validator class for validating data in associative arrays.
 */
class Validator
{

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
    public static function getInstance()
    {
        return new Validator();
    }

    /**
     * Set the validation rules
     * @param array $rules
     */
    public function setRules($rules)
    {
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
    private function callValidation($ruleIndex, $rule, &$messages, $data)
    {
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
    public function getInvalidFields()
    {
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
                $value = isset($data[$field]) ? $data[$field] : null;
                $this->callValidation(
                        $ruleIndex, $rule, $fieldMessages, ['field' => $field, 'data' => $value]
                );
            }
            if (count($fieldMessages) > 0) {
                $this->invalidFields[$field] = $fieldMessages;
                $passed = false;
            }
        }
        return $passed;
    }

    /**
     * Receives a boolean from the validation function and a message to be
     * displayed when the value is false. It also receives the options array
     * so it could override the standard message that the validation code
     * generates.
     *
     * @param boolean $result
     * @param string $message
     * @param array $options
     * @return boolean
     */
    protected function evaluateResult($result, $message, $options)
    {
        if ($result) {
            return true;
        } else {
            $this->message = isset($options['message']) ? $options['message'] : $message;
            return false;
        }
    }

    /**
     * Validates data that is supposed to be required. This validation fails
     * only when the data supplied is null or is an empty string.
     * @param mixed $data
     * @param string $name
     * @param array $options
     * @return boolean  
     */
    protected function validateRequired($data, $name, $options)
    {
        return $this->evaluateResult(
            $data !== null && $data !== '', "The {$name} field is required", $options
        );
    }

    /**
     * Validates data using regular expressions.
     * @param mixed $data
     * @param string $name
     * @param array $options
     * @return boolean
     */
    protected function validateRegexp($data, $name, $options)
    {
        return $this->evaluateResult(
            preg_match_all(is_string($options) ? $options : $options[0], $data), "The format of your input is invalid", $options
        );
    }

    /**
     * Validates data that is supposed to be numeric.
     * @param string $data
     * @param string $name
     * @param array $options
     * @return boolean
     */
    protected function validateNumeric($data, $name, $options)
    {
        return $this->evaluateResult(
            is_numeric($data) || $data === null, "The {$name} field must contain only numbers", $options
        );
    }

    /**
     * Validates the size of data to be stored.
     * 
     * @param string $data
     * @param string $name
     * @param array $options
     * @return boolean
     */
    protected function validateLength($data, $name, $options)
    {
        $length = strlen($data);
        if (is_array($options)) {
            $hasMin = isset($options['min']);
            $hasMax = isset($options['max']);
            $max = $options['max'];
            $min = $options['min'];
            $this->evaluateResult(
                ($hasMax ? $length <= $max : true) &&
                ($hasMin ? $length >= $min : true), $this->getLenghtValidationMessage($name, $hasMin, $hasMax, $min, $max), $options
            );
        } else {
            return $this->evaluateResult(
                $length <= $options, "The length of the {$name} field must be {$options} characters or less", $options
            );
        }
    }

    private function getLenghtValidationMessage($name, $hasMin, $hasMax, $min, $max)
    {
        return "The length of the {$name} field must be" .
            ($hasMin ? " $min characters or greater" : '') .
            ($hasMax && $hasMin ? " and" : '') .
            ($hasMax ? " $max characters or lesser" : '');
    }

}
