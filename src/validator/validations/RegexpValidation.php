<?php

namespace ntentan\utils\validator\validations;
use ntentan\utils\validator\Validation;

class RegexpValidation extends Validation
{
    public function run($field, $data)
    {
        $value = $this->getFieldValue($field, $data);
        return $this->evaluateResult(
            $field,
            preg_match_all(is_string($field['options']) ? 
                $field['options'] : $field['options'][0], $value), 
            "The format of your input is invalid"
        );       
    }
}