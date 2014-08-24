<?php
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    get_include_path(),
)));
require_once 'Jifno/Message.php';
require_once 'Jifno/Storage/Sqlite.php';
require_once 'Jifno/Transport/Standard.php';
require_once 'Jifno.php';


Jifno::setDefaults(array(
    'key'     => '9fe630283b5a62833b04023c20e43915',
    'from'    => 'christian@thinkopen.biz',
    'name'    => 'Jifno E-mailer',
));

Jifno\Storage\Sqlite::$path = dirname(__FILE__) . '/../temp';
Jifno\Transport\Standard::$url = 'http://localhost/jifno-api/v1';
