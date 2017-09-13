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

/**
 * An input filter class which is wraped around PHP's `filter_input` and
 * `filter_input_array` classes. This class provides methods which allow safe
 * and secure access to data passed to an application. 
 *
 * @author James Ainooson
 */
class Input 
{
    /**
     * Constant for POST request.
     */
    const POST = INPUT_POST;

    /**
     * Constant for GET request.
     */
    const GET = INPUT_GET;

    /**
     * Constant for INPUT request.
     */
    const REQUEST = INPUT_REQUEST;

    /**
     * Cache or arrays which hold decoded query strings.
     *
     * @var array
     */
    private static $arrays = [];
    
    /**
     * Decodes and returns the value of a given item in an HTTP query.
     * Although PHP decodes query strings automatically, it converts periods to underscores. This method makes it 
     * possible to decode query strings that contain underscores.
     * http://stackoverflow.com/a/14432765
     * 
     * @param string $method The HTTP method of the query (GET, POST ...)
     * @param string $key The key of the item in the query to retrieve.
     * @return mixed
     */
    private static function decode($method, $key) 
    {
        if(!isset(self::$arrays[$method])) {
            $query = $method == self::GET 
                ? filter_input(INPUT_SERVER, 'QUERY_STRING') 
                : file_get_contents('php://input');
            $query = preg_replace_callback('/(?:^|(?<=&))[^=[]+/', 
                function($match) {
                    return bin2hex($match[0]);
                }, urldecode($query));
            parse_str($query, $data);
            self::$arrays[$method] = array_combine(array_map('hex2bin', array_keys($data)), $data);        
        }
        return $key ? (self::$arrays[$method][$key] ?? null) : (self::$arrays[$method] ?? null);
    }
    
    /**
     * Does the actual work of calling either the filter_input of 
     * filter_input_array. Calls the filter_input when a data key is provided
     * and callse the filte_input_array when a data key is absent.
     * 
     * @param string $input Input type
     * @param string $key The data key
     * @return string|array The value.
     */
    private static function getVariable($input, $key) 
    {
        if ($key === null) {
            if (!isset(self::$arrays[$input])) {
                self::$arrays[$input] = filter_input_array($input);
            }
            $return = self::$arrays[$input];
        } else {
            $return = filter_input($input, $key);
        }

        if ($return === null && $key === null) {
            $return = array();
        }

        return $return;
    }
    
    /**
     * Retrieves GET request variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function get($key = null) 
    {
        return self::decode(self::GET, $key);
    }

    /**
     * Retrieves post request variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function post($key = null) 
    {
        return self::decode(self::POST, $key);
    }

    /**
     * Retrieves server variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function server($key = null) 
    {
        return self::getVariable(INPUT_SERVER, $key);
    }

    /**
     * Retrieves cookie variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function cookie($key = null) 
    {
        return self::getVariable(INPUT_COOKIE, $key);
    }

    /**
     * Checks if a particular key exists in a given request query.
     *
     * @param string $input
     * @param string $key
     * @return bool
     */
    public static function exists($input, $key) : bool
    {
        return isset(self::getVariable($input, null)[$key]);
    }

    public static function files($key = null) 
    {
        $files = [];
        if (!isset($_FILES[$key])) {
            return null;
        }
        if (is_array($_FILES[$key]['name'])) {
            for ($i = 0; $i < count($_FILES[$key]['name']); $i++) {
                $files[] = new filesystem\UploadedFile([
                    'name' => $_FILES[$key]['name'][$i],
                    'type' => $_FILES[$key]['type'][$i],
                    'tmp_name' => $_FILES[$key]['tmp_name'][$i],
                    'error' => $_FILES[$key]['error'][$i],
                    'size' => $_FILES[$key]['size'][$i],
                ]);
            }
            return $files;
        } else {
            return new filesystem\UploadedFile($_FILES);
        }
    }

}
