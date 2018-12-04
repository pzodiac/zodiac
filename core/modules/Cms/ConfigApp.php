<?php
/**
 * @copyright Copyright (c) 2018 Phalart
 * @author Nhan Phong <nhanphong@vinadesign.vn>
 * User: nhanphong
 * Date: 6/4/18
 * Time: 10:51 AM
 */

namespace Cms;

class ConfigApp
{

    public static function get()
    {

        $config_default = [
            'loader'    => [
                'namespaces' => [
                    'PhalartCMS\Plugin' => LIBRARY_PATH . '/plugins/',
                    'Application'    => LIBRARY_PATH . '/modules/Application',
                    'Cms'            => LIBRARY_PATH . '/modules/Cms',
                    'FrontEnd'       => LIBRARY_PATH . '/modules/FrontEnd',
                    'Api'       => LIBRARY_PATH . '/modules/Api',
                ],
            ],
            'modules'   => [
                'cms' => [
                    'className' => 'Cms\Module',
                    'path'      => LIBRARY_PATH . '/modules/Cms/Module.php'
                ],
                'front-end' => [
                    'className' => 'FrontEnd\Module',
                    'path'      => LIBRARY_PATH . '/modules/FrontEnd/Module.php'
                ],
                'api' => [
                    'className' => 'Api\Module',
                    'path'      => LIBRARY_PATH . '/modules/Api/Module.php'
                ],
            ],
        ];

        $global = include_once APPLICATION_PATH . '/config/global.php';

        // Modules configuration list
        $modules_list = include_once APPLICATION_PATH . '/config/modules.php';
        require_once LIBRARY_PATH . '/modules/Application/Loader/Modules.php';
        $modules = new \Application\Loader\Modules();
        $modules_config = $modules->modulesConfigTheme($modules_list);
        
        $modules_list_lib = include_once LIBRARY_PATH . '/config/modules.php';
        $modules_config_lib = $modules->modulesConfigLib($modules_list_lib);
        $modules_config['loader']['namespaces'] = array_merge($modules_config['loader']['namespaces'], $modules_config_lib['loader']['namespaces']);
        $modules_config['modules'] = array_merge($modules_config['modules'], $modules_config_lib['modules']);
        $config = array_merge_recursive($config_default, $global, $modules_config);

        return new \Phalcon\Config($config);
    }

}
