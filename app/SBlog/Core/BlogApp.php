<?php


namespace App\SBlog\Core;

/**
 * Class BlogApp
 * @package App\SBlog\Core
 */
class BlogApp
{
    /**
     * @var
     */
    public static $app;

    /**
     * @return TSingletone
     */
    public static function get_instance()
    {
        self::$app = Registry::instance();
        self::getParams();
        return self::$app;
    }

    /**
     *
     */
    protected static function getParams()
    {
        $params = require CONF . '/params.php';

        if (!empty($params)) {
            foreach ($params as $k => $v) {
                self::$app->setProperty($k, $v);
            }
        }
    }

}
