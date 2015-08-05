<?php
namespace ntentan\utils\tests;

class UtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $value = null;
        
        \ntentan\utils\Utils::factory($value, 
            function(){
                return 3;
            }
        );
        
        $this->assertEquals(3, $value);
    }
    
    public function testFactory2()
    {
        $value = 5;
        
        \ntentan\utils\Utils::factory($value, 
            function(){
                return 3;
            }
        );
        
        $this->assertEquals(5, $value);
    }
    
}