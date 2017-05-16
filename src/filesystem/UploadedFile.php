<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\exceptions\FilesystemException;
use ntentan\utils\Filesystem;

class UploadedFile extends File {

    private $clientName;
    private $type;
    private $error;
    private $size;

    public function __construct($file) {
        parent::__construct($file['tmp_name']);
        $this->clientName = $file['name'];
        $this->type = $file['type'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        if(!is_uploaded_file($file['tmp_name'])) {
            throw new FilesystemException(
                "File {$file['tmp_name']} is not an uploaded file"
            );
        }
    }

    public function getSize() {
        return $this->size;
    }

    public function moveTo($destination) {
        Filesystem::checkWritable(dirname($destination));
        if(!move_uploaded_file($this->path, $destination)) {
            throw new FilesystemException(
                "Failed to move file {$this->path} to {$destination}"
            );
        }
    }

    public function getError() {
        return $this->error;
    }

    public function getClientName() {
        return $this->clientName;
    }

    public function getType() {
        return $this->type;
    }

}
