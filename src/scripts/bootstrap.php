<?php
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    get_include_path(),
)));
require_once 'Jifno/Message.php';
require_once 'Jifno/Client.php';
require_once 'Jifno/Storage/Db.php';

Jifno\Message::$defaults = array(
    'from'    => 'christian@thinkopen.biz',
    'name'    => 'Jifno E-mailer',
    'profile' => 'd6c1ab4bc2fa3677f939a3578932dad8'
);

Jifno\Client::$key = '9fe630283b5a62833b04023c20e43915';

Jifno\Storage\Db::$path = dirname(__FILE__) . '/../temp';
