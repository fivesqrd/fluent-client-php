<?php
namespace Fluent;

require_once 'Fluent/Transport/Remote.php';
require_once 'Fluent/Transport/Local.php';
require_once 'Fluent/Content.php';

class Message
{
    /**
     * @var \Fluent\Content
     */
    protected $_content;
    
    protected $_sender = array('address' => null, 'name' => null);

    protected $_recipient = array('address' => null, 'name' => null);
    
    protected $_subject;
    
    protected $_raw;
    
    protected $_options = array();
    
    protected $_attachments = array();
    
    protected $_defaults = array();
    
    public function __construct($content, $defaults = array())
    {
        $this->_content = $content;
        $this->_defaults = $defaults;
    }
    
    protected function _getDefault($name, $fallback = null)
    {
        if (array_key_exists($name, $this->_defaults)) {
            return $this->_defaults[$name];
        }
        
        return $fallback;
    }
    
    public function __toString()
    {
        return $this->_content->getMarkup();
    }
    
    /**
     * @param string $name
     * @param array $arguments
     * @throw \Fluent\Exception
     * @return Fluent
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->_content, $name)) {
            $object = $this->_content;
            $this->format('markup');
        } else {
            throw new \Fluent\Exception('Invalid method ' . $name);
        }
    
        call_user_func_array(array($object, $name), $arguments);
    
        return $this;
    }
    
    /**
     * @param string $transport
     */
    public function send($transport = null)
    {
        if ($transport === null) {
            $transport = $this->_getDefault('transport');
        }
        
        $class = '\\Fluent\\Transport\\' . $transport;
        if (!class_exists($class)) {
            throw new \Fluent\Exception ("{$transport} is not a valid transport");
        }
        
        $client = new $class($this->_getDefault('key'), $this->_getDefault('secret'));
        return $client->send($this);
    }
    
    
    /**
     * @return \Fluent\Message
     */
    public function subject($value)
    {
        $this->_subject = $value;
        return $this;
    }
    
    /**
     * @return \Fluent\Message
     */
    public function to($address, $name = null)
    {
        if (is_array($address)) {
            $this->_recipient = $address;
        } else {
            $this->_recipient = array(
                'address' => $address,
                'name'    => $name
            );
        }
        return $this;
    }
    
    /**
     * @param string $name
     * @param string $contentType
     * $param string $content
     * @return \Fluent\Message
     */
    public function attach($name, $type, $content)
    {
        array_push($this->_attachments, array(
            'name'      => $name,
            'type'      => $type,
            'content'   => base64_encode($content)
        ));
        return $this;
    }
    
    /**
     * @param string $address
     * @param string $name
     * @return \Fluent\Message
     */
    public function from($address, $name = null)
    {
        if (is_array($address)) {
            $this->_sender = $address;
        } else {
            $this->_sender = array(
                'address' => $address,
                'name'    => $name
            );
        }
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @return \Fluent\Message
     */
    public function setOption($name, $value)
    {
        $this->_options[$name] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $text
     * @return \Fluent\Message
     */
    public function teaser($text)
    {
        $this->setOption('teaser', $text);
        return $this;
    }
    
    /**
     * @param string $value raw content
     * @return \Fluent\Message
     */
    public function raw($value)
    {
        $this->_raw = $value;
        $this->setOption('format', 'raw');
        return $this;
    }
    
    public function getSender()
    {
        if (isset($this->_sender['address']) && !empty($this->_sender['address'])) {
            return array('address' => $this->_sender['address'], 'name' => $this->_sender['name']);
        }
        return $this->_getDefault('sender');
    }
    
    public function getContent()
    {
        if ($this->_raw !== null) {
            return $this->_raw;
        }
        
        return $this->_content->getMarkup();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sender'      => $this->getSender(),
            'subject'     => $this->_subject,
            'recipient'   => $this->_recipient,
            'content'     => $this->getContent(),
            'attachments' => $this->_attachments,
            'options'     => $this->_options,
        );
    }
}
