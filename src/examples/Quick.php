<?php

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Fluent.php';
require_once 'Fluent/Api.php';
require_once 'Fluent/Exception.php';
require_once 'Fluent/Content.php';
require_once 'Fluent/Content/Markup.php';
require_once 'Fluent/Message.php';
require_once 'Fluent/Message/Create.php';
require_once 'Fluent/Transport.php';
require_once 'Fluent/Transport/Remote.php';
require_once 'Fluent/Transport/Local.php';
require_once 'Fluent/Storage.php';
require_once 'Fluent/Storage/Sqlite.php';

Fluent::$defaults = array(
    'key'      => '9fe630283b5a62833b04023c20e43915',
    'secret'   => 'test',
    'sender'   => array('name' => 'The Acme Company', 'address' => 'christian@clickscience.co.za'),
    'endpoint' => 'http://localhost/fluent/service/v3',
    //'endpoint' => 'https://fluent.clickapp.co.za/v3',
    'debug'    => true
);

$numbers = array(
    ['value' => '$95.00', 'caption' => 'Billed'], 
    ['value' => '$95.00', 'caption' => 'Paid'],
    ['value' => '$0.00', 'caption' => 'Balance']
);

try {
    $messageId = Fluent::message()->create()
        ->addParagraph('We have just processed your monthly payment for Musixmatch monthly subscription (10 Feb - 9 Mar).')
        ->addNumber($numbers)
        ->addButton('http://www.myinvoices.com', 'Download Invoice')
        ->addParagraph('Please note the transaction will reflect on your statement as <b>"Musixmatch"</b>. Please <a href="#">contact us</a> if you have any questions about this receipt or your account.')
        ->setTeaser('This is a test receipt teaser.')
        ->setTitle('Receipt')
        ->subject('Test E-mail Receipt')
        ->header('Reply-To', 'christianjburger@me.com')
        ->to('christianjburger@gmail.com')
        //->send(\Fluent\Transport::LOCAL);
        ->send(\Fluent\Transport::REMOTE);
    echo 'Sent message: ' . $messageId . "\n";
} catch (Fluent\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
