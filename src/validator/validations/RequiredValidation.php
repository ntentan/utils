<?php

namespace ntentan\utils\validator\validations;
use ntentan\utils\validator\Validation;

class RequiredValidation extends Validation
{
    public function run($field, $data)
    {
        $value = $this->getFieldValue($field, $data);
        return $this->evaluateResult(
            $field, 
            $value !== null && $value !==  '', 
            "The {$field['name']} field is required"
        );        
    }
}