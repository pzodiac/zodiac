<?php
/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 12/4/18
 * Time: 11:13 AM
 */

namespace Cms;

class Config
{

    public static function get()
    {
        $config_default = [
            'loader'    => [
                'namespaces' => [
                    'Zodiac\Plugin' => CORE_PATH . '/plugins/',
                    'Application'    => CORE_PATH . '/modules/Application',
                    'Cms'            => CORE_PATH . '/modules/Cms',
                ],
            ],
            'modules'   => [
                'cms' => [
                    'className' => 'Cms\Module',
                    'path'      => CORE_PATH . '/modules/Cms/Module.php'
                ],
            ],
        ];

        $global = include_once APPLICATION_PATH . '/config/global.php';

        // Modules configuration list
        $modules_list = include_once APPLICATION_PATH . '/config/modules.php';

        require_once CORE_PATH . '/modules/Application/Loader/Modules.php';
        $modules = new \Application\Loader\Modules();
        $modules_config = $modules->modulesConfig($modules_list);
        $modules_list_lib = include_once CORE_PATH . '/config/modules.php';
        $modules_config_lib = $modules->modulesConfigLib($modules_list_lib);
        $modules_config['loader']['namespaces'] = array_merge($modules_config['loader']['namespaces'], $modules_config_lib['loader']['namespaces']);
        $modules_config['modules'] = array_merge($modules_config['modules'], $modules_config_lib['modules']);
        $config = array_merge_recursive($config_default, $global, $modules_config);

        return new \Phalcon\Config($config);
    }

}
