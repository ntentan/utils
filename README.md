ntentan/utils
=============
[![Build Status](https://travis-ci.org/ntentan/utils.svg)](https://travis-ci.org/ntentan/utils)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ntentan/utils/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ntentan/utils/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ntentan/utils/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ntentan/utils/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/ntentan/utils/version.svg)](https://packagist.org/packages/ntentan/utils)
[![Total Downloads](https://poser.pugx.org/ntentan/utils/downloads.svg)](https://packagist.org/packages/ntentan/utils)

A collection of utility classes shared across the different ntentan packages.
`ntentan/utils` currently provides:
 - A Text class for string manipulation
 - An Input class for input filtering.
 - A Validator class for validating input.
 - A collection of file system utilities

Installation
------------
You can install this package through `ntentan\utils` on composer.

Text Manipulation
-----------------
Text manipulation routines in the utils package provides inflector (for 
pluralizing or singularizing text), and camel case conversion routines. These 
routines are mainly consumed by components that generate magic strings for 
class and method names. All routines are implemented as static functions in the 
in the `ntentan\utils\Text`.

### Inflector routines
The following snipet shows how the inflector routines in the `Text` class work.

````php
use ntentan\utils\Text;

print Text::singularize('names'); // Should output name
print Text::pluralize('pot'); // Should output pots
````

### Camel case manipulation
The following snipet shows how the camel case manipulation routines in the `Text`
class work.

````php
use ntentan\utils\Text;

print Text::camelize('home_alone'); // should output homeAlone
print Text::ucamelize('home_alone_again'); // should output HomeAloneAgain
print Text::deCamelize('HomeAloneStill'); // should output home_alone_still
````

It is worth noting that the camel case manipuation routines allow you to 
specify your own seperator as a second argument. For example:

````php
use ntentan\utils\Text;

print Text::camelize('home-alone', '-'); // should output HomeAlone
print Text::deCamelize('HomeAloneAgain', '-'); // should output home-alone-again

````

Filesystem
==========
The utils package provides file manipulation utilities that wrap around PHP's built in 
filesystem functions to provide an object oriented interface. Through this package, 
you can perform the following:

   - Create and delete directories with the option of recursively deleting all the directories content too.
   - Copy and move files and directories, also with the option of performing these recursively.
   - Read to and write from files.
   
While providing these features, the filesystem utilities rely on exceptions to inform
about filesystem errors whenever they take place. The routines for the filesystem utilites
can largely be accessed through a static facade, `ntentan\utils\Filesystem`, which provides
an interface through which most of the features of the filesystem package can be accessed.

Workint with directories
------------------------
To create a directory ...

````php
use ntentan\utils\Filesystem;
Filesystem::directory("/path/to/some/dir")->create();
````  

To create the entire hierarchy if it doesn't exist ...
````php
use ntentan\utils\Filesystem;
Filesystem::directory("/path/to/some/dir")->create(true);
```` 

License
=======
Copyright (c) 2008-2015 James Ekow Abaka Ainooson

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
