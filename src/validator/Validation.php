<?php

namespace ntentan\utils\validator;

/**
 * An abstract class to be used as the base for validations.
 *
 * @package ntentan\utils\validator
 */
abstract class Validation
{
    /**
     * Holds messages generated during validation.
     *
     * @var array
     */
    private $messages = [];

    /**
     * @param $field
     * @param $data
     * @return mixed
     */
    abstract public function run($field, $data);

    /**
     * Utility function that evaluates the result of a validation test and prepares validation messages.
     * Receives a boolean from the validation function and a message to be displayed when the value is false. It also
     * receives the options array so it could override the standard message that the validation code generates.
     *
     * @param array $field
     * @param boolean $result
     * @param string $message
     * @return boolean
     */
    protected function evaluateResult($field, $result, $message)
    {
        $this->messages = [];
        if ($result) {
            return true;
        } else {
            if (is_array($field['name'])) {
                foreach ($field['name'] as $name) {
                    $this->messages[$name][] = $field['options']['message'] ?? $message;
                }
            } else {
                $this->messages[$field['name']][] = $field['options']['message'] ?? $message;
            }
            return false;
        }
    }

    /**
     * Extracts the value of the field description from the data passed.
     *
     * @param array $field
     * @param array $data
     * @return mixed
     */
    protected function getFieldValue($field, $data)
    {
        $value = null;
        if (isset($data[$field['name']])) {
            $value = $data[$field['name']];
        }
        return $value;
    }

    /**
     * Get the validation messages generated.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
