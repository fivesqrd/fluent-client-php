<?php
namespace Fluent\Transport;

class Local implements \Fluent\Transport
{
    protected $_debug;
    
    protected $_storage;
    
    public function __construct($storage = 'Sqlite')
    {
        $class = 'Fluent\\Storage\\' . $storage;
        $this->_storage = $class::getInstance();
    }
    
    public function send(\Fluent\Message $message)
    {
        return $this->_storage->persist($message);
    }
}
