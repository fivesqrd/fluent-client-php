<?php

use Jifno\Content;

use Jifno\Template;

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Jifno/Email.php';

class MyTemplate extends Jifno\Template
{
    const THEME = 'minimal';
    
    protected $_user;
    
    public function __construct($user)
    {
        /* setup for your content here */
        $this->_user = $user;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Jifno\Template::getContent()
     * @return \Jifno\Content
     */
    public function getContent()
    {
        $content = new \Jifno\Content(self::THEME);
        return $content->setTitle($text)
            ->addParagraph($text)
            ->addParagraph($text)
            ->addCallout($href, $text);
    }
}

$message = new Jifno\Message();
$messageId = $message->to($to)
    ->subject($subject)
    ->content(new MyTemplate($user))
    ->send();
