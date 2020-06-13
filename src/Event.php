<?php
namespace Fluent;

class Event
{
    protected $_defaults = array();

    public function __construct($defaults)
    {
        $this->_defaults = $defaults;
    }

    /**
     * @param int $id
     * @return \Fluent\Event\Get
     */
    public function get($id)
    {
        return new Event\Get(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret']),
            $id
        );
    } 

    /**
     * @return \Fluent\Event\Find
     */
    public function find()
    {
        return new Event\Find(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret'])
        );
    } 
}
