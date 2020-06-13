<?php

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Fluent.php';
require_once 'Fluent/Content.php';
require_once 'Fluent/Content/Markup.php';
require_once 'Fluent/Message.php';
require_once 'Fluent/Transport.php';
require_once 'Fluent/Transport/Remote.php';

$text = '<content><paragraph>Imported paragraph</paragraph></content>';

$content = Fluent\Content::markup($text)
    ->setTitle('Hello Earth & World')
    ->addParagraph('Hello Paragraph & All')
    ->addParagraph('<table border="1"><tr><td>Hello Paragraph</td><td>& All</td></tr></table>')
    ->addCallout('http://www.fluentmsg.com?test=1&another=2', 'Hello Callout & Href');

echo $content->toString();

Fluent::$defaults = array(
    'key'      => '9fe630283b5a62833b04023c20e43915',
    'secret'   => 'test',
    'sender'   => array('name' => 'ACME', 'address' => 'christian@clickscience.co.za'),
);

if (isset($argv[1])) {
    Fluent::message($content)
        ->to($argv[1])
        ->from('fluent@clickapp.co.za')
        ->subject('Fluent Content Test')
        ->send('remote');
}
