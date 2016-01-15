<?php
$config['master'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=test',
    'username' => 'root',
    'password' => '',
    'init_attributes' => array(),
    'init_statements' => array('SET CHARACTER SET utf8', 'SET NAMES utf8'),
    'default_fetch_mode' => PDO::FETCH_ASSOC
);
$config['slave'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=test',
    'username' => 'root',
    'password' => '',
    'init_attributes' => array(),
    'init_statements' => array('SET CHARACTER SET utf8', 'SET NAMES utf8'),
    'default_fetch_mode' => PDO::FETCH_ASSOC
);
