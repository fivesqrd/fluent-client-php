<?php

use Fluent\Message;

use Fluent\Content;

use Fluent\Template;

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Fluent/Email.php';


$messageId = Fluent::factory(new MyTemplate())
    ->send('everyone@internet.com', 'My little pony');

$message = new Fluent\Message();

$message->to($to)
    ->subject($subject)
    ->content('<html><body>Build from scratch</body></html>')
    ->send();
