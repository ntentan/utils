<?php

namespace ntentan\utils\exceptions;

class FileNotFoundException extends FilesystemException
{
    public function __construct($path) {
        parent::__construct("File '$path' not found");
    }
}
