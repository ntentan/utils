<?php

namespace ntentan\utils\exceptions;


class FileAlreadyExistsException extends FilesystemException
{

    /**
     * FileAlreadyExistsException constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct("File '$path' already exists");
    }
}
