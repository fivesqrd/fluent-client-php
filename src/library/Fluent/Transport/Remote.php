<?php
namespace Fluent\Transport;

class Remote implements \Fluent\Transport
{
    protected $_api;

    public function __construct($api)
    {
        $this->_api = $api;
    }
    
    public function send(\Fluent\Message\Create $message)
    {
        $properties = $message->toArray();
        $params = array(
            'sender'      => $properties['sender'],
            'subject'     => $properties['subject'],
            'recipient'   => $properties['recipient'],
            'content'     => $properties['content'],
            'header'      => $properties['headers'],
            'attachment'  => $properties['attachments'],
            'option'      => $properties['options'],
        );
        
        $response = $this->_api->call('message', 'create', $params);
        return $response->_id;
    }
    
}
