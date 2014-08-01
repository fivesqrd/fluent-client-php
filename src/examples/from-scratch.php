<?php

use Jifno\Message;

use Jifno\Content;

use Jifno\Template;

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Jifno/Email.php';


$messageId = Jifno::factory(new MyTemplate())
    ->send('everyone@internet.com', 'My little pony');

$message = new Jifno\Message();

$message->to($to)
    ->subject($subject)
    ->content('<html><body>Build from scratch</body></html>')
    ->send();
