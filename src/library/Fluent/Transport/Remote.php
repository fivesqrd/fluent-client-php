<?php
namespace Fluent\Transport;

class Remote implements \Fluent\Transport
{
    /**
     * @var \Fluent\Client
     */
    protected $_client;
    
    public function __construct($client)
    {
        $this->_client = $client;
    }
    
    public function send(\Fluent\Message $message)
    {
        $properties = $message->toArray();
        $params = array(
            'sender'      => $properties['sender'],
            'subject'     => $properties['subject'],
            'recipient'   => $properties['recipient'],
            'content'     => $properties['content'],
            'attachment'  => $properties['attachments'],
            'option'      => $properties['options'],
        );
        
        $response = $this->_client->call('message', 'POST', $params, self::$debug);
        return $response['Id'];
    }
}
