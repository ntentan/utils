<?php
namespace ntentan\utils;

class CamelCase
{
    public static function camelize($string, $separator = '_')
    {
        if(is_array($separator))
        {
            $separator = "(\\" . implode("|\\", $separator) . ")";
        }
        else
        {
            $separator = '\\' . $separator;
        }
        return preg_replace_callback(
            "/{$separator}[a-zA-Z]/", 
            function ($matches) 
            {
                return strtoupper($matches[0][1]);
            }, 
            strtolower($string)
        );
    }
    
    public static function ucamelize($string, $separator = '_')
    {
        return ucfirst(self::camelize($string, $separator));
    }
    
    public static function deCamelize($string, $separator)
    {
        return preg_replace_callback(
            "/[A-Z][a-z]/", 
            function ($matches) use($separator) 
            {
                return $separator . strtolower($matches[0]);
            }, 
            lcfirst($string)
        );        
    }
}
