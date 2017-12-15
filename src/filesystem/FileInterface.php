<?php
namespace ntentan\utils\filesystem;

/**
 * An interface for objects that represent access to a file resource.
 *
 * @package ntentan\utils\filesystem
 */
interface FileInterface
{
    /**
     * Move the file to a new location.
     *
     * @param string $destination New destination of file.
     * @return void
     */
    public function moveTo(string $destination) : void;

    /**
     * Return the size of the file at location in bytes.
     *
     * @return integer
     */
    public function getSize(): integer;

    /**
     * Make a copy of the file resource.
     *
     * @param string $destination Location of the copy to be made.
     * @return void
     */
    public function copyTo(string $destination) : void;

    /**
     * Delete the file resource.
     *
     * @return void
     */
    public function delete(): void;

    /**
     * Get the location of th
     *
     * @return string
     */
    public function getPath(): string;
}
