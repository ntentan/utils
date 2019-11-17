<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\exceptions\FileAlreadyExistsException;
use ntentan\utils\exceptions\FileNotFoundException;
use ntentan\utils\exceptions\FileNotReadableException;
use ntentan\utils\exceptions\FileNotWriteableException;
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
    
    public function setUp() : void
    {
        vfsStream::setup('fs');
        $this->file = vfsStream::newFile('file');
        vfsStreamWrapper::getRoot()->addChild($this->file);
        vfsStreamWrapper::getRoot()->addChild(vfsStream::newDirectory('dir'));
    }

    public function testExistsException()
    {
        $this->expectException(FileNotFoundException::class);
        Filesystem::checkExists(vfsStream::url('fs/notexists'));
    }
    
    public function testWritableException()
    {
        $this->expectException(FileNotWriteableException::class);
        $this->file->chmod(0000);
        Filesystem::checkWritable(vfsStream::url('fs/file'));
    }

    public function testReadableException()
    {
        $this->expectException(FileNotReadableException::class);
        $this->file->chmod(0000);
        Filesystem::checkReadable(vfsStream::url('fs/file'));
    }

    public function testNotExistsException()
    {
        $this->expectException(FileAlreadyExistsException::class);
        Filesystem::checkNotExists(vfsStream::url('fs/file'));
    }

    public function testNonExistReadSafetyException()
    {
        $this->expectException(FileNotFoundException::class);
        Filesystem::checkReadSafety(vfsStream::url('fs/notfile'));
    }

    public function testNonReadableReadSafetyException()
    {
        $this->expectException(FileNotReadableException::class);
        $this->file->chmod(0000);
        Filesystem::checkReadSafety(vfsStream::url('fs/file'));
    }

    public function testNonExistWriteSafetyException()
    {
        $this->expectException(FileNotFoundException::class);
        Filesystem::checkWriteSafety(vfsStream::url('fs/notfile'));
    }

    public function testNonReadableWriteSafetyException()
    {
        $this->expectException(FileNotWriteableException::class);
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
