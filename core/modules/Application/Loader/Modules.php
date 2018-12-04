<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Application\Loader;

use Phalcon\Text;

class Modules
{

    public function modulesConfigLib($modules_list)
    {
        $namespaces = array();
        $modules = array();

        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $namespaces['Lib\\' . $module] = LIBRARY_PATH . '/modules/' . $module;
                $simple = Text::uncamelize($module);
                $simple = str_replace('_', '-', $simple);
                //$simple .=  '-lib';
                $modules[$simple] = array(
                    'className' => $module . '\Module',
                    'path' => LIBRARY_PATH . '/modules/' . $module . '/Module.php'
                );
            }
        }
        $modules_array = array(
            'loader' => array(
                'namespaces' => $namespaces,
            ),
            'modules' => $modules,
        );

        return $modules_array;
    }

    public function modulesConfigTheme($modules_list)
    {
        $namespaces = array();
        $modules = array();

        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $namespaces[$module] = THEME_PATH . '/modules/' . $module;
                $simple = Text::uncamelize($module);
                $simple = str_replace('_', '-', $simple);
                $modules[$simple] = array(
                    'className' => $module . '\Module',
                    'path' => THEME_PATH . '/modules/' . $module . '/Module.php'
                );
            }
        }
        $modules_array = array(
            'loader' => array(
                'namespaces' => $namespaces,
            ),
            'modules' => $modules,
        );

        return $modules_array;
    }

    public function modulesConfig($modules_list)
    {
        $namespaces = array();
        $modules = array();

        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $namespaces[$module] = APPLICATION_PATH . '/modules/' . $module;
                $simple = Text::uncamelize($module);
                $simple = str_replace('_', '-', $simple);
                $modules[$simple] = array(
                    'className' => $module . '\Module',
                    'path' => APPLICATION_PATH . '/modules/' . $module . '/Module.php'
                );
            }
        }
        $modules_array = array(
            'loader' => array(
                'namespaces' => $namespaces,
            ),
            'modules' => $modules,
        );

        return $modules_array;
    }

} 