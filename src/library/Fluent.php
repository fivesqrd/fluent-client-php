<?php


class Fluent
{
    public static $defaults = array(
        'key'       => null,
        'secret'    => null,
        'sender'    => null,
        'format'    => 'markup',
        'transport' => 'remote'
    );
    
    /**
     * @param string $content
     * @param array $defaults
     * @return \Fluent\Message
     */
    public static function message($content, array $defaults = array())
    {
        return new \Fluent\Message($content, array_merge(self::$defaults, $defaults));
    }
}
