<?php
/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 12/3/18
 * Time: 3:16 PM
 */

namespace Zodiac;
use Application\Cache\Manager as CacheManager;
use Cms\Model\Configuration as CmsConfiguration;
use \PhalartCMS\Plugin\CheckPoint as CheckPointPlugin;
use \PhalartCMS\Plugin\Localization as LocalizationPlugin;
use \PhalartCMS\Plugin\AdminLocalization as AdminLocalizationPlugin;
use \PhalartCMS\Plugin\Acl as AclPlugin;
use \PhalartCMS\Plugin\MobileDetect as MobileDetectPlugin;
use PhalartCMS\Plugin\Shortcode;
use \PhalartCMS\Plugin\Title as TitlePlugin;

if (APPLICATION_ENV == 'development') {
    date_default_timezone_set('Asia/Bangkok');
    ini_set('display_errors', true);
    error_reporting(E_ALL);
}
define('ROOT_APP', __DIR__);

class Bootstrap
{

    public function run()
    {
        $di = new \Phalcon\DI\FactoryDefault();

        $application = include_once APPLICATION_PATH . '/config/environment/' . APPLICATION_ENV . '.php';

        $config_default = [
            'base_path' => (isset($application['base_path'])) ? $application['base_path'] : null,
            'base_uri' => (isset($application['base_uri'])) ? $application['base_uri'] : null,
            'database'  => (isset($application['database'])) ? $application['database'] : null,
            'cache'     => (isset($application['cache'])) ? $application['cache'] : null,
            'memcache'  => (isset($application['memcache'])) ? $application['memcache'] : null,
            'memcached'  => (isset($application['memcached'])) ? $application['memcached'] : null,
            'assets'    => (isset($application['assets'])) ? $application['assets'] : null,
        ];
        $config = new \Phalcon\Config($config_default);

        // Database
        $db = new \Phalcon\Db\Adapter\Pdo\Mysql([
            "host"     => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname"   => $config->database->dbname,
            "charset"  => $config->database->charset,
        ]);
        $di->set('db', $db);

        // Config

        $result = $db->fetchOne(
            "SELECT * FROM `cms_configuration` WHERE `key` = 'THEME'"
        );
        $theme = $result['value'];
        var_dump($theme); die;
        define('THEME', $theme);
        define('THEME_PATH', __DIR__ . '/themes/' . $theme);
        require_once LIBRARY_PATH . '/modules/Cms/ConfigApp.php';
        $config2 = \Cms\ConfigApp::get();
        $config->merge($config2);
        $di->set('config', $config);

        // Registry
        $registry = new \Phalcon\Registry();
        $di->set('registry', $registry);

        // Loader
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces($config->loader->namespaces->toArray());
        $loader->registerDirs([LIBRARY_PATH . "/plugins/"]);
        $loader->registerFiles([APPLICATION_PATH . '/../vendor/autoload.php']);
        $loader->register();


        // Get Option setting
        $di->set('options', new \PhalartCMS\Plugin\Options());
        // Get Option setting

        // View
        $this->initView($di);
        $this->initSimpleView($di);

        // URL
        $url = new \Phalcon\Mvc\Url();
        $url->setBasePath($config->base_path);
        $url->setBaseUri($config->base_path);
        $di->set('url', $url);

        // Cache
        $this->initCache($di);

        // CMS
        $cmsModel = new CmsConfiguration();
        $registry->cms = $cmsModel->getConfig(); // Отправляем в Registry

        // Application
        $application = new \Phalcon\Mvc\Application();
        $application->registerModules($config->modules->toArray());

        // Events Manager, Dispatcher
        $this->initEventManager($di);

        // Session
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        $di->set('session', $session);

        $acl = new \Application\Acl\DefaultAcl();
        $di->set('acl', $acl);

        // JS Assets
        $this->initAssetsManager($di);

        // Flash helper
        $flash = new \Phalcon\Flash\Session([
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning',
        ]);
        $flash->setAutoescape(false);
        $di->set('flash', $flash);

        $di->set('helper', new \Application\Mvc\Helper());

        // Routing
        $this->initRouting($application, $di);

        $application->setDI($di);
        $di->setShared('shortcode', new Shortcode($application));

        // Main dispatching process
        $response = $this->dispatch($di);
        $response->send();

    }

