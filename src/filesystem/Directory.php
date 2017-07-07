<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\Filesystem;
use ntentan\utils\exceptions\FilesystemException;

/**
 * 
 */
class Directory implements FileInterface {

    private $path;

    public function __construct($path = null) {
        $this->path = $path;
    }

    public function copyTo($destination) {
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

    public function getSize() {
        
    }

    public function moveTo($destination) {
        
    }

    public static function create($path, $permissions = 0755) {
        if (file_exists($path) && !is_dir($path)) {
            throw new FilesystemException("A file already exists in the location of [$path]");
        }
        if (!file_exists($path)) {
            mkdir($path, $permissions, true);
        }
        return new Directory($path);
    }

    public function delete() {
        
    }

    public function getPath() {
        return $this->path;
    }

    public function __toString() {
        return $this->path;
    }

}
