<?php

namespace ntentan\utils\validator\validations;
use ntentan\utils\validator\Validation;

class NumericValidation extends Validation
{
    public function run($field, $data)
    {
        $value = $this->getFieldValue($field, $data);        
        return $this->evaluateResult(
            $field,
            is_numeric($value) || $value === null, 
            "The {$field['name']} field must contain only numbers"
        );     
    }
}