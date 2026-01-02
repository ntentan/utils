CHANGELOG
==========
## v0.13.1 - 2026-01-01
### Fixed
- Fixed a warning in the `Input` and `Filesystem` classes when running on PHP 8.3 and above.


## v0.13.0 - 2024-12-13
### Added
- Type hints in some required places

### Removed
- The Input::server method since it was practically unnecessary.

## v0.12.0 - 2022-12-21
### Added
- A method to allow deleting files only when they exist.

### Fixed
- Updated deprecated methods for PHP iterators and array access in the Filesystem component.

## v0.11.2 - 2021-01-08
### Fixed
- Removed the faulty `INPUT_REQUEST` constant.

### Removed
- Removed unused input decoder code for accepting dots in header names.


## v0.11.1 - 2020-11-05
### Fixed
- A bug in the `Filesystem` class that throws out a php warning whenever files are checked for existence.
- Recursively checking the parent of a directory when creating a directory from a full path.

## v0.11.0 - 2020-11-25
### Added
- A mechanism was added to allow the use custom decoders for requests.
- Custom messages can now be supplied for exceptions that are thrown within the file system libraries.

### Fixed
- A bug in the plurals library that causes every word ending in 'o' to be mapped out to a plural ending in 'es' was fixed.

### Removed
- The custom request decoder was removed to improve performance in cases where huge requests are sent in.

## v0.10.0 - 2020-02-25
### Added
- New methods to the `Directory` class for creating directories that don't exist.
- Overwite parameters to copy and move operations. You can now either overwrite duplicates, skip duplicates, or optionally overwrite older files when duplicates exist.

### Fixed
- Double encoding of url parameters in the `Input` class.

## v0.9.1 - 2019-11-17
### Added
- Updates to unit testing framework, and its associated dependencies.

### Fixed
- Inflection rules for plurals ending in `ion`.
- Unit tests to match with new unit testing framework.


## v0.9.0 - 2019-06-24
### Added
- A method to resolve a relative path regardless of whether the file exists or not.

### Fixed
- Recursive directories can now be created without repetition of the last directory.

## v0.8.1 - 2019-06-03
### Fixed
- `Directory` class can now resolve the current working directory when running through a PHAR archive.


## v0.8.0 - 2019-05-27

First release with a Changelog
