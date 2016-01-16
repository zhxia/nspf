<?php
$config['dao_class'] = 'Spf\Core\Database\Pdo\DataAccess';
$config['master'] = array(
    'dsn' => 'mysql:host=192.168.137.120;dbname=test',
    'username' => 'zhxia',
    'password' => 'admin',
    'init_attributes' => array(),
    'init_statements' => array('SET CHARACTER SET utf8', 'SET NAMES utf8'),
    'default_fetch_mode' => PDO::FETCH_ASSOC
);
$config['slave'] = array(
    'dsn' => 'mysql:host=192.168.137.120;dbname=test',
    'username' => 'zhxia',
    'password' => 'admin',
    'init_attributes' => array(),
    'init_statements' => array('SET CHARACTER SET utf8', 'SET NAMES utf8'),
    'default_fetch_mode' => PDO::FETCH_ASSOC
);

$config[]=array(
    'driver_class'=>'',
    'host'=>'',
    'port'=>3306,
    'database'=>'',
    'user'=>'',
    'password'=>'',
);
