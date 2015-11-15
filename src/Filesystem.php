<?php
namespace ntentan\utils;

class Filesystem
{
    public static function isWritable($path)
    {
        return is_writable($path);
    }
    
    public static function exists($path)
    {
        return file_exists($path);
    }
    
    public static function checkWritable($path)
    {
        if(!self::isWritable($path)) {
            throw new exceptions\FilesystemException("File $path is not writeable");
        }
    }
    
    public static function checkExists($path)
    {
        if(!self::exists($path)) {
            throw new exceptions\FilesystemException("File $path does not exist");
        }
    }
}
