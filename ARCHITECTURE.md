# Cm_Cache_Backend_File Architecture

## Purpose

A high-performance drop-in replacement for Zend Framework's `Zend_Cache_Backend_File`
that uses a more scalable directory structure and improved file locking.
Designed primarily for Magento 1 but usable in any Zend_Cache context.

## Directory Structure

```
File.php            — the single class (extends Zend_Cache_Backend_File)
tests/
  CommonBackend.php           — shared backend test traits
  CommonExtendedBackend.php   — shared extended-backend test traits
  FileBackendTest.php         — PHPUnit test suite
```

## Key Design Decisions

- **Hashed directory layout**: cache entries are spread across a two-level
  directory tree derived from the cache ID hash, reducing filesystem directory
  entry counts and improving performance at scale.
- **Separate tag directory**: tags are stored in their own subdirectory
  (`file_name_prefix + '_tags'`) alongside cache data, allowing fast tag-based
  invalidation without scanning all cache files.
- **No chmod by default**: `use_chmod` defaults to `false`; permissions are
  controlled by the process `umask()` to avoid the overhead of `chmod()` on
  every file write.
- **File locking**: `flock()`-based locking is enabled by default
  (`file_locking = true`) to prevent race conditions under concurrent requests.

## Extension Points

- Override `_options` array at construction to customise directory level,
  file/directory permissions, and the filename prefix.
- Pass a custom `cache_dir` option to control where files are stored.

## Dependency Flow

```
Consumer (Zend_Cache_Backend interface)
  └── Cm_Cache_Backend_File extends Zend_Cache_Backend_File
        └── Filesystem (PHP fopen / flock / unlink / glob)
```
