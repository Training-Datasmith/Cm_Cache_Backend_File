<?php

declare(strict_types=1);

/**
 * Example: Use Cm_Cache_Backend_File as a Zend_Cache backend.
 *
 * Requires Zend_Cache (e.g. via magento/zendframework1 or similar).
 */

// Cm_Cache_Backend_File does not use Composer autoloading in all setups.
require_once 'File.php';

$frontend = Zend_Cache::factory(
    'Core',
    new Cm_Cache_Backend_File([
        'cache_dir'              => sys_get_temp_dir() . '/cm_cache',
        'file_name_prefix'       => 'myapp',
        'hashed_directory_level' => 2,
        'file_locking'           => true,
    ]),
    [
        'lifetime'                => 3600,  // 1 hour TTL
        'automatic_serialization' => true,
    ]
);

$cacheId = 'my_cache_key';

if (($data = $frontend->load($cacheId)) === false) {
    // Cache miss — compute and store the data.
    $data = ['computed' => 'value', 'timestamp' => time()];
    $frontend->save($data, $cacheId, ['my_tag']);
    echo "Cache miss — data computed and stored." . PHP_EOL;
} else {
    echo "Cache hit — data retrieved from cache." . PHP_EOL;
}

var_dump($data);
