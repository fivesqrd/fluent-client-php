<?php
namespace Jifno;

class Config
{
    /**
     * Default options
     */
    public static $defaults = array(
        'key'         => null,
        'name'        => null,
        'storage'     => 'Sqlite',
        'from'        => null,
        'logo'        => null,
        'color'       => null,
        'teaser'      => null,
        'footer'      => null,
        'path'        => null,
        'url'         => 'http://api.jifno.com/v1'
    );
}