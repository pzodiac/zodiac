<?php

/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 12/04/18
 * Time: 14:33 PM
 */

namespace Zodiac\Plugin;

use Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\View,
    Application\Acl\DefaultAcl;

class Acl extends Plugin
{

    public function __construct(DefaultAcl $acl, Dispatcher $dispatcher, View $view)
    {
        $role = $this->getRole();

        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        // var_dump([$module, $controller, $action]); die;

        $resourceKey = $module . '/' . $controller;
        $resourceVal = $action;

        if ($acl->isResource($resourceKey)) {
            if (!$acl->isAllowed($role, $resourceKey, $resourceVal)) {
                $this->accessDenied($role, $resourceKey, $resourceVal, $view);
            }
        } else {
            $this->resourceNotFound($resourceKey, $dispatcher, $view);
        }

    }

    private function getRole()
    {
        $auth = $this->session->get('auth');
        if (!$auth) {
            $role = 'guest';
        } else {
            if ($auth->admin_session == true) {
                $role = \Admin\Model\AdminUser::getRoleById($auth->id);
            } else {
                $role = 'member';
            }
        }
        return $role;

    }

    private function accessDenied($role, $resourceKey = null, $resourceVal = null, View $view)
    {
        if (in_array($role, ['guest', 'member'])) {
            if (IS_ADMIN) {
                $urlRedirect = urlencode(BASE_URL . $this->router->getRewriteUri());
                $this->redirect(BASE_URL . '/admin/login?redirect_to=' . $urlRedirect);
            }
            $view->setViewsDir(APPLICATION_PATH . '/modules/Index/views/');
            $view->setPartialsDir(APPLICATION_PATH . '/modules/Index/views/');
            $view->message = "Bạn không có quyền truy cập";
            $view->partial('error/error403');

            $response = new \Phalcon\Http\Response();
            $response->setHeader(403, 'Forbidden');
            $response->sendHeaders();
            echo $response->getContent();
            exit;
            //return $this->redirect('http://cms.beta');
        }

        $view->setViewsDir(APPLICATION_PATH . '/modules/Index/views/');
        $view->setPartialsDir(APPLICATION_PATH . '/modules/Index/views/');
        $view->message = "$role - Access Denied to resource <b>$resourceKey::$resourceVal</b>";
        $view->partial('error/error403');

        $response = new \Phalcon\Http\Response();
        $response->setHeader(403, 'Forbidden');
        $response->sendHeaders();
        echo $response->getContent();
        exit;
    }

    private function resourceNotFound($resourceKey, Dispatcher $dispatcher, View $view)
    {
        $dispatcher->forward(
            [
                'module' => 'index',
                'namespace' => 'Index\Controller',
                "controller" => "Index",
                "action"     => "error404",
            ]
        );
        return false;
        //echo $dispatcher->getModuleName(); die;
        /*$view->message = "Acl resource <b>$resourceKey</b> in <b>/app/config/acl.php</b> not exists";
        $view->render('Index', 'error/error404');


        $response = new \Phalcon\Http\Response();
        $response->setHeader(404, 'Not Found');
        $response->sendHeaders();*/
        //echo $response->getContent();
        //exit;
    }

    private function redirect($url, $code = 302)
    {
        switch ($code) {
            case 301 :
                header('HTTP/1.1 301 Moved Permanently');
                break;
            case 302 :
                header('HTTP/1.1 302 Moved Temporarily');
                break;
        }
        header('Location: ' . $url);
        exit;
    }

}