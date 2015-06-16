<?php
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    get_include_path(),
)));
require_once 'Fluent/Message.php';
require_once 'Fluent/Storage/Sqlite.php';
require_once 'Fluent/Transport/Standard.php';
require_once 'Fluent.php';


Fluent::setDefaults(array(
    'key'     => '9fe630283b5a62833b04023c20e43915',
    'from'    => 'christian@thinkopen.biz',
    'name'    => 'Fluent E-mailer',
));

Fluent\Storage\Sqlite::$path = dirname(__FILE__) . '/../temp';
Fluent\Transport\Standard::$url = 'http://localhost/jifno-api/v1';
