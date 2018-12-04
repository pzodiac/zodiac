<?php

/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 12/04/18
 * Time: 14:33 PM
 */
namespace Zodiac\Plugin;

class MobileDetect
{

    public function __construct($session, $view, $request)
    {
        $detect = new \Mobile_Detect();

        $mobile = $request->getQuery('mobile');
        if ($mobile == 'false') {
            $session->set('device_detect', 'desktop');
        }
        if ($mobile == 'true') {
            $session->set('device_detect', 'mobile');
        }

        $isMobile = false;
        $device_detect = $session->get('device_detect');
        if (!empty($device_detect)) {
            $isMobile = ($device_detect == 'mobile') ? true : false;
        } else {
            if ($detect->isMobile() && !$detect->isTablet()) {
                $isMobile = true;
                $session->set('device_detect', 'mobile');
            } else {
                $session->set('device_detect', 'desktop');
            }
        }


        define('MOBILE_DEVICE', ($isMobile) ? true : false);

        $device = 'desktop';
        if (MOBILE_DEVICE) {
            $device = 'mobile';
            $view->setMainView(MAIN_VIEW_PATH . 'mobile');
        }

        if(!IS_ADMIN) {
            $view->setViewsDir(MAIN_VIEW_PATH . $device . '/');
            $view->setPartialsDir(MAIN_VIEW_PATH . $device . "/partials/");
            $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        }
    }

} 