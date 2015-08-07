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
    const POST = INPUT_POST;
    const GET = INPUT_GET;
    const REQUEST = INPUT_REQUEST;
    const SESSION = INPUT_SESSION;
    
    private static $arrays = [];
    
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
        if($key === null)
        {
            if(!isset(self::$arrays[$input]))
            {
                self::$arrays[$input] = filter_input_array($input);
            }
            $return = self::$arrays[$input];
        }
        else
        {
            $return = filter_input($input, $key);
        }
        
        if($return === null && $key === null)
        {
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
        return self::getVariable(INPUT_GET, $key);
    }
    
    /**
     * Retrieves post request variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function post($key)
    {
        return self::getVariable(INPUT_POST, $key);
    }
    
    /**
     * Retrieves server variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function server($key)
    {
        return self::getVariable(INPUT_SERVER, $key);
    }
    
    /**
     * Retrieves request variables. This includes all variables passed through
     * POST and GET as well as COOKIES.
     * @param string $key
     * @return string|array
     */
    public static function request($key)
    {
        return self::getVariable(INPUT_REQUEST, $key);
    }
    
    /**
     * Retrieves session variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function session($key)
    {
        return self::getVariable(INPUT_SESSION, $key);
    }
    
    /**
     * Retrieves cookie variables.
     * 
     * @param string $key
     * @return string|array
     */
    public static function cookie($key)
    {
        return self::getVariable(INPUT_COOKIE, $key);
    }
    
    public static function exists($input, $key)
    {
        return isset(self::getVariable($input, null)[$key]);
    }
}
