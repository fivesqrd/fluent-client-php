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

    const VERSION = '3.3';
    
    /**
     * @param string $content
     * @param array $defaults
     * @return \Fluent\Message
     */
    public static function message(array $defaults = array())
    {
        return new \Fluent\Message(array_merge(self::$defaults, $defaults));
    }

    /**
     * @param array $defaults
     * @return \Fluent\Event
     */
    public static function event(array $defaults = array())
    {
        return new \Fluent\Event(array_merge(self::$defaults, $defaults));
    }
}
