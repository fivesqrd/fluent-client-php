<?php

use Fluent\Content;

use Fluent\Template;

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library'),
    get_include_path(),
)));

require_once 'Fluent/Email.php';

class MyTemplate extends Fluent\Template
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
     * @see \Fluent\Template::getContent()
     * @return \Fluent\Content
     */
    public function getContent()
    {
        $content = new \Fluent\Content(self::THEME);
        return $content->setTitle($text)
            ->addParagraph($text)
            ->addParagraph($text)
            ->addCallout($href, $text);
    }
}

$message = new Fluent\Message();
$messageId = $message->to($to)
    ->subject($subject)
    ->content(new MyTemplate($user))
    ->send();
