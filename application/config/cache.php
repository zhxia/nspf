<?php
$config['redis'] = array(
    'default' => array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 1.0,
        'pconnect' => false,
    ),
);

$config['memcache'] = array(
    'default' => array(
        array(
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 0,
        ),
        array(
            'host' => '127.0.0.1',
            'port' => 11212,
            'weight' => 0,
        )
    ),
);