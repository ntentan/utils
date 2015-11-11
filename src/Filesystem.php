<?php
namespace ntentan\utils;

class Filesystem
{
    public static function isWritable($path)
    {
        return is_writable($path);
    }
    
    public static function checkWritable($path)
    {
        if(!self::isWritable($path)) {
            throw new exceptions\FilesystemException("File $path is not writeable");
        }
    }
}
