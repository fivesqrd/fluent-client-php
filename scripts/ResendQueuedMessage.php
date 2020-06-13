<?php
require_once(__DIR__ . '/bootstrap.php');

if (count($argv) < 2) {
    echo "Usage: {$argv[0]} <messageId> [address]\n";
    exit;
}

$storage = Fluent\Storage\Sqlite::getInstance();

$message = $storage->getMessage($argv[1]);

$recipient = isset($argv[2]) ? $argv[2] : json_decode($message['recipient'], true);

$response = Fluent::message($message['content'])
    ->from(json_decode($message['sender'], true))
    ->to($recipient)
    ->headers(json_decode($message['headers'], true))
    ->options(json_decode($message['options'], true))
    ->subject($message['subject'])
    ->attachments($storage->getAttachments($message['id']))
    ->send('remote');

echo date("Y-m-d H:i:s") . ": Delivered '{$message['subject']}' e-mail to " . $recipient . " - {$response}\n";

/*
if ($response) {
    $storage->moveToSent($message['id'], $response);
}
*/