    private function initRouting($application, $di)
    {
        $router = new \Application\Mvc\Router\DefaultRouter();
        $router->setDi($di);
        foreach ($application->getModules() as $module) {
            $routesClassName = str_replace('Module', 'Routes', $module['className']);
            if (class_exists($routesClassName)) {
                $routesClass = new $routesClassName();
                $router = $routesClass->init($router);
            }
            $initClassName = str_replace('Module', 'Init', $module['className']);
            if (class_exists($initClassName)) {
                new $initClassName();
            }
        }
        $di->set('router', $router);
    }

    private function initAssetsManager($di)
    {
        $config = $di->get('config');
        $assetsManager = new \Application\Assets\Manager();
        $js_collection = $assetsManager->collection('js')
            ->setLocal(true)
            ->addFilter(new \Phalcon\Assets\Filters\Jsmin())
            ->setTargetPath(ROOT . '/assets/js.js')
            ->setTargetUri('assets/js.js')
            ->join(true);
        if ($config->assets->js) {
            foreach ($config->assets->js as $js) {
                $js_collection->addJs(ROOT . '/' . $js);
            }
        }

        // Admin JS Assets
        $assetsManager->collection('modules-admin-js')
            ->setLocal(true)
            ->addFilter(new \Phalcon\Assets\Filters\Jsmin())
            ->setTargetPath(ROOT . '/assets/modules-admin.js')
            ->setTargetUri('assets/modules-admin.js')
            ->join(true);

        // Admin LESS Assets
        $assetsManager->collection('modules-admin-less')
            ->setLocal(true)
            ->addFilter(new \Application\Assets\Filter\Less())
            ->setTargetPath(ROOT . '/assets/modules-admin.less')
            ->setTargetUri('assets/modules-admin.less')
            ->join(true)
            ->addCss(APPLICATION_PATH . '/modules/Admin/assets/admin.less');

        $di->set('assets', $assetsManager);
    }

