<?php
$config['auto_mapping'] = true;
$config['http404'] = 'Controllers\ErrorController';
$config['mappings']['\Controllers\IndexController'] = array(
    '^/$',
);