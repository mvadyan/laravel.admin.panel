<?php


namespace App\SBlog\Core;

/**
 * Class Registry
 * @package App\SBlog\Core
 */
class Registry
{
    use TSingletone;

    /**
     * @var array
     */
    protected static $properties = [];

    /**
     * @param $name
     * @param $value
     */
    public function setProperty($name, $value)
    {
        self::$properties[$name] = $value;
    }

    public function getProperty ($name)
    {
        if (isset(self::$properties[$name])) {
            return self::$properties[$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public static function getProperties(): array
    {
        return self::$properties;
    }

}
