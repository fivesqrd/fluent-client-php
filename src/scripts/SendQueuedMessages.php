<?php
require_once 'Jifno/Message.php';
require_once dirname(__FILE__) . 'bootstrap.php';

$path = dirname(__FILE__) . '/../temp';

$storage = Jifno\Storage\Db::getInstance();
if ($storage->isLocked(getmypid())) {
    print "PID is still alive! can not run twice!\n";
    exit;
}

$client = new Jifno\Client();

foreach ($storage->getQueue() as $message) {
    try {
        $object = (new Jifno\Message($message['profile']))
            ->from(json_decode($message['sender'], true))
            ->to(json_decode($message['recipient'], true))
            ->subject($message['subject'])
            ->content($message['content']);
        
        foreach ($storage->getAttachments($message['id']) as $attachment) {
            $object->attach($attachment['name'], $attachment['type'], $attachment['content']);
        }
        $response = $object->send($client);
        
        if ($response) {
            $storage->moveToSent($message['id'], $response);
        }
    } catch (Exception $e) {
        $storage->moveToFailed($message['id'], $e->getMessage());
        echo $e->getMessage() . "\n";
    }
}

$result = $storage->purge(30);
echo "{$result} messages purged\n";
