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
    public static function message($template = null, array $defaults = array())
    {
        if ($template instanceof \Fluent\Template) {
            $content = $template->getContent();
        } else {
            $content = new \Fluent\Content();
        }
        
        return new \Fluent\Message($content, array_merge(self::$defaults, $defaults));
    }
}
