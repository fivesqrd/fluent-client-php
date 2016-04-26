<?php
namespace Fluent\Transport;

class Local implements \Fluent\Transport
{
    protected $_debug;
    
    protected $_storage;
    
    public function __construct($defaults)
    {
        if (array_key_exists('storage', $defaults)) {
            $class = 'Fluent\\Storage\\' . ucfirst($defaults['storage']);
        } else {   
            $class = 'Fluent\\Storage\\Sqlite';
        }
        $this->_storage = $class::getInstance();
    }
    
    public function send(\Fluent\Message $message)
    {
        return $this->_storage->persist($message);
    }
}
