<?php

namespace ntentan\utils\filesystem;

use ntentan\utils\Filesystem;

/**
 * Description of File
 *
 * @author ekow
 */
class File
{
    protected $path;
    
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    public function moveTo($destination)
    {
        Filesystem::checkWritable($destination);
        copy($this->path, $destination);
        unlink($this->path);
        $this->path = $destination;
    }
    
    public function getSize()
    {
        return filesize($this->path);
    }
}
