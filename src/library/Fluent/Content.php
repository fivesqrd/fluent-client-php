<?php
namespace Fluent;

class Content
{
    protected $_title;
    
    /**
     * @param string $text
     * @return \Fluent\Content
     */
    public function setTitle($text)
    {
        $this->_title = '<title>' . $text . '</title>';
        return $this;
    }
    
    /**
     * @param string $text
     * @return \Fluent\Content
     */
    public function addParagraph($text)
    {
        $this->_content .= '<paragraph>' . $text .  '</paragraph>';
        return $this;
    }
    
    /**
     * @param string $href
     * @param string $text
     * @return \Fluent\Content
     */
    public function addCallout($href, $text)
    {
        $this->_content .= '<callout href="' . $href . '">' . $text . '</callout>';
        return $this;
    }
    
    /**
     * @return string
     */
    public function getMarkup()
    {
        return '<content>' . $this->_title . $this->_content . '</content>';
    }
    
    public function __toString()
    {
        return $this->getMarkup();
    }
}
