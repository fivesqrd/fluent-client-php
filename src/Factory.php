<?php

namespace Fluent;

class Factory
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

    const VERSION = '4.0';
    
    /**
     * @param string $content
     * @param array $defaults
     * @return \Fluent\Message
     */
    public static function message(array $defaults = array())
    {
        return new Message(array_merge(self::$defaults, $defaults));
    }

    /**
     * @param array $defaults
     * @return \Fluent\Event
     */
    public static function event(array $defaults = array())
    {
        return new Event(array_merge(self::$defaults, $defaults));
    }
}
