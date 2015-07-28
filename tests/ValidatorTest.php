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
        $this->assertEquals(['name' => ['The name field is required']], $this->validator->getInvalidFields());

        $response = $this->validator->validate(
            array(
                'name' => ''
            )
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(['name' => ['The name field is required']], $this->validator->getInvalidFields());

        $response = $this->validator->validate(
            array(
                'name' => 'Jamie!'
            )
        );
        $this->assertEquals(true, $response);
        $this->assertEquals([], $this->validator->getInvalidFields());
    }

    public function testRequiredMessage() {
        $this->validator->setRules(
            [
                'name' => ['required' => ['message' => 'Please provide a name']]
            ]
        );
        $response = $this->validator->validate(
            array(
                'name' => ''
            )
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(['name' => ['Please provide a name']], $this->validator->getInvalidFields());
    }

}
