<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\exceptions\FileNotFoundException;
use ntentan\utils\exceptions\FileNotReadableException;
use ntentan\utils\exceptions\FileNotWriteableException;
use ntentan\utils\Filesystem;

/**
 * A file on the filesystem
 *
 * @author ekow
 */
class File implements FileInterface
{
    const OVERWRITE_ALL = 0;
    const OVERWRITE_NONE = 1;
    const OVERWRITE_OLDER = 2;

    /**
     * Path to file
     * @var string
     */
    protected $path;

    /**
     * File constructor.
     *
     * @param string $path Path to file. Does
     */
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    private function skipOperation($destination, $overwrite)
    {
        return file_exists($destination) && ($overwrite & self::OVERWRITE_NONE || ($overwrite & self::OVERWRITE_OLDER && filemtime($destination) >= filemtime($this->path)));
    }

    /**
     * Move file to a new location.
     *
     * @param string $destination New destination of the file.
     * @param int $overwrite Set some overwrite flags on the operation.
     * @throws FileNotFoundException
     * @throws FileNotWriteableException
     */
    public function moveTo(string $destination, int $overwrite = self::OVERWRITE_ALL) : void
    {
        if($this->skipOperation($destination, $overwrite)) {
            return;
        }
        $this->copyTo($destination);
        $this->delete();
        $this->path = $destination;
    }

    /**
     * Get the size of the file.
     *
     * @return int
     * @throws FileNotReadableException
     */
    public function getSize() : int
    {
        Filesystem::checkReadable($this->path);
        return filesize($this->path);
    }

    /**
     * Copy a file to a new destination.
     *
     * @param string $destination
     * @param string $overwrite
     * @throws FileNotFoundException
     * @throws FileNotWriteableException
     */
    public function copyTo(string $destination, int $overwrite = self::OVERWRITE_ALL) : void
    {
        if($this->skipOperation($destination, $overwrite)) {
            return;
        }
        $destination = is_dir($destination) ? ("$destination/" . basename($this->path)) : $destination;
        Filesystem::checkWriteSafety(dirname($destination));
        copy($this->path, $destination);
    }

    /**
     * @return string
     * @throws FileNotReadableException
     */
    public function getContents()
    {
        Filesystem::checkReadable($this->path);
        return file_get_contents($this->path);
    }

    /**
     * @param $contents
     * @throws FileNotWriteableException
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

    public function deleteIfExists(): void
    {
        if (file_exists($this->path)) {
            $this->delete();
        }
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
