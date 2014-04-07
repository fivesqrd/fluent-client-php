<?php
namespace Jifno;

use Jifno\Storage\Db;

require_once 'Jifno/Storage/Db.php';

class Message
{
    protected $_sender = array('address' => null, 'name' => null);

    protected $_recipient = array('address' => null, 'name' => null);
    
    protected $_subject;
    
    protected $_content;
    
    protected $_attachments = array();
    
    protected $_profile;
    
    public function __construct($profile = null)
    {
        $this->_profile = $profile;
    }

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
     * @return int
     */
    public function queue($storage = null)
    {
        if (!$storage) {
            $storage = Storage\Db::getInstance();
        }
        return $storage->persist($this);
    }
    
    /**
     * @param object $client
     * @return string $messageId
     */
    public function send($client = null)
    {
        if (!$client) {
            $client = new Client();
        }
        
        return $client->send($this);
    }
    
    public function getSender()
    {
        return array(
            'address' => ($this->_sender['address']) ? $this->_sender['address'] :  Config::$defaults['from'], 
            'name'    => ($this->_sender['name']) ? $this->_sender['name'] : Config::$defaults['name']
        );
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
            'profile'     => ($this->_profile) ? $this->_profile : self::$defaults['profile']        
        );
    }
}
?>
