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
    private static $pluralRules = [
        ['/child/', 'ren'],
        ['/^ox$/', 'en'],
        ['/(.*)(a|e|i|o|u)(?<remove>y)$/', 'ys'],
        ['/(.*)(?<remove>y)$/', 'ies'],
        ['/(foc|alumn|fung|nucle|octop|radi|syllab)(?<remove>us)$/', 'i'],
        ['/(.*)(d|r)(?<remove>ex|ix)$/', 'ices'],
        ['/(.*)(s|x)(?<remove>is)$/', 'es'],
        ['/(.*)(?<remove>sh)$/', 'shes'],
        ['/(.*)(?<remove>eau)$/', 'eaux'],
        ['/(.*)(?<remove>um)$/', 'a'],
        ['/(.*)(?<remove>tooth)$/', 'teeth'],
        ['/(.*)(?<remove>h)$/', 'hes'],
        ['/(formul|alumn|nebul)(?<remove>a)$/', 'ae'],
        ['/(.*)(?<remove>x)$/', 'xes'],
        ['/(.+)(?<remove>ion)$/', 'ia'],
        ['/(.*)(?<remove>roof)$/', 'roofs'],
        ['/(.*)[^f](?<remove>f|fe)$/', 'ves'],
        ['/(.*)(m|l)(?<remove>ouse)$/', 'ice'],
        ['/(.*)(?<remove>man)$/', 'men'],
        ['/(.*)(?<remove>foot)$/', 'feet'],
        ['/(.*)(disc|phot|pian)(?<remove>o)$/', 'os'],
        ['/(.*)(?<remove>goose)$/', 'geese'],
        ['/(.*)(?<remove>person)$/', 'people'],
        ['/(.*)(?<remove>quiz)$/', 'quizzes'],
        ['/.*(s|o|z)$/', 'es'],
        ['/.*/', 's']
    ];

    private static $singularRules = [
        ['/^axe(?<remove>s)$/', ''],
        ['/(.*)(?<remove>a)$/', 'um'],
        ['/(.*)(dev|v|pr)(?<remove>ices)$/', 'ice'],
        ['/(.*)(?<remove>ices)$/', 'ix'],
        ['/(.*)(?<remove>movies)$/', 'movie'],
        ['/(.*)(?<remove>ies)$/', 'y'],
        ['/(.*)(?<remove>shoes)$/', 'shoe'],
        ['/(.*)(?<remove>oes)$/', 'o'],
        ['/(.*)(?<remove>bases)$/', 'base'],
        ['/(.*)(?<remove>cheeses)$/', 'cheese'],
        ['/(.*)(?<remove>children)$/', 'child'],
        ['/(.*)(?<remove>men)$/', 'man'],
        ['/(.*)(?<remove>feet)$/', 'foot'],
        ['/(.*)(?<remove>geese)$/', 'goose'],
        ['/(.*)(?<remove>atlases)$/', 'atlas'],
        ['/(.*)(?<remove>people)$/', 'person'],
        ['/(.*)(?<remove>teeth)$/', 'tooth'],
        ['/(.*)(iri)(?<remove>ses)$/', 's'],
        ['/(.*)(h|l|p)(ou)(?<remove>ses)$/', 'se'],
        ['/(.*)(ro|po|ca)(?<remove>ses)$/', 'se'],
        ['/(.*)(?<remove>quizzes)$/', 'quiz'],
        ['/(.*)(?<remove>zes)$/', 'z'],
        ['/(.*)(y|i|a|o|e)(?<remove>ses)$/', 'sis'],
        ['/(.*)(?<remove>ses)$/', 's'],
        ['/(.*)(?<remove>ice)$/', 'ouse'],
        ['/(.*)(?<remove>xes)$/', 'x'],
        ['/(.*)(?<remove>eaux)$/', 'eau'],
        ['/(formul|alumn|nebul)(?<remove>ae)$/', 'a'],
        ['/(foc|alumn|fung|nucle|octop|radi|syllab)(?<remove>i)$/', 'us'],
        ['/(.*)(?<remove>hes)$/', 'h'],
        ['/(.*)(ca|mo|lo)(?<remove>ves)$/', 've'],
        ['/(.*)(l|r|o|a|e)(?<remove>ves)$/', 'f'],
        ['/(.*)(li|ni|wi)(?<remove>ves)$/', 'fe'],
        ['/(.*)(?<remove>s)$/', ''],
    ];

    private static $noPlurals = [
        'cod', 'deer', 'feedback', 'fish', 'moose', 'news', 'species', 'series', 'sheep', 'rice'
    ];

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
        if(in_array($text, self::$noPlurals)) {
            return $text;
        }
        foreach(self::$pluralRules as $rule) {
            if(preg_match($rule[0], $text, $matches)) {
                return substr($text, 0, strlen($text) - strlen($matches['remove'] ?? '')) . $rule[1];
            }
        }
    }
    
    /**
     * Generates the english singular of a given word.
     *
     * @param string $text
     * @return string
     */
    public static function singularize($text) : string
    {
        if(in_array($text, self::$noPlurals)) {
            return $text;
        }
        foreach(self::$singularRules as $rule) {
            if(preg_match($rule[0], $text, $matches)) {
                return substr($text, 0, strlen($text) - strlen($matches['remove'] ?? '')) . $rule[1];
            }
        }
        return $text;
    }
}
