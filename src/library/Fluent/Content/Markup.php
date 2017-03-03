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
        $element = new \DOMElement('paragraph');
        $this->_content->appendChild($element);
        $element->appendChild($this->_getCData($text));
        return $this;
    }
    
    /**
     * @param array $numbers Up to 3 number/caption pairs
     * @return \Fluent\Content\Markup
     */
    public function addNumber(array $numbers)
    {
        if (array_key_exists('value', $numbers)) {
            /* we have been given one number only */
            $numbers = array($numbers);
        }
        
        $parent = $this->_content
            ->appendChild(new \DOMElement('numbers'));

        foreach ($numbers as $number) {
            $element = $this->_getNumberElement(
                $parent->appendChild(new \DOMElement('number')), $number
            );
        }

        return $this;
    }

    protected function _getNumberElement($element, $number)
    {
        if (array_key_exists('value', $number)) {
            $element->appendChild(
                new \DOMElement('value', htmlentities($number['value']))
            );
        }

        if (array_key_exists('caption', $number)) {
            $element->appendChild(
                new \DOMElement('caption', $number['caption'])  
            );
        }

        return $element;
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
    public function addButton($href, $text)
    {
        $element = new \DOMElement('button', htmlentities($text));
        $this->_content->appendChild($element);
        $element->setAttribute('href', $href);
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
