<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\filesystem\File;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function setUp() : void
    {
        $structure = [
            'report' => ['index.html' => '<html></html>'],
            'src' => [
                'index.php' => '<?php echo "hello world";',
                'setup.php' => '<?php echo "setting up!";',
                'assets' => [
                    'script.js' => 'alert("Damn")',
                    'style.css' => 'body{margin:0}'
                ],
                'README.md' => 'Sources for my cool app'
            ],
            'composer.json' => '{}',
            'README.md' => 'My Cool App'
        ];
        vfsStream::setup('fs', null, $structure);
    }


    public function testMoveTo()
    {
        $file = new File(vfsStream::url('fs/composer.json'));
        $file->moveTo(vfsStream::url('fs/src'));
        $this->assertFileExists(vfsStream::url('fs/src/composer.json'));
        $this->assertFileDoesNotExist(vfsStream::url('fs/composer.json'));
    }

    public function testMoveToOverwrite()
    {
        $file = new File(vfsStream::url('fs/README.md'));
        $file->moveTo(vfsStream::url('fs/src'), File::OVERWRITE_NONE);
        $this->assertFileExists(vfsStream::url('fs/src/README.md'));
        $this->assertEquals('My Cool App', file_get_contents(vfsStream::url('fs/README.md')));
        $this->assertFileExists(vfsStream::url('fs/README.md'));
        $this->assertEquals('Sources for my cool app', file_get_contents(vfsStream::url('fs/src/README.md')));
    }

    public function testMoveToOvewriteOlder()
    {
        $file = new File(vfsStream::url('fs/README.md'));
        touch(vfsStream::url('fs/README.md'), time() + 1000);
        clearstatcache();
        $file->moveTo(vfsStream::url('fs/src'), File::OVERWRITE_OLDER);
        $this->assertFileExists(vfsStream::url('fs/src/README.md'));
        $this->assertFileDoesNotExist(vfsStream::url('fs/README.md'));
    }

    public function testGetSize()
    {
        $file = new File(vfsStream::url('fs/report/index.html'));
        $this->assertEquals(13, $file->getSize());
    }

    public function testCopyTo()
    {
        $file = new File(vfsStream::url('fs/composer.json'));
        $file->copyTo(vfsStream::url('fs/src'));
        $this->assertFileExists(vfsStream::url('fs/src/composer.json'));
        $this->assertFileExists(vfsStream::url('fs/composer.json'));

    }
    
    public function testCopyToOverwrite()
    {
        $file = new File(vfsStream::url('fs/README.md'));
        $file->copyTo(vfsStream::url('fs/src'), File::OVERWRITE_NONE);
        $this->assertFileExists(vfsStream::url('fs/src/README.md'));
        $this->assertEquals('My Cool App', file_get_contents(vfsStream::url('fs/README.md')));
        $this->assertFileExists(vfsStream::url('fs/README.md'));
        $this->assertEquals('Sources for my cool app', file_get_contents(vfsStream::url('fs/src/README.md')));
    }

    public function testCopyToOvewriteOlder()
    {
        $file = new File(vfsStream::url('fs/README.md'));
        touch(vfsStream::url('fs/README.md'), time() + 1000);
        clearstatcache();
        $file->copyTo(vfsStream::url('fs/src'), File::OVERWRITE_OLDER);
        $this->assertFileExists(vfsStream::url('fs/src/README.md'));
        $this->assertEquals('My Cool App', file_get_contents(vfsStream::url('fs/README.md')));
        $this->assertFileExists(vfsStream::url('fs/README.md'));
        $this->assertEquals('My Cool App', file_get_contents(vfsStream::url('fs/src/README.md')));    
        
    }
    
    public function testGetContents()
    {
        $file = new File(vfsStream::url('fs/report/index.html'));
        $this->assertEquals('<html></html>', $file->getContents());
    }

    public function testPutContents()
    {
        $file = new File(vfsStream::url('fs/report/index.html'));
        $file->putContents('<html><title>Hello World!</title></html>');
        $this->assertEquals('<html><title>Hello World!</title></html>', $file->getContents());
        $file = new File(vfsStream::url('fs/report/summary.html'));
        $file->putContents('<html><title>Summary</title></html>');
        $this->assertFileExists(vfsStream::url('fs/report/summary.html'));
        $this->assertEquals('<html><title>Summary</title></html>', $file->getContents());

    }

    public function testDelete()
    {
        $file = new File(vfsStream::url('fs/report/index.html'));
        $this->assertFileExists(vfsStream::url('fs/report/index.html'));
        $file->delete();
        $this->assertFileDoesNotExist(vfsStream::url('fs/report/index.html'));
    }

    public function testGetPath()
    {
        $file = new File(vfsStream::url('fs/report/index.html'));
        $this->assertEquals($file->getPath(), vfsStream::url('fs/report/index.html'));
    }
}
