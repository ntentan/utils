<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\filesystem\Directory;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class DirectoryTest extends TestCase
{
    public function setUp()
    {
        $structure = [
            'report' => ['index.html' => '<html></html>'],
            'src' => [
                'index.php' => '<?php echo "hello world";',
                'setup.php' => '<?php echo "setting up!";',
                'assets' => [
                    'script.js' => 'alert("Damn")',
                    'style.css' => 'body{margin:0}'
                ]
            ],
            'composer.json' => '{}',
            'README.md' => 'My Cool App'
        ];
        vfsStream::setup('fs', null, $structure);
    }

    public function testGetContents()
    {
        $dir = new Directory(vfsStream::url("fs"));
        $contents = $dir->getFiles();
        $this->assertEquals(4, count($contents));
        $expected = [
            vfsStream::url('fs/report'),
            vfsStream::url('fs/src'),
            vfsStream::url('fs/composer.json'),
            vfsStream::url('fs/README.md')
        ];
        $actual = [];
        foreach($contents as $content) {
            $actual[]=(string)$content;
        }
        foreach($expected as $file) {
            $this->assertContains($file, $contents);
        }
    }

    public function testCopyto()
    {
        $dir = new Directory(vfsStream::url('fs/src'));
        $dir->copyTo(vfsStream::url('fs/scr2'));
        foreach($this->getMovedAndCopiedFiles() as $file) {
            $this->assertFileExists($file);
        }
    }

    public function testMoveTo()
    {
        $dir = new Directory(vfsStream::url('fs/src'));
        $dir->moveTo(vfsStream::url('fs/scr2'));
        foreach($this->getMovedAndCopiedFiles() as $file) {
            $this->assertFileExists($file);
        }
        $this->assertFileNotExists(vfsStream::url('fs/src'));
        $this->assertEquals(vfsStream::url('fs/scr2'), $dir->getPath());
    }

    public function testDelete()
    {
        $dir = new Directory(vfsStream::url('fs/src'));
        $dir->delete();
        $this->assertFileNotExists(vfsStream::url('fs/src'));
        $this->assertFileExists(vfsStream::url('fs'));
        $this->assertFileExists(vfsStream::url('fs/composer.json'));
    }

    public function testGetSize()
    {
        $dir = new Directory(vfsStream::url('fs'));
        $this->assertEquals(103, $dir->getSize());
        $dir = new Directory(vfsStream::url('fs/report'));
        $this->assertEquals(13, $dir->getSize());
    }

    public function getMovedAndCopiedFiles()
    {
        return [
            vfsStream::url('fs/scr2/assets'),
            vfsStream::url('fs/scr2/assets/script.js'),
            vfsStream::url('fs/scr2/assets/style.css'),
            vfsStream::url('fs/scr2/index.php'),
            vfsStream::url('fs/scr2/setup.php')
        ];
    }
}