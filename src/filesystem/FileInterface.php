<?php
namespace ntentan\utils\filesystem;

interface FileInterface
{
    public function moveTo($destination);
    public function getSize();
    public function copyTo($destination);
    public function delete();
    public function getPath();
}