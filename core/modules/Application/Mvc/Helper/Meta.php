<?php

/**
 * Meta
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Helper;

class Meta extends \Phalcon\Mvc\User\Component
{

    private static $instance;
    private static $storage = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Meta();
        }
        return self::$instance;

    }

    public function set($name, $content)
    {
        self::$storage[$name] = $content;
    }

    public function get($name)
    {
        if (array_key_exists($name, self::$storage)) {
            $content = self::$storage[$name];
            $escaper = new \Phalcon\Escaper();
            return "<meta name=\"{$name}\" content=\"{$escaper->escapeHtml($content)}\">\n";
        }
    }

    public function setFb($name, $content)
    {
        self::$storage[$name] = $content;
    }

    public function getFb($name)
    {
        if (array_key_exists($name, self::$storage)) {
            $content = self::$storage[$name];
            $escaper = new \Phalcon\Escaper();
            return "<meta property=\"{$name}\" content=\"{$escaper->escapeHtml($content)}\">\n";
        }
    }

    public function getFavico($name)
    {
        return self::$storage[$name];
    }

    public function getName($name)
    {
        return self::$storage[$name];
    }
}
