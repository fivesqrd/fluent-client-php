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
    public function title($text)
    {
        $this->_content->appendChild(new \DOMElement('title', htmlentities($text)));
        return $this;
    }
    
    /**
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function paragraph($text)
    {
        $element = new \DOMElement('paragraph');
        $this->_content->appendChild($element);
        $element->appendChild($this->_getCData($text));
        return $this;
    }

    /**
     * Add multiple paragraphs on one go
     * @param array[string] $paragraphs
     * @return \Fluent\Content\Markup
     */
    public function paragraphs(array $values)
    {
        foreach ($values as $text) {
            $this->paragraph($text);
        }

        return $this;
    }
    
    /**
     * @param array $numbers Up to 3 number/caption pairs
     * @return \Fluent\Content\Markup
     */
    public function number(array $numbers)
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
    
    /**
     * @param string $href
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function button($href, $text)
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

    public function teaser($text)
    {
        $this->_teaser = $text;
        return $this;
    }
    
    public function getTeaser()
    {
        return $this->_teaser;
    }

    /**
     * Only add paragraph if condition is true
     * @param bool $condition
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function paragraphWhen($condition, $text)
    {
        return $condition === true ? $this->paragraph($text) : $this;
    }

    /**
     * Only add paragraph if condition is true
     * @param bool $condition
     * @param array $paragraphs
     * @return \Fluent\Content\Markup
     */
    public function paragraphsWhen($condition, array $paragraphs)
    {
        return $condition === true ? $this->paragraphs($paragraphs) : $this;
    }

    /**
     * Only add number if condition is true
     * @param bool $condition
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function numberWhen($condition, array $numbers)
    {
        return $condition === true ? $this->number($numbers) : $this;
    }

    /**
     * Only add button if condition is true
     * @param bool $condition
     * @param string $href
     * @param string $text
     * @return \Fluent\Content\Markup
     */
    public function buttonWhen($condition, $href, $text)
    {
        return $condition === true ? $this->button($href, $text) : $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $doc = $this->_content->ownerDocument;
        return $doc->saveXml();
    }
    
    public function __toString()
    {
        return $this->toString();
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
}
