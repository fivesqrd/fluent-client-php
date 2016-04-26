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
        $response = Fluent::message($message['content'])
            ->from(json_decode($message['sender'], true))
            ->to(json_decode($message['recipient'], true))
            ->headers(json_decode($message['headers'], true))
            ->subject($message['subject'])
            ->attachments($storage->getAttachments($message['id']))
            ->send('remote');
        
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
