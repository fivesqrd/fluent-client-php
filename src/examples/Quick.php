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
    'sender'   => array('name' => 'ACME', 'address' => 'christian@clickscience.co.za'),
);

Fluent\Api::$endpoint = 'http://localhost/fluent-web-service/v3';
//Fluent\Api::$endpoint = 'https://fluent.clickapp.co.za/v3';
Fluent\Api::$debug = true;

try {
    $messageId = Fluent::message()->create()
        ->setTitle('My little pony')
        ->addParagraph('I love my pony very much.')
        ->addCallout('http://www.mypony.com', 'Like my pony')
        ->setTeaser('This is a teaser')
        ->subject('Testing it')
        ->header('Reply-To', 'christianjburger@me.com')
        ->to('christianjburger@gmail.com')
        //->send(\Fluent\Transport::LOCAL);
        ->send(\Fluent\Transport::REMOTE);
    echo 'Sent message: ' . $messageId . "\n";
} catch (Fluent\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
