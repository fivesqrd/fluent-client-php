<?php

use Jifno\advanced;

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Jifno/Email.php';

$message = new Jifno\advanced();
$messageId = $message->to('everyone@internet.com')
    ->subject('My little pony')
    ->content('<p>I love my pony very much.</p>')
    ->attach($name, $type, $content)
    ->attach($name, $type, $content)
    ->send();