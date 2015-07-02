<?php
namespace Fluent;

class Content
{
    protected $_title;
    
    protected $_teaser;
    
    protected $_raw = false;
    
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
    
    public function setRawContent($value)
    {
        $this->_raw = true;
        $this->_content = $value;
    }
    
    /**
     * @return string
     */
    public function getMarkup()
    {
        if ($this->_raw === true) {
            return $this->_content;
        }
        
        return '<content>' . $this->_title . $this->_content . '</content>';
    }
    
    public function getFormat()
    {
        return ($this->_raw === true) ? 'raw' : 'markup';
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
        return $this->getMarkup();
    }
}
