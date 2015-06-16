<?php

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Fluent.php';
require_once 'Fluent/Transport/Standard.php';

Fluent::setDefaults(array(
    'key'      => '12345',
    'sender'   => array('name' => 'ACME', 'address' => 'christian@photofrog.co.za'),
    'color'    => '#4986e7',
    'logo'     => 'Test Logo',
    'footer'   => 'ACME'
));

try {
    $messageId = Fluent::factory()
        ->setTitle('My little pony')
        ->addParagraph('I love my pony very much.')
        ->addCallout('http://www.mypony.com', 'Like my pony')
        ->send('christian@thinkopen.biz', 'My little pony', 'standard');
    echo 'Sent message: ' . $messageId . "\n";
} catch (Fluent\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
