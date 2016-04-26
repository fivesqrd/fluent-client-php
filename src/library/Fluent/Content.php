<?php
namespace Fluent;

use Fluent\Content as Content;

class Content
{
    public static function markup()
    {
        return new Content\Markup();
    }

    public static function raw()
    {
        return new Content\Raw();
    }
}
