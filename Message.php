<?php
namespace Jifno;

require_once 'Jifno/Storage/Db.php';

class Message
{
    protected $_sender = array('address' => null, 'name' => null);

    protected $_recipient = array('address' => null, 'name' => null);
    
    protected $_subject;
    
    protected $_content;
    
    protected $_attachments = array();
    
    protected $_profile;
    
    /**
    * Default options
    */
    public static $defaults = array(
        'name'    => null, 
        'from'    => null, 
        'profile' => null
    );
    
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
            'content'   => $content
        ));
        return $this;
    }

    /**
     * @return \Jifno\Message
     */
    public function content($value)
    {
        if (is_object($value)) {
            $this->_content = $value->getContent();
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
            $this->_from = $address;
        } else {
            $this->_from = array(
                'address' => $address,
                'name'    => $name
            );
        }
        return $this;
    }

    /**
     * @return int
     */
    public function queue(Storage\Db $storage)
    {
        return $storage->persist($this);
    }
    
    /**
     * @return string $messageId
     */
    public function send(\Jifno\Client $client)
    {
        $params = array(
            'sender'      => $this->getSender(),
            'subject'     => $this->_subject,
            'recipients'  => array($this->_recipient),
            'content'     => $this->_content,
            'attachments' => $this->_attachments,
            'profile'     => ($this->_profile) ? $this->_profile : self::$defaults['profile']        
        );
        
        $response = $client->call('messages', 'create', json_encode($params));
        return $response['_id'];
    }
    
    public function getSender()
    {
        return array(
            'address' => ($this->_sender['address']) ? $this->_sender['address'] :  self::$defaults['from'], 
            'name'    => ($this->_sender['name']) ? $this->_sender['name'] : self::$defaults['name']
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
            'profile'     => $this->_profile        
        );
    }
}
?>
