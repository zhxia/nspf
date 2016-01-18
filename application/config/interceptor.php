<?php
$config['global'] = array(
    'Interceptors\AuthInterceptor',
);
$config['default'] = array(
    'Interceptors\DefaultInterceptor'
);
$config['\Controllers\IndexController'] = array(
    '!Interceptors\AuthInterceptor',
    'Interceptors\LogInterceptor',
);