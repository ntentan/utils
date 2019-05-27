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
use ntentan\utils\exceptions\FileNotFoundException;
use ntentan\utils\filesystem\Directory;
use ntentan\utils\filesystem\File;
use ntentan\utils\filesystem\FileInterface;

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
     * @throws exceptions\FileNotWriteableException
     */
    public static function checkWritable(string $path) : void
    {
        if (!is_writable($path)) {
            throw new exceptions\FileNotWriteableException("Location [$path] is not writeable");
        }
    }

    /**
     * Check if a file is readable.
     * In cases where the file cannot be read, an exception is thrown.
     *
     * @param string $path The path to the file to be checked.
     * @throws exceptions\FileNotReadableException
     */
    public static function checkReadable(string $path) : void
    {
        if (!is_readable($path)) {
            throw new exceptions\FileNotReadableException("Location $path is not readable");
        }
    }

    /**
     * Checks if a file exists and throws an exception if not.
     *
     * @param $path
     * @throws exceptions\FileNotFoundException
     */
    public static function checkExists(string $path) : void
    {
        if (!file_exists($path)) {
            throw new exceptions\FileNotFoundException("Location '$path' does not exist");
        }
    }

    /**
     * Checks if a file exists and throws an exception if it does.
     *
     * @param string $path
     * @throws exceptions\FileAlreadyExistsException
     */
    public static function checkNotExists(string $path) : void
    {
        if(file_exists($path)) {
            throw new exceptions\FileAlreadyExistsException("Location '$path' already exists");
        }
    }

    /**
     * Checks if a file exists and is writeable and throws a relevant exception if either condition is not met.
     *
     * @param $path
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\FileNotWriteableException
     */
    public static function checkWriteSafety($path)
    {
        Filesystem::checkExists($path);
        Filesystem::checkWritable($path);
    }

    /**
     * Checks if a file exists and is readable and throws a relevant excetion if either condition is not met.
     *
     * @param $path
     * @throws exceptions\FileNotFoundException
     * @throws exceptions\FileNotReadableException
     */
    public static function checkReadSafety($path)
    {
        Filesystem::checkExists($path);
        Filesystem::checkReadable($path);
    }

    /**
     * Return an instance of the relevant FileInterface (File or Directory) for a file in a given path.
     *
     * @param string $path
     * @return FileInterface
     * @throws FileNotFoundException
     */
    public static function get($path)
    {
        if (is_dir($path)) {
            return new filesystem\Directory($path);
        } else if(is_file($path)) {
            return new filesystem\File($path);
        }
        throw new FileNotFoundException("Could not get location '{$path}'");
    }

    public static function file($path)
    {
        return new filesystem\File($path);
    }

    public static function directory($path)
    {
        return new filesystem\Directory($path);
    }

    public static function glob($glob)
    {
        return new filesystem\FileCollection(glob($glob));
    }
}
