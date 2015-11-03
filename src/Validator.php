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
    private function callValidator($validator, $rule, $data)
    {
        return $this->$method($rule, $data);
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
    
    private function getFieldInfo($key, $value)
    {
        $name = null;
        $options = [];
        if(is_numeric($key) && is_string($value)){
            $name = $value;
        } else if (is_numeric($key) && is_array($value)) {
            $name = array_shift($value);
            $options = $value;
        } else if (is_string($key)) {
            $name = $key;
            $options = $value;
        }
        return ['name' => $name, 'options' => $options];
    }

    /**
     * Validate data according to validation rules that have been set into
     * this validator.
     * @param array $data The data to be validated
     */
    public function validate($data) {
        $passed = true;
        $this->invalidFields = [];
        foreach ($this->rules as $validator => $fields) {
            $validatorMethod = "validate$validator";
            foreach($fields as $key => $value) {
                $field = $this->getFieldInfo($key, $value);
                $passed &= $this->$validatorMethod($field, $data);
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
    protected function evaluateResult($field, $result, $message)
    {
        if ($result) {
            return true;
        } else {
            if(is_array($field['name']))
            {
                foreach($field['name'] as $name) {
                    $this->invalidFields[$name][] = 
                        isset($field['options']['message']) ? 
                        $field['options']['message'] : $message;
                }
            }
            else
            {
                $this->invalidFields[$field['name']][] = 
                    isset($field['options']['message']) ? $field['options']['message'] : $message;
            }
            return false;
        }
    }
    
    protected function getFieldValue($field, $data)
    {
        $value = null;
        if(isset($data[$field['name']])){
            $value = $data[$field['name']];
        }
        return $value;
    }

    /**
     * Validates data that is supposed to be required. This validation fails
     * only when the data supplied is null or is an empty string.
     * @param mixed $data
     * @param string $name
     * @param array $options
     * @return boolean  
     */
    protected function validateRequired($field, $data)
    {
        $value = $this->getFieldValue($field, $data);
        return $this->evaluateResult(
            $field, 
            $value !== null && $value !==  '', 
            "The {$field['name']} field is required"
        );
    }

    /**
     * Validates data using regular expressions.
     * @param mixed $data
     * @param string $name
     * @param array $options
     * @return boolean
     */
    protected function validateRegexp($field, $data)
    {
        $value = $this->getFieldValue($field, $data);
        return $this->evaluateResult(
            $field,
            preg_match_all(is_string($field['options']) ? 
                $field['options'] : $field['options'][0], $value), 
            "The format of your input is invalid"
        );
    }

    /**
     * Validates data that is supposed to be numeric.
     * @param string $data
     * @param string $name
     * @param array $options
     * @return boolean
     */
    protected function validateNumeric($field, $data)
    {
        $value = $this->getFieldValue($field, $data);        
        return $this->evaluateResult(
            $field,
            is_numeric($value) || $value === null, 
            "The {$field['name']} field must contain only numbers"
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
    protected function validateLength($field, $data)
    {
        $value = $this->getFieldValue($field, $data);        
        $length = strlen($value);
        if (is_array($field['options'])) {
            $hasMin = isset($field['options']['min']);
            $hasMax = isset($field['options']['max']);
            $max = $field['options']['max'];
            $min = $field['options']['min'];
            return $this->evaluateResult(
                $field,
                ($hasMax ? $length <= $max : true) &&
                ($hasMin ? $length >= $min : true), 
                $this->getLenghtValidationMessage($field['name'], $hasMin, $hasMax, $min, $max)
            );
        } else {
            return $this->evaluateResult(
                $field,
                $length <= $field['options'], 
                "The length of the {$field['name']} field must be {$field['options']} characters or less"
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
