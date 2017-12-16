<?php

namespace ntentan\utils\tests\cases;

use ntentan\utils\filesystem\UploadedFile;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use phpmock\phpunit\PHPMock;

/**
 * Class UploadFileTest
 *
 * @package ntentan\utils\tests\cases
 */
class UploadFileTest extends TestCase
{
    use PHPMock;

    public function setUp()
    {
        $isUploadedFile = $this->getFunctionMock('\ntentan\utils\filesystem\\', "is_uploaded_file");
        $isUploadedFile->expects($this->any())->will(
            $this->returnValueMap([
                ['/tmp/x12345', true],
                ['/tmp/x54321', false]
            ])
        );

    }

    public function getFileArray()
    {
        return [
            'name' => 'uploaded.jpeg',
            'type' => 'image/jpeg',
            'size' => 123456,
            'tmp_name' => '/tmp/x12345',
            'error' => 0
        ];
    }

    /**
     * @expectedException \ntentan\utils\exceptions\FilesystemException
     */
    public function testWrongFile()
    {
        $file = $this->getFileArray();
        $file['tmp_name'] = '/tmp/x54321';
        new UploadedFile($file);
    }

    private function setupMove($from, $to, $return = true)
    {
        vfsStream::setup('fs', null, ['uploads' => ['existing' => '']]);
        $moveUploadedFile = $this->getFunctionMock('\ntentan\utils\filesystem\\', 'move_uploaded_file');
        $moveUploadedFile->expects($this->any())->willReturnCallback(
            function($uploadedFile, $destination) use ($from, $to) {
                $this->assertEquals($from, $uploadedFile);
                $this->assertEquals($to, $destination);
                return true;
            }
        );
    }

    public function testMoveTo()
    {
        $this->setupMove('/tmp/x12345', vfsStream::url('fs/uploads/uploaded.jpeg'));
        $uploadedFile = new UploadedFile($this->getFileArray());
        $uploadedFile->moveTo(vfsStream::url('fs/uploads'));

    }

    public function testMoveToRename()
    {
        $this->setupMove('/tmp/x12345', vfsStream::url('fs/uploads/customname.jpeg'));
        $uploadedFile = new UploadedFile($this->getFileArray());
        $uploadedFile->moveTo(vfsStream::url('fs/uploads/customname.jpeg'));

    }

    /**
     * @expectedException \ntentan\utils\exceptions\FilesystemException
     */
    public function testMoveToFail()
    {
        $uploadedFile = new UploadedFile($this->getFileArray());
        // Will fail since move_uploaded_file is not mocked
        $uploadedFile->moveTo(vfsStream::url('fs/uploads/'));
    }
    public function testAttributes()
    {
        $uploadedFile = new UploadedFile($this->getFileArray());
        $this->assertEquals(123456, $uploadedFile->getSize());
        $this->assertEquals('uploaded.jpeg', $uploadedFile->getClientName());
        $this->assertEquals(0, $uploadedFile->getError());
        $this->assertEquals('image/jpeg', $uploadedFile->getType());
    }
}
