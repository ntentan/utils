<?php

namespace ntentan\utils\validator\validations;
use ntentan\utils\validator\Validation;

/**
 * Ensures a field contains a value.
 *
 * @package ntentan\utils\validator\validations
 */
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