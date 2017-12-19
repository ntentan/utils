<?php

namespace ntentan\utils\validator\validations;
use ntentan\utils\validator\Validation;

/**
 * Ensures that a field contains only numbers.
 *
 * @package ntentan\utils\validator\validations
 */
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