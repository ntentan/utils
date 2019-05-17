<?php


namespace ntentan\utils\filesystem;


use ntentan\utils\Filesystem;
use test\base;

class FileCollection implements \Iterator, \ArrayAccess, FileInterface, \Countable
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
            $file->moveTo($destination . DIRECTORY_SEPARATOR . basename($file));
        }
    }

    public function copyTo(string $destination): void
    {
        foreach($this as $file) {
            $file->copyTo($destination . DIRECTORY_SEPARATOR . basename($file));
        }
    }

    public function getSize(): int
    {
        return array_reduce(iterator_to_array($this),
            function($carry, $item){
                return $carry + $item->getSize();
            }, 0);
    }

    public function delete(): void
    {
        foreach($this as $file) {
            $file->delete();
        }
    }

    public function getPath(): string
    {
        return array_reduce($this->paths,
            function($carry, $path) {
                $carry .= escapeshellarg($path) . " ";
            }, "");
    }

    public function count()
    {
        return count($this->paths);
    }
}