<?php

use Jifno\quick;

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Jifno.php';

$messageId = Jifno::factory()
    ->setTitle('My little pony')
    ->addParagraph('I love my pony very much.')
    ->addCallout('http://www.mypony.com', 'Like my pony')
    ->send('everyone@internet.com', 'My little pony');