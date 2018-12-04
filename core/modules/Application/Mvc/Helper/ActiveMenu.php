<?php

/**
 * ActiveMenu
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Helper;

class ActiveMenu extends \Phalcon\Mvc\User\Component
{

    private static $instance;
    private $active = null;
    private $active_parent = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ActiveMenu();
        }
        return self::$instance;
    }

    public function setActive($value)
    {
        $this->active = $value;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function isActive($value)
    {
        if ($this->active == $value) {
            return true;
        }
    }

    public function activeClass($value)
    {
        if ($this->isActive($value)) {
            return ' active open';
        }
    }

    public function setActiveParent($value)
    {
        $this->active_parent = $value;
    }

    public function isActiveParent($value)
    {
        if ($this->active_parent == $value) {
            return true;
        }
    }

    public function activeParentClass($value)
    {
        if ($this->isActiveParent($value)) {
            return ' active open';
        }
    }

}
