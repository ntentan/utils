<?php
/*
 * Ntentan Framework
 * Copyright (c) 2008-2015 James Ekow Abaka Ainooson
 * 
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 
 * 
 */

namespace ntentan\utils;

use ntentan\utils\filesystem\Directory;

/**
 * A collection of filesystem utilities.
 * 
 */
class Filesystem
{
    /**
     * Checks if a file is writeable.
     * In cases where the file cannot be written to an exception is thrown.
     *
     * @param string $path The path to the file to be checked.
     * @throws exceptions\FileNotWriteabkeException
     * @return bool
     */
    public static function checkWritable($path) : bool
    {
        if (!is_writable($path)) {
            throw new exceptions\FileNotWriteableException("File $path is not writeable");
        }
        return true;
    }

    /**
     * Check if a file is readable.
     * In cases where the file cannot be read, an exception is thrown.
     *
     * @param string $path The path to the file to be checked.
     * @throws exceptions\FileNotReadableException
     * @return bool
     */
    public static function checkReadable($path) : bool
    {
        if (!is_readable($path)) {
            throw new exceptions\FileNotReadableException("File $path is not readable");
        }
        return true;
    }

    public static function checkExists($path)
    {
        if (!file_exists($path)) {
            throw new exceptions\FileNotFoundException($path);
        }
        return true;
    }

    public static function checkWriteSafety($path)
    {
        Filesystem::checkExists($path);
        Filesystem::checkWritable($path);
    }

    public static function createDirectoryStructure($structure, $basePath)
    {
        foreach ($structure as $key => $value) {
            if (is_numeric($key)) {
                Directory::create("$basePath/$value");
            } else {
                Directory::create("$basePath/$key");
                self::createDirectoryStructure($value, "$basePath/$key");
            }
        }
    }

    public static function get($path)
    {
        if (is_dir($path)) {
            return new filesystem\Directory($path);
        }
        return new filesystem\File($path);
    }
}
