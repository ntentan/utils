<?php
namespace ntentan\utils\tests;

use ntentan\utils\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase {

    private $validator;

    public function setUp()
    {
        $this->validator = Validator::getInstance();
    }

    public function testRequired() {
        $this->validator->setRules(
            array(
                'name' => ['required']
            )
        );
        $response = $this->validator->validate(
            array(
                'name' => null
            )
        );
        $this->assertEquals(false, $response);
    }

}
