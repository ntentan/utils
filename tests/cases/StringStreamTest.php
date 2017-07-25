<?php

/* 
 * The MIT License
 *
 * Copyright 2017 ekow.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace ntentan\utils\tests\cases;

use PHPUnit\Framework\TestCase;
use ntentan\utils\StringStream;

class StringStreamTest extends TestCase
{
    public function setup()
    {
        StringStream::register();
    }
    
    public function tearDown()
    {
        StringStream::unregister();
    }
    
    private function writeTest()
    {
        $first = fopen("string://test", "w");
        fputs($first, "This would be modified at some point");
        fclose($first);        
    }
    
    public function testOpenReadWrite()
    {
        $string = fopen("string://test", 'w');
        fputs($string, "Hello World");
        fclose($string);
        
        $read = fopen("string://test", "r");
        $output = fgets($read);
        fclose($read);
        
        $this->assertEquals("Hello World", $output);
    }
    
    public function testAppendAndSeek()
    {
        $this->writeTest();
        $readplus = fopen("string://test", "r+");
        $output = fgets($readplus);
        $this->assertEquals("This would be modified at some point", $output);
        fputs($readplus, " ... was it?");
        rewind($readplus);
        $output = fgets($readplus);
        $this->assertEquals("This would be modified at some point ... was it?", $output);
        fclose($readplus);
        
    }
    
    public function testSeekSet()
    {
        $this->writeTest();
        $readfile = fopen("string://test", "r");
        fseek($readfile, 5);
        $output = fgets($readfile);
        $this->assertEquals("would be modified at some point", $output);
    }
    
    public function testSeekSetPadding()
    {
        $this->writeTest();
        $writefile = fopen("string://test", "a");
        fseek($writefile, 40);
        fputs($writefile, "Padded");
        fclose($writefile);
        
        $readfile = fopen("string://test", "r");
        $output = fgets($readfile);
        $this->assertEquals("This would be modified at some point\0\0\0\0Padded", $output);
        fclose($readfile);
    }
}

