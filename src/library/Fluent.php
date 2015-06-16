<?php

/**
 * 
 * @author cjb
 *
 * @method \Fluent setTitle(string $text)
 * @method \Fluent addParagraph(string $text)
 * @method \Fluent addCallout(string $href, string $text)
 * @method \Fluent attach(string $name, string $contentType, string $stream)
 */
class Fluent
{
    /**
     * Default options
     */
    public static $defaults = array(
        'key'             => null,
        'sender'          => array('name' => null, 'address' => null),
        'theme'           => 'clean',
        'logo'            => null,
        'color'           => null,
        'teaser'          => null,
        'footer'          => null,
        'transport'       => 'Standard',
        'storage'         => 'Sqlite',
    );
    
    public static function setDefaults($values)
    {
        foreach ($values as $key => $value) {
            self::$defaults[$key] = $value;
        }
    }
    
    /**
     * @param string $theme
     * @return \Fluent\Method\Email
     */
    public static function email($template = null)
    {
        if ($template instanceof \Fluent\Email\Template) {
            $content = $template->getContent();
        } else {
            $content = new \Fluent\Email\Content(self::$defaults['theme']);
        }
        
        return new self(new \Fluent\Email(), $content);
    }
    
    public static function getDefault($key, $value = null)
    {
        if (!empty($value)) {
            return $value;
        }
        
        return self::$defaults[$key];
    }

}
