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
            'report' => ['index.html' => ''],
            'src' => [
                'index.php' => '',
                'setup.php' => '',
                'assets' => []
            ],
            'composer.json' => '',
            'README.md' => ''
        ];
        vfsStream::setup('fs', null, $structure);
    }

    public function testIterator()
    {
        $dir = new Directory(vfsStream::url("fs"));
        $contents = $dir->getContents();
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
}