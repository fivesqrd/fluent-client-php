<?php
namespace Fluent\Event;

class Find
{
    protected $_api;

    protected $_params = array();

    /**
     * @param \Fluent\Api $api
     * @return \Fluent\Event\Find
     */
    public function __construct($api)
    {
        $this->_api = $api;
    }

    /**
     * @param string $value
     * @return \Fluent\Event\Find
     */
    public function from($value)
    {
        $this->_params['recipient'] = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return \Fluent\Event\Find
     */
    public function to($value)
    {
        $this->_params['sender'] = $value;
        return $this;
    } 

    /**
     * @param string $value
     * @return \Fluent\Event\Find
     */
    public function since($value)
    {
        $this->_params['since'] = $value;
        return $this;
    }

    /**
     * @param array|string $value
     * @return \Fluent\Event\Find
     */
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
