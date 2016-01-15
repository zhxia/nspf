<?php
$config['global'] = array(
    'DBSession',
    'Auth',
);
$config['default'] = array(
    'test'
);
$config['\Controllers\Index'] = array(
    '!Auth',
    'Interceptors\Log',
);