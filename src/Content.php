<?php
namespace Fluent;

use Fluent\Content as Content;

class Content
{
    public static function markup($content = null)
    {
        return new Content\Markup($content);
    }

    public static function raw()
    {
        return new Content\Raw();
    }
}
