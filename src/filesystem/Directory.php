<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\Filesystem;
use ntentan\utils\exceptions\FilesystemException;

/**
 * 
 */
class Directory implements FileInterface {

    private $path;

    private function __construct($path = null) {
        $this->path = $path;
    }

    public function copyTo($destination) {
        Filesystem::isWritable(dir($destination));
        if (!is_dir($destination) && !file_exists($destination)) {
            self::create($destination);
        }

        foreach (glob($source) as $file) {
            $newFile = (is_dir($destination) ? "$destination/" : '') . basename("$file");
            if (is_dir($file)) {
                self::create($newFile);
                self::copyDir("$file/*", $newFile);
            } else {
                copy($file, $newFile);
            }
        }
    }

    public function getSize() {
        
    }

    public function moveTo($destination) {
        
    }

    public static function create($path) {
        Filesystem::checkWriteSafety(dirname($path));
        if (file_exists($path) && !is_dir($path)) {
            throw new FilesystemException("A file already exists in the location of [$path]");
        }
        if (!file_exists($path)) {
            mkdir($path);
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
