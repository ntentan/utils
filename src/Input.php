<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\utils;

/**
 * Description of Globals
 *
 * @author ekow
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
    
    public static function get($key = null)
    {
        return self::getVariable(INPUT_GET, $key);
    }
    
    public static function post($key)
    {
        return self::getVariable(INPUT_POST, $key);
    }
    
    public static function server($key)
    {
        return self::getVariable(INPUT_SERVER, $key);
    }
    
    public static function request($key)
    {
        return self::getVariable(INPUT_REQUEST, $key);
    }
    
    public static function session($key)
    {
        return self::getVariable(INPUT_SESSION, $key);
    }
    
    public static function cookie($key)
    {
        return self::getVariable(INPUT_COOKIE, $key);
    }
    
    public static function exists($input, $key)
    {
        return isset(self::getVariable($input, null)[$key]);
    }
}
