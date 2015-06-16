<?php
namespace Fluent;

interface Storage
{
    public function persist(\Fluent\Message $email);
    
    public function delete($messageId);
    
    public function moveToSent($messageId, $reference);

    public function moveToFailed($messageId, $error);
    
    public function getQueue();
    
    public function purge($days);
}
