<?php
namespace Fluent\Email;

interface Template
{
    /**
     * @return \Fluent\Email\Content
     */
    public function getContent();
}
