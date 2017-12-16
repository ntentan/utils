<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\exceptions\FileNotFoundException;
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
     * Used to perform copies and moves.
     *
     * @param string $operation
     * @param string $destination
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileAlreadyExistsException
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    private function directoryOperation(string $operation, string $destination):void
    {
        try {
            Filesystem::checkExists($destination);
        } catch (FileNotFoundException $e) {
            $destinationDir = new self($destination);
            $destinationDir->create();
        }

        $files = $this->getFiles();
        foreach ($files as $file) {
            $destinationPath = "$destination/" . basename($file);
            $file->$operation($destinationPath);
        }
    }

    /**
     * Recursively copies directory and its contents to destination.
     *
     * @param string $destination
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileAlreadyExistsException
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function copyTo(string $destination): void
    {
        $this->directoryOperation('copyTo', $destination);
    }

    /**
     * Recursively get the size of all contents in the directory.
     *
     * @return integer
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     */
    public function getSize() : int
    {
        $files = $this->getFiles();
        $size = 0;
        foreach($files as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    /**
     * Recursively move a directory and its contents to another location.
     *
     * @param string $destination
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileAlreadyExistsException
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function moveTo(string $destination) : void
    {
        $this->directoryOperation('moveTo', $destination);
        $this->delete();
        $this->path = $destination;
    }

    /**
     * Create the directory pointed to by path.
     *
     * @param int $permissions
     * @throws \ntentan\utils\exceptions\FileAlreadyExistsException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function create($permissions = 0755)
    {
        Filesystem::checkNotExists($this->path);
        Filesystem::checkWritable(dirname($this->path));
        mkdir($this->path, $permissions, true);
    }

    /**
     * Recursively delete the directory and all its contents.
     *
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     */
    public function delete() : void
    {
        $files = $this->getFiles();
        foreach($files as $file) {
            $file->delete();
        }
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
     * @throws \ntentan\utils\exceptions\FileNotFoundException
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     * @return array<FileInterface>
     */
    public function getFiles() : array
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
