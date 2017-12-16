<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\Filesystem;

/**
 * A file on the filesystem
 *
 * @author ekow
 */
class File implements FileInterface
{

    /**
     * @var string
     */
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param string $destination
     * @throws \ntentan\utils\exceptions\FileNotFoundException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function moveTo(string $destination) : void
    {
        $this->copyTo($destination);
        $this->delete();
        $this->path = $destination;
    }

    /**
     * @return int
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     */
    public function getSize() : int
    {
        Filesystem::checkReadable($this->path);
        return filesize($this->path);
    }

    /**
     * @param string $destination
     * @throws \ntentan\utils\exceptions\FileNotFoundException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function copyTo(string $destination) : void
    {
        $destination = is_dir($destination) ? ("$destination/" . basename($this->path)) : $destination;
        Filesystem::checkWriteSafety(dirname($destination));
        copy($this->path, $destination);
    }

    /**
     * @return string
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     */
    public function getContents()
    {
        Filesystem::checkReadable($this->path);
        return file_get_contents($this->path);
    }

    /**
     * @param $contents
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
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
