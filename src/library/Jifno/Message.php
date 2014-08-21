<?php
namespace Jifno;

class Message
{
    protected $_sender = array('address' => null, 'name' => null);

    protected $_recipient = array('address' => null, 'name' => null);
    
    protected $_subject;
    
    protected $_content;
    
    protected $_attachments = array();

    /**
     * @return \Jifno\Message
     */
    public function subject($value)
    {
        $this->_subject = $value;
        return $this;
    }
    
    /**
     * @return \Jifno\Message
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
     * @return \Jifno\Message
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
     * @return \Jifno\Message
     */
    public function content($value)
    {
        if ($value instanceof \Jifno\Content) {
            $this->_content = $value->getHtml();
        } elseif ($value instanceof \Jifno\Template) {
            $this->_content = $value->getContent()->getHtml();
        } else {
            $this->_content = $value;
        }
        return $this;
    }
    
    /**
     * @param string $address
     * @param string $name
     * @return \Jifno\Message
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
     * @param string $transport local or standard
     * @return string $messageId
     */
    public function send($transport = null)
    {
        $transport = \Jifno::getDefault('transport', ucfirst($transport));
        
        $class = '\\Jifno\\Transport\\' . $transport;
        if (!class_exists($class)) {
            throw new Exception ("{$transport} is not a valid transport");
        }
        
        $client = new $class;
        return $client->send($this);
    }
    
    public function getSender()
    {
        if (isset($this->_sender['address']) && !empty($this->_sender['address'])) {
            return array('address' => $this->_sender['address'], 'name' => $this->_sender['name']);
        }
        return \Jifno::getDefault('sender');
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
            'content'     => $this->_content,
            'html'        => true,
            'attachments' => $this->_attachments,
            //'profile'     => \Jifno::getDefault('profile', $this->_profile)        
        );
    }
}
?>
