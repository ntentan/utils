CHANGELOG
==========

## Unreleased
### Added
- New methods to the `Directory` class for creating directories that don't exist.
- Overwite parameters to copy and move operations. You can now either overwrite, skip or optionally overwrite older files.

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