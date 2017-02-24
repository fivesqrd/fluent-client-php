<?php
namespace Fluent\Message;

/**
 *
 * @author cjb
 *
 * @method \Fluent\Message\Create setTitle(string $text)
 * @method \Fluent\Message\Create addParagraph(string $text)
 * @method \Fluent\Message\Create addButton(string $href, string $text)
 * @method \Fluent\Message\Create addNumber(string $value, string $caption)
 * @method \Fluent\Message\Create setRawContent(string $value)
 * @method \Fluent\Message\Create setTeaser(string $value)
 */

use Fluent\Content;
use Fluent\Transport;
use Fluent\Exception;

class Create
{
    /**
     * @var \Fluent\Content
     */
    protected $_content;
    
    protected $_sender = array('address' => null, 'name' => null);

    protected $_recipient = array('address' => null, 'name' => null);
    
    protected $_subject;
    
    protected $_options = array();

    protected $_headers = array();
    
    protected $_attachments = array();
    
    protected $_defaults = array();
    
    public function __construct($content, $defaults = array())
    {
        if ($content instanceof \Fluent\Template) {
            $this->_content = $content->getContent();
        } elseif ($content instanceof Content\Markup) {
            $this->_content = $content;
        } elseif ($content instanceof Content\Raw) {
            $this->_content = $content;
        } elseif ($content === null) {
            $this->_content = new Content\Markup();
        } elseif (is_string($content) && strstr($content, '<content>')) {
            $this->_content = new Content\Markup($content);
        } else {
            $this->_content = new Content\Raw($content);
        }

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
        return $this->_content->toString();
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
        } else {
            throw new Exception('Invalid method ' . $name . ' for ' . get_class($this->_content));
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

        switch (strtolower($transport)) {
            case 'local':
                $client = new Transport\Local($this->_defaults);
                break;
            default:
                $client = new Transport\Remote(
                    new \Fluent\Api($this->_getDefault('key'), $this->_getDefault('secret'))
                );
                break;
        }
        
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
     * @param string $content
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
     * @param array $values
     * @return \Fluent\Message
     */
    public function attachments(array $values)
    {
        foreach ($values as $attachment) {
            $this->attach($attachment['name'], $attachment['type'], $attachment['content']); 
        }
    
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
    public function option($name, $value)
    {
        $this->_options[$name] = $value;
        return $this;
    }

    public function options($values)
    {
        $this->_options = array_merge($this->_options, $values);
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return \Fluent\Message
     */
    public function header($name, $value)
    {
        $this->_headers[$name] = $value;
        return $this;
    }

    /**
     * 
     * @param array $values
     * @return \Fluent\Message
     */
    public function headers(array $values)
    {
        $this->_headers = array_merge($this->_headers, $values);
        return $this;
    }
    
    /**
     * @return array
     */
    public function getSender()
    {
        if (isset($this->_sender['address']) && !empty($this->_sender['address'])) {
            return array('address' => $this->_sender['address'], 'name' => $this->_sender['name']);
        }
        return $this->_getDefault('sender');
    }
    
    /**
     * @return \Fluent\Content
     */
    public function getContent()
    {
        return $this->_content;
    }
    
    public function getOptions()
    {
        $content = array(
            'format' => $this->_content->getFormat(),
            'teaser' => $this->_content->getTeaser()
        );
        
        return array_merge($this->_options, $content);
    }

    public function getHeaders()
    {
        if (!array_key_exists('headers', $this->_defaults) || !is_array($this->_defaults['headers'])) {
            return $this->_headers;
        }

        return array_merge($this->_defaults['headers'], $this->_headers);
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
            'content'     => $this->_content->toString(),
            'headers'     => $this->getHeaders(),
            'attachments' => $this->_attachments,
            'options'     => $this->getOptions(),
        );
    }
}
