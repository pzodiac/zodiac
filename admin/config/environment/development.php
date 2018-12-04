<?php

return [
    'base_path' => '/',
    'base_uri' => '/',
    'database'  => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'phalart',
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