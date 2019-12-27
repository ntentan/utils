<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\exceptions\ValidatorException;
use ntentan\utils\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorContainerTest extends TestCase
{

    private $validator;

    public function setUp() : void
    {
        $this->validator = new Validator();
    }

    public function testRequired()
    {
        $this->validator->setRules(
            array(
                'required' => ['name']
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

        $response = $this->validator->validate([]);
        $this->assertEquals(false, $response);
        $this->assertEquals(['name' => ['The name field is required']], $this->validator->getInvalidFields());
    }

    public function testRequiredMessage()
    {
        $this->validator->setRules(
            ['required' => ['name' => ['message' => 'Please provide a name']]]
        );
        $response = $this->validator->validate(
            array(
                'name' => ''
            )
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(['name' => ['Please provide a name']], $this->validator->getInvalidFields());
    }

    public function testRegexpValidation()
    {
        $this->validator->setRules(
            ['regexp' => ['name' => '/[A-Z]+/']]
        );

        $response = $this->validator->validate(
            ['name' => 'james']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['name' => ['The format of your input is invalid']], $this->validator->getInvalidFields()
        );

        $response = $this->validator->validate(
            ['name' => 'JAMES']
        );
        $this->assertEquals(true, $response);
    }

    public function testNumericValidation()
    {
        $this->validator->setRules(
            ['numeric' => ['age']]
        );
        $response = $this->validator->validate(
            ['age' => '21a']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['age' => ['The age field must contain only numbers']], $this->validator->getInvalidFields()
        );

        $response = $this->validator->validate(['age' => '21']);
        $this->assertEquals(true, $response);
    }

    public function testLengthValidation()
    {
        $this->validator->setRules(
            ['length' => ['user_name' => 8]]
        );
        $response = $this->validator->validate(
            ['user_name' => 'james.ainooson']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['user_name' => ['The length of the user_name field must be 8 characters or less']], $this->validator->getInvalidFields()
        );

        $response = $this->validator->validate(
            ['user_name' => 'james']
        );
        $this->assertEquals(true, $response);
    }

    public function testLenghtMinMaxValidation()
    {
        $this->validator->setRules(
            ['length' => ['user_name' => ['min' => 6, 'max' => 8]]]
        );
        $response = $this->validator->validate(
            ['user_name' => 'james.ainooson']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['user_name' => ['The length of the user_name field must be 6 characters or greater and 8 characters or lesser']], $this->validator->getInvalidFields()
        );
    }

    public function testValidatorException()
    {
        $this->expectException(ValidatorException::class);
        $this->validator->setRules(
            ['fake_validator' => ['name']]
        );

        $this->validator->validate(
            ['name' => 'not important']
        );
    }

    public function testCustomValidation()
    {
        $customValidation = new \ntentan\utils\tests\lib\FakeValidator();
        $customValidation->setRules(
            ['fake' => [[['username', 'name']]]]
        );
        $response = $customValidation->validate(
            ['name' => 'JAMIE', 'username' => 'jamie']
        );
        $this->assertEquals(true, $response);
        $response = $customValidation->validate(
            ['name' => 'jamie', 'username' => 'JAMIE']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            [
                'name' => ['Username must be lowercase form of name'],
                'username' => ['Username must be lowercase form of name']
            ],
            $customValidation->getInvalidFields()
        );
    }
}
