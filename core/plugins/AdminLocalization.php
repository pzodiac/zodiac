<?php

/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 12/04/18
 * Time: 15:24 PM
 */

namespace Zodiac\Plugin;

use Phalcon\Mvc\User\Plugin;

class AdminLocalization extends Plugin
{

    public function __construct($config)
    {
        $file = APPLICATION_PATH . '/../data/translations/admin/' . $config->admin_language . '.php';
        if (!is_file($file)) {
            die("file $file not exists");
        }
        $translations = include($file);
        $this->getDI()->set('admin_translate', new \Phalcon\Translate\Adapter\NativeArray(array('content' => $translations)));

    }

}
