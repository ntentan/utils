ntentan/utils
=============
[![Build Status](https://travis-ci.com/ntentan/utils.svg)](https://travis-ci.com/ntentan/utils)
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
============
You can install this package through `ntentan\utils` on composer.

Text Manipulation
=================
Text manipulation routines in the utils package provides inflector (for 
pluralizing or singularizing text), and camel case conversion routines. These 
routines are mainly consumed by components that generate magic strings for 
class and method names. All routines are implemented as static functions in the 
in the `ntentan\utils\Text`.

## Inflector routines
The following snipet shows how the inflector routines in the `Text` class work.

````php
use ntentan\utils\Text;

print Text::singularize('names'); // Should output name
print Text::pluralize('pot'); // Should output pots
````

## Camel case manipulation
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

Working with directories
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

Similarly, deleting directories can be done by ...
````php
use ntentan\utils\Filesystem;
Filesystem::directory("/path/to/some/dir")->delete();
````  
Note that this also deletes all the contents of the directory including subdirectories,
which are recursively emptied.

Moving and copying files and directories
----------------------------------------
Continuing in a consistent fashion, copying directories work through ...

````php
use ntentan\utils\Filesystem;
Filesystem::directory("/path/to/some/dir")->moveTo("/path/to/new/dir");
````  
and with copying ...

````php
use ntentan\utils\Filesystem;
Filesystem::directory("/path/to/some/dir")->copyTo("/path/to/new/dir");
````  

If you care to use files instead, you can replace the call to `Filesystem::directory` with
a call to `Filesystem::file` and perform your operations as follows ...

````php
use ntentan\utils\Filesystem;
Filesystem::file("/path/to/some/file")->moveTo("/path/to/new/file");
````  

Checking file statuses and Dealing with errors
----------------------------------------------
Errors that occur during filesystem operations are reported through exceptions. Exceptions
are descriptively named such that a `ntentan\utils\exceptions\FileNotFoundException` is thrown
when a file is not found, a `ntentan\utils\exceptions\FileNotReadableException` is thrown
when trying to read a file that has no read permisions, and so on.

Permissions and statuses of files can be explicitly checked too, through methods in the
static `Filesystem` class. These aptly named methods such as `Filesystem::checkWriteable` and
`Filesystem::checkExists`, perform the correspondingly named checks and throw the appropriate
exceptions when these checks fail.


Validation
==========
You can validate the contents of arrays using the validation routines in the utils package. All validation is done
through the Validator class. For example, to check if some required fields are set in an array you can use:

````php
use ntentan\utils\Validator;

$validator = new Validator();
$validator->setRules(['required' => 'name', 'email']);
`````

This validator is now setup to check if the `name` and `email` fields of any arrays passed are set. Whenever we have
some data to check, we can call the `validate` method.

````php
$data = ['name' => 'Kofi', 'email' => 'kofi@example.com'];
$success = $validator->validate($data);
````

The validate method returns a boolean, which represents the validy of the array as far as the validation rules are 
concerned. In the case of our example above, `$success` will be `true`. In a case where success happens to be false, 
such as when a value is not provided for the `name` key in our example, `$success` will be `false`. To retrieve the
validation errors in that case, a call to the `getInvalidFields()` method of the validation object can be made. 

````php
$data = ['email' => 'kofi@example.com'];
if(!$validator->validate($data)) {
    print_r($validator->getInvalidFields());
}
````

The `getInvalidFields` method always returns the invalid fields found in the data as an associative array of the 
field names to a list of their errors. In the case above, the executed would provide the following output.

````
Array
(
    [name] => Array
        (
            [0] => The name field is required
        )

)
````

License
=======
Copyright (c) 2008-2020 James Ekow Abaka Ainooson

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
