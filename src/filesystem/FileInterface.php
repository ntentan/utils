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
     * @param int $overwrite
     * @return void
     */
    public function moveTo(string $destination, int $overwrite = 0) : void;

    /**
     * Return the size of the file at location in bytes.
     *
     * @return integer
     */
    public function getSize(): int;

    /**
     * Make a copy of the file resource.
     *
     * @param string $destination Location of the copy to be made.
     * @param int $overwrite
     * @return void
     */
    public function copyTo(string $destination, int $overwrite = 0) : void;

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

    /**
     * Delete a file if it exists.
     */
    public function deleteIfExists(): void;
}
