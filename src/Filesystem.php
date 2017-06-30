<?php

namespace ntentan\utils;

use ntentan\utils\filesystem\Directory;

class Filesystem {

    public static function checkWritable($path) {
        if (!is_writable($path)) {
            throw new exceptions\FileNotWriteableException("File $path is not writeable");
        }
        return true;
    }

    public static function checkReadable($path) {
        if (!is_readable($path)) {
            throw new exceptions\FileNotReadableException("File $path is not readable");
        }
        return true;
    }

    public static function checkExists($path) {
        if (!file_exists($path)) {
            throw new exceptions\FileNotFoundException($path);
        }
        return true;
    }

    public static function checkWriteSafety($path) {
        Filesystem::checkExists($path);
        Filesystem::checkWritable($path);
    }

    public static function createDirectoryStructure($structure, $basePath) {
        foreach ($structure as $key => $value) {
            if (is_numeric($key)) {
                Directory::create("$basePath/$value");
            } else {
                Directory::create("$basePath/$key");
                self::createDirectoryStructure($value, "$basePath/$key");
            }
        }
    }

    public static function get($path) {
        if (is_dir($path)) {
            return new filesystem\Directory($path);
        }
        return new filesystem\File($path);
    }

}
