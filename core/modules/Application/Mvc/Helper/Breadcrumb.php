<?php

/**
 * Breadcrumbs
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Helper;

class Breadcrumb extends \Phalcon\Mvc\User\Component
{

    private static $instance;

    public static function getInstance($breadcrumb = null, $status = false)
    {
        if (!self::$instance) {
            self::$instance = new Breadcrumb();
        }
        if ($breadcrumb) {
            if ($status) {
                self::$instance->getDi()->get('view')->setVar('breadcrumb', $breadcrumb);
            }
        }
        return self::$instance;
    }
}
