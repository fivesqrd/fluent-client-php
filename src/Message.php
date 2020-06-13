<?php
namespace Fluent;

/**
 *
 * @author cjb
 */
class Message
{
    protected $_defaults;

    public function __construct($defaults = array())
    {
        $this->_defaults = $defaults;
    }
    
    /**
     * @param mixed $content
     * @return \Fluent\Message\Create
     */
    public function create($content = null)
    {
        return new Message\Create($content, $this->_defaults);
    }

    /**
     * @param int $id
     * @return \Fluent\Message\Get
     */
    public function get($id)
    {
        return new Message\Get(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret']),
            $id
        );
    } 

    /**
     * @return \Fluent\Message\Find
     */
    public function find()
    {
        return new Message\Find(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret'])
        );
    } 
}
