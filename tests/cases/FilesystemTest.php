<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\filesystem\Directory;
use ntentan\utils\filesystem\File;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use ntentan\utils\Filesystem;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    use PHPMock;

    private $file;
    
    public function setUp()
    {
        vfsStream::setup('fs');
        $this->file = vfsStream::newFile('file');
        vfsStreamWrapper::getRoot()->addChild($this->file);
        vfsStreamWrapper::getRoot()->addChild(vfsStream::newDirectory('dir'));
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FileNotFoundException
     */
    public function testExistsException()
    {
        Filesystem::checkExists(vfsStream::url('fs/notexists'));
    }
    
    /**
     * @expectedException \ntentan\utils\exceptions\FileNotWriteableException
     */    
    public function testWritableException()
    {
        $this->file->chmod(0000);
        Filesystem::checkWritable(vfsStream::url('fs/file'));
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FileNotReadableException
     */
    public function testReadableException()
    {
        $this->file->chmod(0000);
        Filesystem::checkReadable(vfsStream::url('fs/file'));
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FileAlreadyExistsException
     */
    public function testNotExistsException()
    {
        Filesystem::checkNotExists(vfsStream::url('fs/file'));
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FileNotFoundException
     */
    public function testNonExistReadSafetyException()
    {
        Filesystem::checkReadSafety(vfsStream::url('fs/notfile'));
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FileNotReadableException
     */
    public function testNonReadableReadSafetyException()
    {
        $this->file->chmod(0000);
        Filesystem::checkReadSafety(vfsStream::url('fs/file'));
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FileNotFoundException
     */
    public function testNonExistWriteSafetyException()
    {
        Filesystem::checkWriteSafety(vfsStream::url('fs/notfile'));
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function testNonReadableWriteSafetyException()
    {
        $this->file->chmod(0000);
        Filesystem::checkWriteSafety(vfsStream::url('fs/file'));
    }

    public function testReadSafety()
    {
        $this->assertNull(Filesystem::checkReadSafety(vfsStream::url('fs/file')));
    }

    public function testWriteSafety()
    {
        $this->assertNull(Filesystem::checkWriteSafety(vfsStream::url('fs/file')));
    }

    public function testGet()
    {
        $file = Filesystem::get(vfsStream::url('fs/file'));
        $this->assertInstanceOf(File::class, $file);
        $dir = Filesystem::get(vfsStream::url('fs/dir'));
        $this->assertInstanceOf(Directory::class, $dir);
    }
}
