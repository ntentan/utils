<?php


namespace ntentan\utils\filesystem;


use ntentan\utils\Filesystem;

class FileCollection implements \Iterator, \ArrayAccess, FileInterface
{
    private $paths;
    private $iteratorIndex;
    private $instances;

    public function __construct($paths)
    {
        $this->paths = $paths;
        $this->iteratorIndex = 0;
    }

    private function getInstance($index)
    {
        if(!isset($this->instances[$index])) {
            if(is_dir($this->paths[$index])) {
                $this->instances[$index] = Filesystem::directory($this->paths[$index]);
            } else {
                $this->instances[$index] = Filesystem::file($this->paths[$index]);
            }
        }
        return $this->instances[$index];
    }

    public function rewind()
    {
        $this->iteratorIndex = 0;
    }

    public function current()
    {
        return $this->getInstance($this->iteratorIndex);
    }

    public function key()
    {
        return $this->iteratorIndex;
    }

    public function next()
    {
        $this->iteratorIndex++;
    }

    public function valid()
    {
        return isset($this->paths[$this->iteratorIndex]);
    }

    public function offsetSet($index, $path)
    {
        if(is_null()) {
            $this->paths[] = $path;
        } else {
            $this->paths[$index] = $path;
            unset($this->instances[$index]);
        }
    }

    public function offsetExists($index)
    {
        return isset($this->paths[$index]);
    }

    public function offsetGet($index)
    {
        return isset($this->paths[$index]) ? $this->paths[$index] : null;
    }

    public function offsetUnset($index)
    {
        unset($this->paths[$index]);
    }

    public function moveTo(string $destination): void
    {
        foreach($this as $file) {
            $file->moveTo($destination);
        }
    }

    public function getSize(): int
    {
        return array_reduce($this,
            function($carry, $item){
                $carry += $item->getSize();
            }, 0);
    }

    public function copyTo(string $destination): void
    {
        foreach($this as $file) {
            $this->copyTo($destination);
        }
    }
}