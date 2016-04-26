<?php
namespace Fluent\Content;

class Markup
{
    protected $_title;
    
    protected $_teaser;
    
    protected $_content;

    public function __construct($content = null)
    {
        $this->_content = $content; //todo: check and strip content and title tags
    }
    
    /**
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function setTitle($text)
    {
        $this->_title = '<title>' . $text . '</title>';
        return $this;
    }
    
    /**
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function addParagraph($text)
    {
        $this->_content .= '<paragraph>' . $text .  '</paragraph>';
        return $this;
    }
    
    /**
     * @param string $href
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function addCallout($href, $text)
    {
        $this->_content .= '<callout href="' . $href . '">' . $text . '</callout>';
        return $this;
    }

    public function getFormat()
    {
        return 'markup';
    }

    /**
     * @return string
     */
    public function toString()
    {
        if (substr($this->_content, 0, 9) == '<content>') {
            return $this->_content;
        }

        return '<content>' . $this->_title . $this->_content . '</content>';
    }

    public function setTeaser($text)
    {
        $this->_teaser = $text;
        return $this;
    }
    
    public function getTeaser()
    {
        return $this->_teaser;
    }
    
    public function __toString()
    {
        return $this->getString();
    }
}
