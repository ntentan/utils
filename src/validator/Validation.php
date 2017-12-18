<?php

namespace ntentan\utils\validator;

abstract class Validation
{
    private $messages = [];

    /**
     * @param $field
     * @param $data
     * @return mixed
     */
    abstract public function run($field, $data);

    /**
     * Receives a boolean from the validation function and a message to be
     * displayed when the value is false. It also receives the options array
     * so it could override the standard message that the validation code
     * generates.
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
            if(is_array($field['name']))
            {
                foreach($field['name'] as $name) {
                    $this->messages[$name][] = isset($field['options']['message']) ? 
                        $field['options']['message'] : $message;
                }
            }
            else
            {
                $this->messages[$field['name']][] = isset($field['options']['message']) ? 
                    $field['options']['message'] : $message;
            }
            return false;
        }
    }

    protected function getFieldValue($field, $data)
    {
        $value = null;
        if(isset($data[$field['name']])){
            $value = $data[$field['name']];
        }
        return $value;
    }
    
    public function getMessages()
    {
        return $this->messages;
    }
}
