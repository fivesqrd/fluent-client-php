<?php
namespace Fluent\Message;

class Find
{
    protected $_api;

    protected $_params = array();

    public function __construct($api)
    {
        $this->_api = $api;
    }

    public function from($value)
    {
        $this->_params['recipient'] = $value;
        return $this;
    }

    public function to($value)
    {
        $this->_params['sender'] = $value;
        return $this;
    } 

    public function since($value)
    {
        $this->_params['since'] = $value;
        return $this;
    }

    public function request($value)
    {
        $this->_params['request'] = $value;
        return $this;
    }

    public function fetch()
    {
        return $this->_api->call('message', 'index', $this->_params);
    }
}
