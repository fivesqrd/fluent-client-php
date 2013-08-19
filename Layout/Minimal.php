<?php
namespace Jifno\Layout;

class Minimal
{
    /**
     * @param string $text
     * @return \Jifno\Template\Minimal
     */
    public function setTitle($text)
    {
        $this->_title = '<h2>' . $text . '</h2>';
        return $this;
    }
    
    /**
     * @param string $text
     * @return \Jifno\Template\Minimal
     */
    public function addParagraph($text)
    {
        $this->_content .= '<p>' . $text .  '</p>';
        return $this;
    }
    
    /**
     * @param string $href
     * @param string $text
     * @return \Jifno\Template\Minimal
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
        return '<html><body class="minimal">' . $this->_title . $this->_content . '</body></html>';
    }
    
    public function __toString()
    {
        return $this->getHtml();
    }
}