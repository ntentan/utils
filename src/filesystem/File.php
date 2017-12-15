<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\Filesystem;

/**
 * Description of File
 *
 * @author ekow
 */
class File implements FileInterface
{

    /**
     * @var string
     */
    protected $path;
    private $isDirectory;

    public function __construct($path)
    {
        $this->path = $path;
        if (is_dir($path)) {
            $this->isDirectory = true;
        }
    }

    public function moveTo(string $destination) : void
    {
        self::copyTo($destination);
        unlink($this->path);
        $this->path = $destination;
    }

    public function getSize() : integer
    {
        return filesize($this->path);
    }

    public function copyTo(string $destination) : void
    {
        Filesystem::checkWriteSafety(dirname($destination));
        copy($this->path, $destination);
    }

    public function getContents()
    {
        Filesystem::checkExists($this->path);
        return file_get_contents($this->path);
    }

    public function putContents($contents)
    {
        if (file_exists($this->path)) {
            Filesystem::checkWritable($this->path);
        } else {
            Filesystem::checkWritable(dirname($this->path));
        }
        file_put_contents($this->path, $contents);
    }

    public function delete() : void
    {
        unlink($this->path);
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function __toString()
    {
        return $this->path;
    }

}
