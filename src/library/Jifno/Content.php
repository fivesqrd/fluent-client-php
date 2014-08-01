<?php
namespace Jifno;

class Content
{
    protected $_theme = 'minimal';
    
    protected $_title;
    
    /**
     * @param string $layout
     */
    public function __construct($theme = null)
    {
        if (!empty($theme)) {
            $this->_theme = $theme;
        }
    }
    
    /**
     * @param string $text
     * @return \Jifno\Content
     */
    public function setTitle($text)
    {
        $this->_title = '<h2>' . $text . '</h2>';
        $this->_title = $text;
        return $this;
    }
    
    /**
     * @param string $text
     * @return \Jifno\Content
     */
    public function addParagraph($text)
    {
        $this->_content .= '<div class="paragraph">' . $text .  '</div>';
        return $this;
    }
    
    /**
     * @param string $href
     * @param string $text
     * @return \Jifno\Content
     */
    public function addCallout($href, $text)
    {
        $this->_content .= '<div class="callout"><a href="' . $href . '">' . $text . '</a></div>';
        return $this;
    }
    
    /**
     * @return string
     */
    public function getHtml()
    {
        return '<html><body class="' . $this->_theme . '">' . $this->_title . $this->_content . '</body></html>';
    }
    
    public function __toString()
    {
        return $this->getHtml();
    }
}