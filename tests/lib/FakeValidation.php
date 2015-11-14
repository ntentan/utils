<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\utils\tests\lib;

/**
 * Description of FakeValidation
 *
 * @author ekow
 */
class FakeValidation extends \ntentan\utils\validator\Validation
{
    public function run($field, $data)
    {
        return $this->evaluateResult(
            $field, 
            $data['username'] === strtolower($data['name']), 
            'Username must be lowercase form of name'
        );
    }
}
