<?php

namespace ntentan\utils\tests\lib;

class FakeValidator extends \ntentan\utils\Validator
{
    public function __construct()
    {
        $this->registerValidation('fake', '\ntentan\utils\tests\lib\FakeValidation', ['some_data']);
    }
}
