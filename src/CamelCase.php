<?php
namespace ntentan\utils;

class CamelCase
{
    public function camelize($string, $separator = '_')
    {
        if(is_array($separator))
        {
            $separator = "(" . implode(",", $separator) . ")";
        }
        preg_replace_callback(
            "/{$separator}[a-zA-Z]/", 
            function ($matches) 
            {
                return strtoupper($matches[0][1]);
            }, 
            $string
        );
    }
    
    public function unCamelize($string, $separator)
    {
        preg_replace_callback(
            "/[A-Z][a-z]/", 
            function ($matches) 
            {
                return strtoupper($matches[0][1]);
            }, 
            $string
        );        
    }
}
