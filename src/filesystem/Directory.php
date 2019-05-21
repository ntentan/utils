<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\exceptions\FileAlreadyExistsException;
use ntentan\utils\exceptions\FileNotFoundException;
use ntentan\utils\exceptions\FileNotReadableException;
use ntentan\utils\exceptions\FileNotWriteableException;
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
     * @throws FileAlreadyExistsException
     * @throws FileNotReadableException
     * @throws FileNotWriteableException
     */
    private function directoryOperation(string $operation, string $destination):void
    {
        try {
            Filesystem::checkExists($destination);
            $destination = $destination . DIRECTORY_SEPARATOR . basename($this);
        } catch (FileNotFoundException $e) {
            Filesystem::directory($destination)->create(true);
        }

        foreach ($this->getFiles() as $file) {
            $file->$operation($destination . DIRECTORY_SEPARATOR . basename($file));
        }
    }

    /**
     * Recursively copies directory and its contents to destination.
     *
     * @param string $destination
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws FileAlreadyExistsException
     * @throws FileNotReadableException
     * @throws FileNotWriteableException
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
     * @throws FileNotFoundException
     * @throws FilesystemException
     * @throws FileAlreadyExistsException
     * @throws FileNotReadableException
     * @throws FileNotWriteableException
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
     * @throws FileAlreadyExistsException
     * @throws FileNotWriteableException
     */
    public function create($recursive=false, $permissions = 0755)
    {
        Filesystem::checkNotExists($this->path);
        if($recursive) {
            $parsedPath = parse_url($this->path);
            $segments = explode(DIRECTORY_SEPARATOR, $parsedPath['path']);
            $scheme = $parsedPath['scheme'] ?? '';
            $host = $parsedPath['host'] ?? '';
            array_unshift($segments, $scheme . ':' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . $host);
            $accumulator = "";
            $parent = "";
            foreach($segments as $segment) {
                $accumulator .= $segment . DIRECTORY_SEPARATOR;
                if(is_dir($accumulator)) {
                    $parent = $accumulator;
                } else {
                    break;
                }
            }
        } else {
            $parent = dirname($this->path);
        }
        Filesystem::checkWritable($parent == "" ? "." : $parent);
        mkdir($this->path, $permissions, true);
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
     * @return array<FileInterface>
     */
    public function getFiles() : FileCollection
    {
        Filesystem::checkExists($this->path);
        Filesystem::checkReadable($this->path);
        $contents = [];

        $files = scandir($this->path);
        foreach ($files as $file) {
            if($file != '.' && $file != '..') {
                $contents[] = "$this->path/$file";
            }
        }
        return new FileCollection($contents);
    }

    public function __toString()
    {
        return $this->path;
    }
}
