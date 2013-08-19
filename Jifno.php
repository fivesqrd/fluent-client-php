<?php
use Jifno\Layout\Minimal;

require_once 'Jifno/Layout/Minimal.php';
require_once 'Jifno/Storage/Db.php';
require_once 'Jifno/Message.php';
require_once 'Jifno/Client.php';

class Jifno
{
    protected $_content;
    
    /**
     * @return \Jifno\Layout\Minimal
     */
    public function minimal()
    {
        $this->_content = new Minimal();
        return $this->_content;
    }
    
    /**
     * @param array|string $recipient
     * @param string $subject
     * @return number
     */
    public function queue($recipient, $subject, $path = null)
    {
        $message = new Jifno\Message();
        return $message->to($recipient)
            ->subject('Test Jifno Message')
            ->content($this->_content->getHtml())
            ->queue(Jifno\Storage\Db::getInstance($path));
    }
    
    /**
     * @param array|string $recipient
     * @param string $subject
     * @return string
     */
    public function send($recipient, $subject, $key = null)
    {
        $message = new Jifno\Message();
        return $message->to($recipient)
            ->subject('Test Jifno Message')
            ->content($this->_content->getHtml())
            ->send(new Jifno\Client($key));
    }
}