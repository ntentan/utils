<?php
use ntentan\utils\CamelCase;

class CamelCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testCamelCase()
    {
        $this->assertEquals('camelCase', CamelCase::camelize("camel_case"));
        $this->assertEquals('CamelCase', CamelCase::ucamelize("camel_case"));
        $this->assertEquals('camelCaseJoe', CamelCase::camelize("camel_case_joe"));
        $this->assertEquals('CamelCaseJoe', CamelCase::ucamelize("camel_case_joe"));
        
        $this->assertEquals('camelCase', CamelCase::camelize("camel.case", "."));
        $this->assertEquals('CamelCase', CamelCase::ucamelize("camel.case", "."));
        $this->assertEquals('camelCaseJoe', CamelCase::camelize("camel.case.joe", "."));
        $this->assertEquals('CamelCaseJoe', CamelCase::ucamelize("camel.case.joe", "."));
        
        $this->assertEquals('camelCaseJoe', CamelCase::camelize("camel.case_joe", array(".", "_")));
        $this->assertEquals('CamelCaseJoe', CamelCase::ucamelize("camel_case.joe", array(".", "_")));
    }
    
    public function testUnCamelCase()
    {
        $this->assertEquals('camel_case', CamelCase::deCamelize("camelCase", '_'));
        $this->assertEquals('camel.case.joe', CamelCase::deCamelize("CamelCaseJoe", '.'));
    }
}