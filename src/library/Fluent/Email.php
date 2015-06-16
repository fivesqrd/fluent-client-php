<?php
namespace Fluent;

class Email implements \Fluent\Method
{
    /**
     * @var \Fluent\Email\Content
     */
    protected $_content;
    
    /**
     * @var \Fluent\Message
     */
    protected $_message;
    
    public function __construct($message, $content)
    {
        $this->_message = $message;
        $this->_content = $content;
    }
    
    public function __toString()
    {
        return $this->_content->getHtml();
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
        } elseif (method_exists($this->_message, $name)) {
            $object = $this->_message;
        } else {
            throw new \Fluent\Exception('Invalid method ' . $name);
        }
    
        call_user_func_array(array($object, $name), $arguments);
    
        return $this;
    }
    
    /**
     * @param string $to
     * @param string $subject
     * @param string $transport
     */
    public function send($to, $subject, $transport = null)
    {
        return $this->_message->to($to)
            ->subject($subject ? $subject : $this->_title)
            ->content($this->_content)
            ->send($transport);
    }
}
