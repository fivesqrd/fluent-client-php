<?php


class Fluent
{
    public static $defaults = array(
        'key'       => null,
        'secret'    => null,
        'sender'    => null,
        'headers'   => null,
        'format'    => 'markup',
        'transport' => 'remote',
        'storage'   => 'sqlite'
    );
    
    /**
     * @param string $content
     * @param array $defaults
     * @return \Fluent\Message
     */
    public static function message($content = null, array $defaults = array())
    {
        return new \Fluent\Message($content, array_merge(self::$defaults, $defaults));
    }
}
