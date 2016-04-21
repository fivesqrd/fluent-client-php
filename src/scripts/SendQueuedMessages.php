<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$path = dirname(__FILE__) . '/../temp';

$storage = Fluent\Storage\Sqlite::getInstance();
if ($storage->isLocked(getmypid())) {
    print "PID is still alive! can not run twice!\n";
    exit;
}

foreach ($storage->getQueue() as $message) {
    try {
        echo date("Y-m-d H:i:s") . " Sending '{$message['subject']}' to {$message['recipient']}\n";
        $object = Fluent::message()
            ->from(json_decode($message['sender'], true))
            ->to(json_decode($message['recipient'], true))
            ->subject($message['subject'])
            ->headers($message['headers'])
            ->content($message['content']);
        
        foreach ($storage->getAttachments($message['id']) as $attachment) {
            $object->attach($attachment['name'], $attachment['type'], $attachment['content']);
        }
        $response = $object->send('remote');
        
        if ($response) {
            $storage->moveToSent($message['id'], $response);
        }
    } catch (Exception $e) {
        $storage->moveToFailed($message['id'], $e->getMessage());
        echo $e->getMessage() . "\n";
    }
}

$result = $storage->purge(30);
if ($result) {
    echo "{$result} messages purged\n";
}
