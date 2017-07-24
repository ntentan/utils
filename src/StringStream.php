<?php

/*
 * Adapted for use in ntentan\utils from dvdoug\stringstream package.
 */

/**
 * Stream wrapper for strings.
 * 
 * @author Doug Wright
 */

namespace ntentan\utils;

/**
 * Stream wrapper for strings which allows you to read strings as though they
 * were I/O streams.
 * @author Doug Wright
 * @package StringStream
 */
class StringStream 
{

    /**
     * Content of stream
     * @var string
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

    private function setFlags($read, $write, $position)
    {
        $this->read = $read;
        $this->write = $write;
        $this->position = $position;
    }

    /**
     * Open stream
     * @param string $aPath
     * @param string $aMode
     * @param int $aOptions
     * @param string $aOpenedPath
     * @return boolean
     */
    public function stream_open($aPath, $aMode, $aOptions, &$aOpenedPath) 
    {
        $this->path = substr($aPath, 9);
        if (!isset(self::$string[$this->path])) {
            self::$string[$this->path] = '';
        }
        $this->options = $aOptions;
        $aOpenedPath = $this->path;

        switch ($aMode) {

            case 'r':
            case 'rb':
                $this->setFlags(true, false, 0);
                break;

            case 'r+':
            case 'c+':
                $this->setFlags(true, true, 0);
                break;

            case 'w':
            case 'wb':
                $this->setFlags(false, true, 0);
                break;

            case 'w+':
                $this->setFlags(true, true, 0);
                $this->stream_truncate(0);
                break;

            case 'a':
                $this->setFlags(false, true, strlen(self::$string[$this->path]));
                break;

            case 'a+':
                $this->setFlags(true, true, strlen(self::$string[$this->path]));
                break;

            case 'c':
                $this->setFlags(false, true, 0);
                break;

            default:
                trigger_error($aMode . 'Invalid mode specified (mode specified makes no sense for this stream implementation)', E_USER_ERROR);
        }

        return true;
    }

    /**
     * Read from stream
     * @param int $aBytes number of bytes to return
     * @return string
     */
    function stream_read($aBytes) {
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
     * @return int
     */
    function stream_write($aData) {
        if ($this->write) {
            $left = substr(self::$string[$this->path], 0, $this->position);
            $right = substr(self::$string[$this->path], $this->position + strlen($aData));
            self::$string[$this->path] = $left . $aData . $right;
            $this->position += strlen($aData);
            return strlen($aData);
        } else {
            return 0;
        }
    }

    /**
     * Return current position
     * @return int
     */
    function stream_tell() {
        return $this->position;
    }

    /**
     * Return if EOF
     * @return boolean
     */
    function stream_eof() {
        return $this->position >= strlen(self::$string[$this->path]);
    }

    /**
     * Seek to new position
     * @param int $aOffset
     * @param int $aWhence
     * @return boolean
     */
    function stream_seek($aOffset, $aWhence) {
        switch ($aWhence) {
            case SEEK_SET:
                $this->position = $aOffset;
                if ($aOffset > strlen(self::$string[$this->path])) {
                    $this->stream_truncate($aOffset);
                }
                return true;
                break;

            //XXX Code coverage testing shows PHP truncates automatically for SEEK_CUR
            case SEEK_CUR:
                $this->position += $aOffset;
                return true;
                break;

            case SEEK_END:
                $this->position = strlen(self::$string[$this->path]) + $aOffset;
                if (($this->position + $aOffset) > strlen(self::$string[$this->path])) {
                    $this->stream_truncate(strlen(self::$string[$this->path]) + $aOffset);
                }
                return true;
                break;

            default:
                return false;
        }
    }

    /**
     * Truncate to given size
     * @param int $aSize
     */
    public function stream_truncate($aSize) {
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
    public function stream_stat() {
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
     * @param array $aOptions
     * @return array
     */
    public function url_stat($aPath, $aOptions) {
        $resource = fopen($aPath, 'r');
        return fstat($resource);
    }

    public static function register() {
        if (!self::$registered) {
            if (in_array("string", stream_get_wrappers())) {
                stream_wrapper_unregister("string");
            }
            stream_wrapper_register("string", __CLASS__);
            self::$registered = true;
        }
    }

    public static function unregister() {
        if (self::$registered) {
            stream_wrapper_unregister('string');
        }
    }

}
