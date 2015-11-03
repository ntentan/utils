<?php

namespace ntentan\utils\validator\validations;
use ntentan\utils\validator\Validation;

class LengthValidation extends Validation
{
    public function run($field, $data)
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