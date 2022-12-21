<?php

/*
 * Adapted for use in ntentan\utils from dvdoug\stringstream package.
 */

namespace ntentan\utils;

/**
 * Stream wrapper for strings which allows you to read strings as though they
 * were I/O streams.
 * @author Doug Wright, James Ainooson
 * @package StringStream
 */
class StringStream
{

    /**
     * Content of stream
     * @var array
     */
    private static $string = [];

    /**
     * Whether this stream can be read
     * @var boolean
     */
    private $read;

    /**
     * Whether this stream can be written
     * @var boolean
     */
    private $write;

    /**
     * Options
     * @var int
     */
    private $options;

    /**
     * Current position within stream
     * @var int
     */
    private $position;
    private $path;
    private static $registered = false;
    
    public $context;

    private function setFlags($read, $write, $position)
    {
        $this->read = $read;
        $this->write = $write;
        $this->position = $position;
    }

    /**
     * Open a stream
     *
     * @param string $aPath
     * @param string $aMode
     * @param int $aOptions
     * @param string $aOpenedPath
     * @return boolean
     * @throws exceptions\StringStreamException
     */
    public function stream_open($aPath, $aMode, $aOptions, &$aOpenedPath)
    {
        $this->path = substr($aPath, 9);
        if (!isset(self::$string[$this->path])) {
            self::$string[$this->path] = '';
        }
        $this->options = $aOptions;
        $aOpenedPath = $this->path;
        $lenght = strlen(self::$string[$this->path]);
        $flags = [
            'r' => [true, false, 0],
            'rb' => [true, false, 0],
            'r+' => [true, true, 0],
            'c+' => [true, true, 0],
            'w' => [false, true, 0],
            'wb' => [false, true, 0],
            'w+' => [true, true, 0],
            'a' => [false, true, $lenght],
            'a+' => [true, true, $lenght],
            'c' => [false, true, 0]
        ];

        if (isset($flags[$aMode])) {
            $flag = $flags[$aMode];
            $this->setFlags($flag[0], $flag[1], $flag[2]);
        } else {
            throw new exceptions\StringStreamException("Unknown stream mode '{$aMode}'");
        }

        if ($aMode === 'w+') {
            $this->stream_truncate(0);
        }

        return true;
    }

    /**
     * Read from stream
     * @param int $aBytes number of bytes to return
     * @return string|bool
     */
    public function stream_read($aBytes)
    {
        if ($this->read) {
            $read = substr(self::$string[$this->path], $this->position, $aBytes);
            $this->position += strlen($read);
            return $read;
        } else {
            return false;
        }
    }

    /**
     * Write to stream
     * @param string $aData data to write
     * @return int|bool
     */
    public function stream_write($aData)
    {
        if ($this->write) {
            $left = substr(self::$string[$this->path], 0, $this->position);
            $right = substr(self::$string[$this->path], $this->position + strlen($aData));
            self::$string[$this->path] = $left . $aData . $right;
            $this->position += strlen($aData);
            return strlen($aData);
        } else {
            return false;
        }
    }

    /**
     * Return current position
     * @return int
     */
    public function stream_tell()
    {
        return $this->position;
    }

    /**
     * Return if EOF
     * @return boolean
     */
    public function stream_eof()
    {
        return $this->position >= strlen(self::$string[$this->path]);
    }

    /**
     * Seek to new position
     * @param int $aOffset
     * @param int $aWhence
     * @return boolean
     */
    public function stream_seek($aOffset, $aWhence)
    {
        switch ($aWhence) {
            case SEEK_SET:
                $this->position = $aOffset;
                if ($aOffset > strlen(self::$string[$this->path])) {
                    $this->stream_truncate($aOffset);
                }
                return true;

            case SEEK_CUR:
                $this->position += $aOffset;
                return true;

            case SEEK_END:
                $this->position = strlen(self::$string[$this->path]) + $aOffset;
                if (($this->position + $aOffset) > strlen(self::$string[$this->path])) {
                    $this->stream_truncate(strlen(self::$string[$this->path]) + $aOffset);
                }
                return true;

            default:
                return false;
        }
    }

    /**
     * Truncate to given size
     * @param int $aSize
     * @return bool
     */
    public function stream_truncate($aSize)
    {
        if (strlen(self::$string[$this->path]) > $aSize) {
            self::$string[$this->path] = substr(self::$string[$this->path], 0, $aSize);
        } else if (strlen(self::$string[$this->path]) < $aSize) {
            self::$string[$this->path] = str_pad(self::$string[$this->path], $aSize, "\0", STR_PAD_RIGHT);
        }
        return true;
    }

    /**
     * Return info about stream
     * @return array
     */
    public function stream_stat()
    {
        return array('dev' => 0,
            'ino' => 0,
            'mode' => 0,
            'nlink' => 0,
            'uid' => 0,
            'gid' => 0,
            'rdev' => 0,
            'size' => strlen(self::$string[$this->path]),
            'atime' => 0,
            'mtime' => 0,
            'ctime' => 0,
            'blksize' => -1,
            'blocks' => -1);
    }

    /**
     * Return info about stream
     * @param string $aPath
     * @return array
     */
    public function url_stat($aPath)
    {
        $resource = fopen($aPath, 'r');
        return fstat($resource);
    }

    /**
     * @param string $protocol
     * @throws exceptions\StringStreamException
     */
    public static function register($protocol = 'string')
    {
        if (!self::$registered) {
            if (in_array($protocol, stream_get_wrappers())) {
                throw new exceptions\StringStreamException("An existing wrapper already exists for '$protocol'");
            }
            stream_wrapper_register("string", __CLASS__);
            self::$registered = true;
        }
    }

    public static function unregister()
    {
        if (self::$registered) {
            stream_wrapper_unregister('string');
            self::$registered = false;
            self::$string = [];
        }
    }

}
