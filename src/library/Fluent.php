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
     * @param array $defaults
     * @return \Fluent\Message
     */
    public static function message($defaults = null)
    {
        if ($defaults === null) {
            $defaults = self::$defaults;
        }
        
        return new \Fluent\Message($defaults);
    }
}
