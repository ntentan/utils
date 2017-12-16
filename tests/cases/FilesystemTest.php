<?php

namespace ntentan\utils\tests\cases;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use ntentan\utils\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    private $file;
    
    public function setUp()
    {
        vfsStream::setup('fs');
        $this->file = vfsStream::newFile('file');
        vfsStreamWrapper::getRoot()->addChild($this->file);
    }
    
    /**
     * @expectedException \ntentan\utils\exceptions\FileNotFoundException
     */
    public function testExists()
    {
        Filesystem::checkExists(vfsStream::url('fs/file'));
        Filesystem::checkExists(vfsStream::url('fs/notexists'));
    }
    
    /**
     * @expectedException \ntentan\utils\exceptions\FileNotWriteableException
     */    
    public function testWritable()
    {
        Filesystem::checkWritable(vfsStream::url('fs/file'));
        $this->file->chmod(0000);
        Filesystem::checkWritable(vfsStream::url('fs/file'));
    }
    
    /**
     * @expectedException \ntentan\utils\exceptions\FilesystemException
     */
    public function testExistsException()
    {
        Filesystem::checkExists(vfsStream::url('fs/file'));
        Filesystem::checkExists(vfsStream::url('fs/nofile'));
    }
    
    /**
     * @expectedException \ntentan\utils\exceptions\FilesystemException
     */
    public function testWriteableException()
    {
        Filesystem::checkWritable(vfsStream::url('fs/file'));
        $this->file->chmod(0000);
        Filesystem::checkWritable(vfsStream::url('fs/file'));
    }
    
}
