<?php
namespace Fluent\Event;

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

    public function type($value)
    {
        $this->_params['type'] = $value;
        return $this;
    }

    public function fetch()
    {
        return $this->_api->call('event', 'index', $this->_params);
    }
}
