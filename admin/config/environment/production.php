<?php

return [
    'base_path' => '/',
    'base_uri' => '/',
    'database'  => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'mbn-nhadat',
        'password' => 'tLuActN3CnTSmKud',
        'dbname'   => 'mbn-nhadat',
        'charset'  => 'utf8',
    ],

    'memcache'  => [
        'host' => 'localhost',
        'port' => 11211,
    ],

    'memcached'  => [
        'host' => 'localhost',
        'port' => 11211,
    ],

    'cache'     => 'file', // memcache, memcached
];