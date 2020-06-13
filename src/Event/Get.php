<?php
namespace Fluent\Event;

class Get
{
    protected $_api;

    protected $_id;

    public function __construct($api, $id)
    {
        $this->_api = $api;
    }

    public function fetch()
    {
        return $this->_api->call('event', 'get', array('id' => $this->_id));
    }
}
