<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\exceptions\FileAlreadyExistsException;
use ntentan\utils\exceptions\FileNotFoundException;
use ntentan\utils\exceptions\FileNotReadableException;
use ntentan\utils\exceptions\FileNotWriteableException;
use ntentan\utils\Filesystem;
use ntentan\utils\exceptions\FilesystemException;

/**
 * Represents a directory from the filesystem.
 *
 * @package ntentan\utils\filesystem
 * 
 */
class Directory implements FileInterface
{
    const OVERWRITE_MERGE = 4;
    const OVERWRITE_SKIP = 8;

    /**
     * Full path to the directory.
     *
     * @var string
     */
    private $path;

    /**
     * Create a new instance with a path.
     *
     * @param string $path Optional path pointed to by new instance. Path does not have to exist.
     */
    public function __construct(string $path = null)
    {
        $this->path = $path;
    }

    /**
     * Recursively copies directory and its contents to destination.
     *
     * @param string $destination
     * @param int $overwrite
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws FilesystemException
     */
    public function copyTo(string $destination, int $overwrite = self::OVERWRITE_MERGE): void
    {
        if(file_exists($destination) && ($overwrite & self::OVERWRITE_SKIP)) {
            return;
        }
        Filesystem::directory($destination)->createIfNotExists(true);
        $this->getFiles()->copyTo($destination, $overwrite);
    }

    /**
     * Recursively get the size of all contents in the directory.
     *
     * @return integer
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws FileNotReadableException
     */
    public function getSize() : int
    {
        return $this->getFiles()->getSize();
    }

    /**
     * Recursively move a directory and its contents to another location.
     *
     * @param string $destination
     * @param int $overwrite
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws FilesystemException
     */
    public function moveTo(string $destination, int $overwrite = self::OVERWRITE_MERGE) : void
    {
        if(file_exists($destination) && ($overwrite & self::OVERWRITE_SKIP)) {
            return;
        }
        Filesystem::directory($destination)->createIfNotExists(true);
        $this->getFiles()->moveTo($destination, $overwrite);
        $this->delete();
        $this->path = $destination;
    }

    /**
     * Create the directory pointed to by path.
     *
     * @param int $permissions
     * @return Directory
     * @throws FileAlreadyExistsException
     * @throws FileNotWriteableException
     */
    public function create($recursive=false, $permissions = 0755)
    {
        Filesystem::checkNotExists($this->path);
        $parent = $this->path;
        if($recursive) {
            $segments = explode(DIRECTORY_SEPARATOR, $this->path);
            $parent = "";
            foreach($segments as $segment) {
                $parent .= $segment . DIRECTORY_SEPARATOR;
                if(!is_dir($parent)) {
                    break;
                }
            }
        }
        $parent = dirname($parent);
        Filesystem::checkWritable($parent == "" ? '.' : $parent);
        mkdir($this->path, $permissions, true);

        return $this;
    }

    public function createIfNotExists($recursive=false, $permissions = 0755)
    {
        try {
            $this->create($recursive, $permissions);
        } catch (FileAlreadyExistsException $exception) {
            // Do nothing
        }
        return $this;
    }

    /**
     * Recursively delete the directory and all its contents.
     *
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws FileNotReadableException
     */
    public function delete() : void
    {
        $this->getFiles()->delete();
        rmdir($this->path);
    }

    /**
     * Get the path of the directory.
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get the files in the directory.
     *
     * @throws FilesystemException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @return FileCollection | array<string>
     */
    public function getFiles($recursive=false, $returnStrings=false)
    {
        Filesystem::checkExists($this->path);
        Filesystem::checkReadable($this->path);
        $paths = [];

        $files = scandir($this->path);
        foreach ($files as $file) {
            if($file == '.' || $file == '..') continue;
            $path = "$this->path/$file";
            if(is_dir($path) && $recursive) {
                $paths = array_merge($paths, Filesystem::directory($path)->getFiles(true, true));
            }
            $paths[] = $path;
        }
        return $returnStrings ? $paths : new FileCollection($paths);
    }

    public function __toString()
    {
        return $this->path;
    }
}
