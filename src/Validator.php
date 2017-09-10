<?php

/*
 * Ntentan Framework
 * Copyright (c) 2008-2017 James Ekow Abaka Ainooson
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
 * Validator class allows general validation rules to be defined that could be used to validate data in arbitrary
 * associative arrays. Rules are defined in an array where different validation types point the the fields in a given
 * associative array that will be validated. ntentan\utils ships with a set of default validations for lenght (or size),
 * regular expressions, numbers and presence (fields that are required).
 */
class Validator
{

    /**
     * An array which represnts the validation rules.
     * 
     * @var array
     */
    private $rules = [];

    /**
     * An array which holds the validation errors found after the last
     * validation was run.
     * 
     * @var array
     */
    private $invalidFields = [];

    /**
     * An array of loaded validations for this validator.
     * 
     * @var array 
     */
    private $validations = [];

    /**
     * A register of validations that could be used by this validator.
     * 
     * @var array
     */
    private $validationRegister = [
        'required' => '\ntentan\utils\validator\validations\RequiredValidation',
        'length' => '\ntentan\utils\validator\validations\LengthValidation',
        'numeric' => '\ntentan\utils\validator\validations\NumericValidation',
        'regexp' => '\ntentan\utils\validator\validations\RegexpValidation',
    ];
    private $validationData = [];

    /**
     * A DI container for initializing the validations.
     *
     * @var Container
     */
    private $container;

    /**
     * Returns a new instance of the Validator.
     * 
     * @return \ntentan\utils\Validator
     */
    public static function getInstance(): self
    {
        return new self();
    }

    /**
     * Get an instance of a validation class.
     * 
     * @param string $name
     * @return validator\Validation
     * @throws exceptions\ValidatorException
     */
    private function getValidation(string $name): validator\Validation
    {
        if (!isset($this->validations[$name])) {
            if (isset($this->validationRegister[$name])) {
                $class = $this->validationRegister[$name];
            } else {
                throw new exceptions\ValidatorException("Validator [$name] not found");
            }

            $params = isset($this->validationData[$name]) ? $this->validationData[$name] : null;
            if($this->container) {
                $this->validations[$name] = $this->container->resolve($class, ['params' => $params]);
            } else {
                $this->validations[$name] = new $class($params);
            }
        }
        return $this->validations[$name];
    }

    /**
     * Register a validation type.
     * 
     * @param string $name The name of the validation to be used in validation descriptions.
     * @param string $class The name of the validation class to load.
     * @param mixed $data Any extra validation data that would be necessary for the validation.
     */
    protected function registerValidation(string $name, string $class, $data = null) : void
    {
        $this->validationRegister[$name] = $class;
        $this->validationData[$name] = $data;
    }

    /**
     * Set the validation rules.
     * 
     * @param array $rules
     */
    public function setRules(array $rules) : void
    {
        $this->rules = $rules;
    }

    /**
     * Returns an associative array of all the errors that occurred the last time the validator was run.
     * 
     * @return array
     */
    public function getInvalidFields(): array
    {
        return $this->invalidFields;
    }

    /**
     * Build a uniform field info array for various types of validations.
     * 
     * @param mixed $key
     * @param mixed $value
     * @return array
     */
    private function getFieldInfo($key, $value): array
    {
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
    
    public function getRules() : array
    {
        return $this->rules;
    }

    /**
     * Validate data according to validation rules that have been set into
     * this validator.
     * 
     * @param array $data The data to be validated
     * @return bool
     */
    public function validate(array $data) : bool
    {
        $passed = true;
        $this->invalidFields = [];
        $rules = $this->getRules();
        foreach ($rules as $validation => $fields) {
            foreach ($fields as $key => $value) {
                $field = $this->getFieldInfo($key, $value);
                $validationInstance = $this->getValidation($validation);
                $validationStatus = $validationInstance->run($field, $data);
                $passed = $passed && $validationStatus;
                $this->invalidFields = array_merge_recursive(
                        $this->invalidFields, $validationInstance->getMessages()
                );
            }
        }
        return $passed;
    }

}
