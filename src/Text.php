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
 * A couple of utility functions for manipulating strings.
 */
class Text
{
    /**
     * Converts text separated by a specified separator to camel case. 
     * This function converts the entire text into lower case before performing the
     * camel case conversion. Due to this the first character would be lowercased.
     * 
     * @param string $string The text to be converted.
     * @param string $separator The separator to consider for camel casing
     * @return string
     */
    public static function camelize($string, $separator = '_') : string
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
    
    /**
     * Converts text separated by a specified separator to camel case. 
     * This method works just as the Text::camelize method except that it converts
     * the first character to uppercase.
     * 
     * @param string $string The text to be converted.
     * @param string $separator The separator to consider for camel casing
     * @return string
     */
    public static function ucamelize($string, $separator = '_') : string
    {
        return ucfirst(self::camelize($string, $separator));
    }
    
    /**
     * Converts camel case text into regular text separated with an arbitrary separator.
     * By default the seperator is an underscore. A space can also be used as the 
     * seperator in cases where the conversion is to an English sentence.
     * 
     * @param string $string The text to be converted.
     * @param string $separator The separator to be used.
     * @return string
     */    
    public static function deCamelize($string, $separator = '_') : string
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
    
    /**
     * Generates the english plural of a given word.
     *
     * @param string $text
     * @return string
     */
    public static function pluralize($text) : string
    {
        $lastLetter = substr($text, -1);
        if($lastLetter == 'y') {
            return substr($text, 0, -1) . 'ies';
        } elseif ( $lastLetter != 's' ) {
            return $text . 's';
        }
        return $text;
    }
    
    /**
     * Generates the english singular of a given word.
     *
     * @param string $text
     * @return string
     */
    public static function singularize($text) : string
    {
        if(substr($text, -1) == 's') {
            return substr($text, 0, -1);
        } elseif (substr($text, -3) == 'ies') {
            return substr($text, 0, -3) . 'y';
        }
        return $text;
    }
}
