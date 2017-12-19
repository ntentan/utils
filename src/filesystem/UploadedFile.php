<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\exceptions\FilesystemException;
use ntentan\utils\Filesystem;

/**
 * Represents file that was uploaded through PHPs internal HTTP mechanisms.
 *
 * @package ntentan\utils\filesystem
 */
class UploadedFile extends File
{
    /**
     * Filename of the file from the client.
     *
     * @var string
     */
    private $clientName;

    /**
     * File type.
     *
     * @var string
     */
    private $type;

    /**
     * Any upload errors that occured.
     * These are based on the errors described for PHPs $_FILES global variable.
     *
     * @var int
     */
    private $error;

    /**
     * Size reported when file was uploaded.
     *
     * @var int
     */
    private $size;

    /**
     * Create a new instance.
     *
     * @param array $file The $_FILES[name] value for a given upload field
     * @throws FilesystemException
     */
    public function __construct($file)
    {
        parent::__construct($file['tmp_name']);
        $this->clientName = $file['name'];
        $this->type = $file['type'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new FilesystemException("File {$file['tmp_name']} is not an uploaded file");
        }
    }

    /**
     * Get the size of the uploaded file as reported by PHP.
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Move the uploaded file safely to another location.
     * Ensures that files were actually uploaded through PHP before moving them.
     *
     * @param string $destination
     * @throws FilesystemException
     * @throws \ntentan\utils\exceptions\FileNotWriteableException
     */
    public function moveTo(string $destination): void
    {
        $destination = is_dir($destination) ? ("$destination/{$this->clientName}") : $destination;
        Filesystem::checkWritable(dirname($destination));
        if (!move_uploaded_file($this->path, $destination)) {
            throw new FilesystemException("Failed to move file {$this->path} to {$destination}");
        }
    }

    /**
     * Return the error code assigned when file was uploaded.
     * See the documentation for the $_FILES global variable for a description of this value.
     *
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Get the name of the file assigned from the client system.
     *
     * @return string
     */
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * Get the mime type of the file.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}
