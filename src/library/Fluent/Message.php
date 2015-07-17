<?php
namespace Fluent;

class Message
{
    protected $_defaults;
    
    public function __construct($defaults)
    {
        $this->_defaults = $defaults;    
    }
    
    /**
     * @param string $template
     * @return \Fluent\Message\Create
     */
    public function create($template = null)
    {
        if ($template instanceof \Fluent\Template) {
            $content = $template->getContent();
        } else {
            $content = new \Fluent\Content();
        }
        
        return new \Fluent\Message\Create($content, $this->_defaults);
    }
    
    public function get($messageId)
    {
        $client = new \Fluent\Client($this->_getDefault('key'), $this->_getDefault('secret'));
        return $client->call('message/' . $messageId, 'GET');
    }
    
    public function query()
    {
        
    } 
    
    public function resend($messageId)
    {
        
    }
    
    protected function _getDefault($name, $fallback = null)
    {
        if (array_key_exists($name, $this->_defaults)) {
            return $this->_defaults[$name];
        }
    
        return $fallback;
    }
}