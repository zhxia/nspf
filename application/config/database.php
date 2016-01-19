<?php
$config['master'] = array(
    'adapter_class' => 'Spf\Database\Mysqli\DaoAdapter',
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'test',
    'user' => 'zhxia',
    'password' => 'admin',
    'init_sql' => array('SET CHARACTER SET utf8', 'SET NAMES utf8'),
//    'fetch_mode' => MYSQLI_ASSOC, //pdo: PDO::FETCH_ASSOC
);

$config['slave'] = array(
    'adapter_class' => 'Spf\Database\Mysqli\DaoAdapter',
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'test',
    'user' => 'zhxia',
    'password' => 'admin',
    'init_sql' => array('SET CHARACTER SET utf8', 'SET NAMES utf8'),
//    'fetch_mode' => MYSQLI_ASSOC, //pdo: PDO::FETCH_ASSOC
);
