<?php
namespace Fluent;

interface Storage
{
    public function persist(\Fluent\Message\Create $email);
    
    public function delete($messageId);
    
    public function moveToSent($messageId, $reference);

    public function moveToFailed($messageId, $error);
    
    public function getQueue();
    
    public function purge($days);
}