    private function initEventManager($di)
    {
        $eventsManager = new \Phalcon\Events\Manager();
        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher) use ($di) {
            new CheckPointPlugin($di->get('request'));
            new LocalizationPlugin($dispatcher);
            new AdminLocalizationPlugin($di->get('config'));
            new AclPlugin($di->get('acl'), $dispatcher, $di->get('view'));
            new MobileDetectPlugin($di->get('session'), $di->get('view'), $di->get('request'));
        });

        $eventsManager->attach("dispatch:afterDispatchLoop", function ($event, $dispatcher) use ($di) {
            new \Seo\Plugin\SeoManager($dispatcher, $di->get('request'), $di->get('router'), $di->get('view'));
            new TitlePlugin($di);
        });

        // Profiler
        $registry = $di->get('registry');
        if ($registry->cms['PROFILER']) {
            $profiler = new \Phalcon\Db\Profiler();
            $di->set('profiler', $profiler);

            $eventsManager->attach('db', function ($event, $db) use ($profiler) {
                if ($event->getType() == 'beforeQuery') {
                    $profiler->startProfile($db->getSQLStatement());
                }
                if ($event->getType() == 'afterQuery') {
                    $profiler->stopProfile();
                }
            });
        }

        $db = $di->get('db');
        $db->setEventsManager($eventsManager);

        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);
    }

    private function initView($di)
    {
        $view = new \Phalcon\Mvc\View();
        $theme = THEME;
        define('MAIN_VIEW_PATH', ROOT_APP . "/themes/{$theme}/views/");

        /*$view->setViewsDir(MAIN_VIEW_PATH);
        $view->setPartialsDir(MAIN_VIEW_PATH . "/partials/");
        $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);*/

        // Volt
        $volt = new \Application\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions([
            'compiledPath' => APPLICATION_PATH . '/../data/cache/volt/',
            'compileAlways'     => (APPLICATION_ENV == 'development'),
        ]);
        $volt->initCompiler();


        $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $di);
        $viewEngines = [
            ".volt"  => $volt,
            ".phtml" => $phtml,
        ];

        $view->registerEngines($viewEngines);

        $ajax = $di->get('request')->getQuery('_ajax');
        if ($ajax) {
            $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        }

        $di->set('view', $view);

        return $view;
    }

    private function initSimpleView($di)
    {
        $view = new \Phalcon\Mvc\View\Simple();

        // Volt
        $volt = new \Application\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions(['compiledPath' => APPLICATION_PATH . '/../data/cache/volt/']);
        $volt->initCompiler();

        $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $di);
        $viewEngines = [
            ".volt"  => $volt,
            ".phtml" => $phtml,
        ];

        $view->registerEngines($viewEngines);
        $di->set('simpleView', $view);

        return $view;
    }

    private function initCache($di)
    {
        $config = $di->get('config');

        $cacheFrontend = new \Phalcon\Cache\Frontend\Data([
            "lifetime" => 60,
            "prefix"   => HOST_HASH,
        ]);

        $cache = null;
        switch ($config->cache) {
            case 'file':
                $cache = new \Phalcon\Cache\Backend\File($cacheFrontend, [
                    "cacheDir" => APPLICATION_PATH . "/../data/cache/backend/"
                ]);
                break;
            case 'memcache':
                $cache = new \Phalcon\Cache\Backend\Memcache(
                    $cacheFrontend, [
                    "host" => $config->memcache->host,
                    "port" => $config->memcache->port,
                ]);
                break;
            case 'memcached':
                $cache = new \Phalcon\Cache\Backend\Libmemcached(
                    $cacheFrontend, [
                    "host" => $config->memcached->host,
                    "port" => $config->memcached->port,
                ]);
                break;
        }
        $di->set('cache', $cache, true);
        $di->set('modelsCache', $cache, true);

        \Application\Widget\Proxy::$cache = $cache; // Modules Widget System

        $modelsMetadata = new \Phalcon\Mvc\Model\Metadata\Memory();
        $di->set('modelsMetadata', $modelsMetadata);

        $di->set('cacheManager', new CacheManager());
    }

    private function dispatch($di)
    {
        $router = $di['router'];

        $router->handle();

        $view = $di['view'];

        $dispatcher = $di['dispatcher'];

        $response = $di['response'];

        $dispatcher->setModuleName($router->getModuleName());
        $dispatcher->setControllerName($router->getControllerName());
        $dispatcher->setActionName($router->getActionName());
        $dispatcher->setParams($router->getParams());

        $moduleName = \Application\Utils\ModuleName::camelize($router->getModuleName());

        $ModuleClassName = $moduleName . '\Module';

        if (class_exists($ModuleClassName)) {
            $module = new $ModuleClassName;
            $module->registerAutoloaders();
            $module->registerServices($di);
        }

        $view->start();

        $registry = $di['registry'];
        if ($registry->cms['DEBUG_MODE']) {
            $debug = new \Phalcon\Debug();
            $debug->listen();

            $dispatcher->dispatch();
        } else {
            try {
                $dispatcher->dispatch();
            } catch (\Phalcon\Exception $e) {
                // Errors catching

                $view->setViewsDir(__DIR__ . '/modules/Index/views/');
                $view->setPartialsDir('');
                $view->e = $e;

                if ($e instanceof \Phalcon\Mvc\Dispatcher\Exception) {
                    $response->setStatusCode(404, 'Not Found');
                    $view->partial('error/error404');
                } else {
                    $response->setStatusCode(503, 'Service Unavailable');
                    $view->partial('error/error503');
                }

                return $response;
            }
        }

        $view->render(
            $dispatcher->getControllerName(),
            $dispatcher->getActionName(),
            $dispatcher->getParams()
        );

        $view->finish();

        // AJAX
        $request = $di['request'];
        $_ajax = $request->getQuery('_ajax');
        if ($_ajax) {
            $contents = $view->getContent();

            $return = new \stdClass();
            $return->html = $contents;
            $return->title = $di->get('helper')->title()->get();
            $return->success = true;

            if ($view->bodyClass) {
                $return->bodyClass = $view->bodyClass;
            }

            $headers = $response->getHeaders()->toArray();
            if (isset($headers[404]) || isset($headers[503])) {
                $return->success = false;
            }
            $response->setContentType('application/json', 'UTF-8');
            $response->setContent(json_encode($return));
        } else {
            $response->setContent($view->getContent());
        }

        return $response;
    }

}
