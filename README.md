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
 - A Dependency Injector
 - A collection of file system utilities

Installation
------------
You can install this package through `ntentan\utils` on composer.

Text Manipulation
-----------------
Text manipulation routines in the utils package provides inflector (for 
pluralizing or singularizing text) and camel case conversion routines. These 
routines are mainly consumed by components that generate magic strings for 
class and method names. All routines are implemented as static functions in the 
in the `ntentan\utils\Text`.

### Inflector routines
The following snipet shows how the inflector routines in the `Text` class work.

````php
use ntentan\utils\Text;

print Text::singularize('names') // Should output name
print Text::pluralize('pot') // Should output pots
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
