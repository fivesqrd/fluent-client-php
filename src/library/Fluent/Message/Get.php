<?php
namespace Fluent\Message;

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
        return $this->_api->call('message', 'get', array('id' => $this->_id));
    }
}
