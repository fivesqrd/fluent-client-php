<?php
namespace Fluent;

use Fluent\Content as Content;

class Content
{
    public static function markup()
    {
        return Content\Markup();
    }

    public static function raw()
    {
        return Content\Raw();
    }
}
