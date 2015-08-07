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
        
        $response = $this->validator->validate([]);
        $this->assertEquals(false, $response);
        $this->assertEquals(['name' => ['The name field is required']], $this->validator->getInvalidFields());
    }

    public function testRequiredMessage() {
        $this->validator->setRules(
            ['name' => ['required' => ['message' => 'Please provide a name']]]
        );
        $response = $this->validator->validate(
            array(
                'name' => ''
            )
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(['name' => ['Please provide a name']], $this->validator->getInvalidFields());
    }
    
    public function testRegexpValidation() {
        $this->validator->setRules(
            ['name' => ['regexp' => '/[A-Z]+/']]
        );
        
        $response = $this->validator->validate(
            ['name' => 'james']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['name' => ['The format of your input is invalid']], 
            $this->validator->getInvalidFields()
        );
        
        $response = $this->validator->validate(
            ['name' => 'JAMES']
        );
        $this->assertEquals(true, $response);
    }
    
    public function testNumericValidation() {
        $this->validator->setRules(
            ['age' => ['numeric']]
        );
        $response = $this->validator->validate(
            ['age' => '21a']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['age' => ['The age field must contain only numbers']], 
            $this->validator->getInvalidFields()
        );
        
        $response = $this->validator->validate(['age' => '21']);
        $this->assertEquals(true, $response);
    }
    
    public function testLengthValidation() {
        $this->validator->setRules(
            ['user_name' => ['length' => 8]]
        );
        $response = $this->validator->validate(
            ['user_name' => 'james.ainooson']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['user_name' => ['The length of the user_name field must be 8 characters or less']],
            $this->validator->getInvalidFields()
        );
        
        $response = $this->validator->validate(
            ['user_name' => 'james']
        );
        $this->assertEquals(true, $response);
    }
     
    public function testLenghtMinMaxValidation() {
        $this->validator->setRules(
            ['user_name' => ['length' => ['min' => 6, 'max' => 8]]]
        );
        $response = $this->validator->validate(
            ['user_name' => 'james.ainooson']
        );
        $this->assertEquals(false, $response);
        $this->assertEquals(
            ['user_name' => ['The length of the user_name field must be 6 characters or greater and 8 characters or lesser']],
            $this->validator->getInvalidFields()
        );
        
    }
}
