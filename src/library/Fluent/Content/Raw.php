<?php
namespace Fluent\Content;

class Raw
{
    protected $_content;

    public function __construct($content)
    {
        $this->_content = $content;
    }

    public function getFormat()
    {
        return 'raw';
    }

    public function getTeaser()
    {
        return null;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->_content;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
