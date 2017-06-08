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
    private $validations = [];
    private $validationRegister = [
        'required' => '\ntentan\utils\validator\validations\RequiredValidation',
        'length' => '\ntentan\utils\validator\validations\LengthValidation',
        'numeric' => '\ntentan\utils\validator\validations\NumericValidation',
        'regexp' => '\ntentan\utils\validator\validations\RegexpValidation',
    ];
    private $validationData = [];

    /**
     * Returns a new instance of the Validator
     * @return \ntentan\utils\Validator
     */
    public static function getInstance() {
        return new Validator();
    }

    private function getValidation($name) {
        if (!isset($this->validations[$name])) {
            if (isset($this->validationRegister[$name])) {
                $class = $this->validationRegister[$name];
            } else {
                throw new exceptions\ValidatorException("Validator [$name] not found");
            }
            $this->validations[$name] = new $class(isset($this->validationData[$name]) ? $this->validationData[$name] : null);
        }
        return $this->validations[$name];
    }

    protected function registerValidation($name, $class, $data = null) {
        $this->validationRegister[$name] = $class;
        $this->validationData[$name] = $data;
    }

    /**
     * Set the validation rules
     * @param array $rules
     */
    public function setRules($rules) {
        $this->rules = $rules;
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
     * Build a uniform field info array for various types of validations
     * 
     * @param mixed $key
     * @param mixed $value
     * @return array
     */
    private function getFieldInfo($key, $value) {
        $name = null;
        $options = [];
        if (is_numeric($key) && is_string($value)) {
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
        foreach ($this->rules as $validation => $fields) {
            foreach ($fields as $key => $value) {
                $field = $this->getFieldInfo($key, $value);
                $validationInstance = $this->getValidation($validation);
                $passed &= $validationInstance->run($field, $data);
                $this->invalidFields = array_merge_recursive(
                        $this->invalidFields, $validationInstance->getMessages()
                );
            }
        }
        return $passed;
    }

}
