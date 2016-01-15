<?php
$config['global'] = array(
    'DBSession',
    'Interceptors\Auth',
);
$config['default'] = array(
    'test'
);
$config['\Controllers\Index'] = array(
    '!Interceptors\Auth',
    'Interceptors\Log',
);