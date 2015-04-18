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
    private static function getVariable($input, $key)
    {
        if($key === null)
        {
            $return = filter_input_array($input);
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
}
