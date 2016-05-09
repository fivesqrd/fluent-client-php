<?php
namespace Fluent\Content;

class Markup
{
    protected $_title;
    
    protected $_teaser;
    
    protected $_content;

    public function __construct($content = null)
    {
        $xml = new \DOMDocument();
        if ($content) {
            $xml->loadXML($content);
        } else {
            $xml->appendChild(new \DOMElement('content'));
        }

        $this->_content = $xml->childNodes->item(0);
    }
    
    /**
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function setTitle($text)
    {
        $this->_content->appendChild(new \DOMElement('title', htmlentities($text)));
        return $this;
    }
    
    /**
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function addParagraph($text)
    {
        $paragraph = new \DOMElement('paragraph');
        $this->_content->appendChild($paragraph);
        $paragraph->appendChild($this->_getCData($text));
        return $this;
    }

    protected function _getCData($text)
    {
        return $this->_content->ownerDocument->createCDATASection($text);
    }
    
    /**
     * @param string $href
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function addCallout($href, $text)
    {
        $callout = new \DOMElement('callout', htmlentities($text));
        $this->_content->appendChild($callout);
        $callout->setAttribute('href', $href);
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
        $doc = $this->_content->ownerDocument;
        return $doc->saveXml();
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
        return $this->toString();
    }
}
