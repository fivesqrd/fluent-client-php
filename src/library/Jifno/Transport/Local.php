<?php
namespace Jifno\Transport;

class Local implements \Jifno\Transport
{
    protected $_debug;
    
    protected $_storage;
    
    public function __construct($storage = 'Sqlite')
    {
        $class = 'Jifno\\Storage\\' . $storage;
        $this->_storage = $class::getInstance();
    }
    
    public function send(\Jifno\Message $message)
    {
        return $this->_storage->persist($message);
    }
}
