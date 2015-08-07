<?php
namespace ntentan\utils\tests;

use ntentan\utils\Text;

class CamelCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testCamelCase()
    {
        $this->assertEquals('camelCase', Text::camelize("camel_case"));
        $this->assertEquals('CamelCase', Text::ucamelize("camel_case"));
        $this->assertEquals('camelCaseJoe', Text::camelize("camel_case_joe"));
        $this->assertEquals('CamelCaseJoe', Text::ucamelize("camel_case_joe"));
        
        $this->assertEquals('camelCase', Text::camelize("camel.case", "."));
        $this->assertEquals('CamelCase', Text::ucamelize("camel.case", "."));
        $this->assertEquals('camelCaseJoe', Text::camelize("camel.case.joe", "."));
        $this->assertEquals('CamelCaseJoe', Text::ucamelize("camel.case.joe", "."));
        
        $this->assertEquals('camelCaseJoe', Text::camelize("camel.case_joe", array(".", "_")));
        $this->assertEquals('CamelCaseJoe', Text::ucamelize("camel_case.joe", array(".", "_")));
    }
    
    public function testUnCamelCase()
    {
        $this->assertEquals('camel_case', Text::deCamelize("camelCase", '_'));
        $this->assertEquals('camel.case.joe', Text::deCamelize("CamelCaseJoe", '.'));
    }
}