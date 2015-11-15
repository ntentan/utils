<?php

namespace ntentan\utils\tests\cases;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use ntentan\utils\Filesystem;

class FilesystemTest extends \PHPUnit_Framework_TestCase
{
    private $file;
    
    public function setUp()
    {
        vfsStream::setup('fs');
        $this->file = vfsStream::newFile('file');
        vfsStreamWrapper::getRoot()->addChild($this->file);
    }
    
    public function testExists()
    {
        $this->assertEquals(true, Filesystem::exists(vfsStream::url('fs/file')));
        $this->assertEquals(false, Filesystem::exists(vfsStream::url('fs/notexists')));
    }
    
    public function testWritable()
    {
        $this->assertEquals(true, Filesystem::isWritable(vfsStream::url('fs/file')));        
        $this->file->chmod(0000);
        $this->assertEquals(false, Filesystem::isWritable(vfsStream::url('fs/file')));
    }
    
    /**
     * @expectedException \ntentan\utils\exceptions\FilesystemException
     */
    public function testExistsException()
    {
        $this->assertEquals(true, Filesystem::checkExists(vfsStream::url('fs/file')));
        Filesystem::checkExists(vfsStream::url('fs/nofile'));
    }
    
    /**
     * @expectedException \ntentan\utils\exceptions\FilesystemException
     */
    public function testWriteableException()
    {
        $this->assertEquals(true, Filesystem::checkWritable(vfsStream::url('fs/file')));
        $this->file->chmod(0000);
        Filesystem::checkWritable(vfsStream::url('fs/file'));
    }
    
}
