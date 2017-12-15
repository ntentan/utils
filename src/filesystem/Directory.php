<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\Filesystem;
use ntentan\utils\exceptions\FilesystemException;

/**
 * A directory on the filesystem.
 *
 * @package ntentan\utils\filesystem
 */
class Directory implements FileInterface
{

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
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function copyTo(string $destination): void
    {
        Filesystem::checkWritable($destination);
        if (!file_exists($destination)) {
            self::create($destination);
        }

        $files = glob("$this->path/*");
        foreach ($files as $file) {
            $newFile = "$destination/" . basename("$file");
            if (is_dir($file)) {
                self::create($newFile);
                (new Directory($file))->copyTo($newFile);
            } else {
                copy($file, $newFile);
            }
        }
    }

    /**
     * Get the size of all contents in the directory.
     *
     * @return integer
     */
    public function getSize() : integer
    {

    }

    public function moveTo(string $destination) : void
    {

    }

    public static function create($path, $permissions = 0755)
    {
        if (file_exists($path) && !is_dir($path)) {
            throw new FilesystemException("A file already exists in the location of [$path]");
        }
        if (!file_exists($path)) {
            mkdir($path, $permissions, true);
        }
        return new Directory($path);
    }

    public function delete() : void
    {

    }

    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileNotFoundException
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     */
    public function getContents()
    {
        Filesystem::checkExists($this->path);
        Filesystem::checkReadable($this->path);
        $contents = [];

        $files = scandir($this->path);
        foreach ($files as $file) {
            if($file != '.' && $file != '..') {
                $contents[] = Filesystem::get("$this->path/$file");
            }
        }
        return $contents;
    }

    public function __toString()
    {
        return $this->path;
    }
}
