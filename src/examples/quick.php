<?php

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Jifno.php';
require_once 'Jifno/Transport/Standard.php';

Jifno::setDefaults(array(
    'key'      => '12345',
    'sender'   => array('name' => 'ACME', 'address' => 'christian@photofrog.co.za'),
    'color'    => '#4986e7',
    'logo'     => 'Test Logo',
    'footer'   => 'ACME'
));

try {
    $messageId = Jifno::factory()
        ->setTitle('My little pony')
        ->addParagraph('I love my pony very much.')
        ->addCallout('http://www.mypony.com', 'Like my pony')
        ->send('christian@thinkopen.biz', 'My little pony', 'standard');
    echo 'Sent message: ' . $messageId . "\n";
} catch (Jifno\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}