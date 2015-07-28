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

class Validator {

    private $rules = [];
    private $instance;
    private $validations;
    private $invalidFields = [];

    private function __construct() {
        $this->registerValidation(
            'required',
            function($data, $name) {
                if($data === null || $data === '') {
                    return "The {$name} field is required";
                }
                else {
                    return true;
                }
            }
        );
    }

    public function registerValidation($validation, $function)
    {
        $this->validations[$validation] = $function;
    }

    public static function getInstance() {
        return new Validator();
    }

    public function setRules($rules) {
        $this->rules = $rules;
    }

    private function callValidation($ruleIndex, $rule, &$messages, $data)
    {
        if(!is_numeric($ruleIndex)) {
            $method = $ruleIndex;
        }
        else {
            $method = $rule;
        }
        $response = $this->validations[$method]($data['data'], $data['field']);
        if($response !== true) {
            $messages[] = $response;
            return false;
        }
        else {
            return true;
        }

    }

    public function getInvalidFields()
    {
        return $this->invalidFields;
    }

    /**
     * Validate the data that was sen
     */
    public function validate($data) {
        $passed = true;
        foreach($this->rules as $field => $fieldRules) {
            $fieldMessages = [];
            foreach($fieldRules as $ruleIndex => $rule) {
                $this->callValidation(
                    $rule, $ruleIndex, $fieldMessages,
                    ['field' => $field, 'data' => $data[$field]]
                );
            }
            if(count($fieldMessages) > 0) {
                $this->invalidFields[$field] = $fieldMessages;
                $passed = false;
            }
        }
        return $passed;
    }
}
