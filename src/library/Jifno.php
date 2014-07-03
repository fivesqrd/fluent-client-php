<?php

use Jifno\Exception;

/**
 * 
 * @author cjb
 *
 * @method \Jifno setTitle(string $text)
 * @method \Jifno addParagraph(string $text)
 * @method \Jifno addCallout(string $href, string $text)
 * @method \Jifno attach(string $name, string $contentType, string $stream)
 */
class Jifno
{
    /**
     * @var \Jifno\Content
     */
    protected $_content;

    /**
     * @var \Jifno\Message
     */
    protected $_message;
    
    /**
     * @param string $theme
     * @return \Jifno
     */
    public static function factory($theme = 'minimal')
    {
        return new self(new \Jifno\Message(), new \Jifno\Content($theme));
    }
    
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
     * @throw \Jifno\Exception
     * @return Jifno
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->_content, $name)) {
            $object = $this->_content;
        } elseif (method_exists($this->_message, $name)) {
            $object = $this->_message;
        } else {
            throw new \Jifno\Exception('Invalid method ' . $name);
        }
        
        call_user_func_array(array($object, $name), $arguments);
        
        return $this;
    }
    
    /**
     * @param string $to
     * @param string $subject
     * @param string $transport
     */
    public function send($to, $subject, $transport = 'Standard')
    {
        return $this->_message->to($to)
            ->subject($subject ? $subject : $this->_title)
            ->content($this->_content)
            ->send($transport);
    }
}