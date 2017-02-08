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

//Fluent\Api::$endpoint = 'http://localhost/fluent-web-service/v3';
Fluent\Api::$endpoint = 'https://fluent.clickapp.co.za/v3';
Fluent\Api::$debug = true;

try {
    $messageId = Fluent::message()->create()
        ->setTitle('My little pony')
        ->addParagraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare pellentesque neque non rutrum. Sed a sagittis lacus.')
        ->addCallout('http://www.mypony.com', 'Like my pony')
        ->addParagraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.')
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
