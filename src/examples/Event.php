
<?php

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Fluent.php';
require_once 'Fluent/Api.php';
require_once 'Fluent/Event.php';
require_once 'Fluent/Event/Find.php';
require_once 'Fluent/Content.php';
require_once 'Fluent/Message.php';
require_once 'Fluent/Exception.php';
require_once 'Fluent/Transport.php';
require_once 'Fluent/Transport/Remote.php';

Fluent::$defaults = array(
    'key'      => '9fe630283b5a62833b04023c20e43915',
    'secret'   => 'test',
    'sender'   => array('name' => 'ACME', 'address' => 'christian@clickscience.co.za'),
);

//Fluent\Api::$endpoint = 'http://localhost/fluent-web-service/v3';
Fluent\Api::$endpoint = 'https://fluent.clickapp.co.za/v3';
Fluent\Api::$debug = true;

try {
    $response = Fluent::event()->find()
        //->from('support@photofrog.co.za')
        ->type('send')
        ->fetch();
    print_r($response);
} catch (Fluent\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
