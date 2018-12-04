<?php

/**
 * Controller
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc;

use Phalcon\Http\Response;
use Cms\Model\Configuration;
/**
 * @property \Phalcon\Cache\Backend\Memcache $cache
 * @property \Phalcon\Mvc\View\Simple $viewSimple
 * @property \Application\Mvc\Helper $helper
 * @property \Phalcon\Http\Cookie $cookies
 */

class Controller extends \Phalcon\Mvc\Controller
{
    public function initialize() {
        $configuration = new Configuration();
        $theme = $configuration->getValueByKey('THEME');
        $this->view->setVars([
            'theme' => $theme,
            'frontend_url' => BASE_URL
        ]);
    }
    public function redirect($url, $code = 302)
    {
        switch ($code) {
            case 301:
                header('HTTP/1.1 301 Moved Permanently');
                break;
            case 302:
                header('HTTP/1.1 302 Moved Temporarily');
                break;
        }
        header('Location: ' . $url);
        $this->response->send();
    }

    public function error404()
    {
        $this->helper->title()->append('Error 404');
        $this->helper->meta()->set('description','Error 404');
        $this->helper->meta()->set('keywords', 'Error 404');
        $breadcrumb = [
            [
                'title'     => 'Trang chủ',
                'url'       => '/',
                'active'    => false 
            ],
            [
                'title'     => 'error',
                'url'       => '',
                'active'    => true
            ]
        ];
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->partial('error/error404');

    }

    public function outputJSON($response)
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setJsonContent($response);
        $this->response->send();
        exit;
    }

    public function returnJSON($response)
    {
        $this->view->disable();

        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($response));
        $this->response->send();
        die;
    }

    public function flashErrors($model)
    {
        foreach ($model->getMessages() as $message) {
            $this->flash->error($message);
        }
    }

    public function setAdminEnvironment()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->view->setLayout(null);
        $this->view->setVars([
            'theme' => THEME
        ]);
    }

    public function curlGet($url, $get = array(), $options = array())
    {
        $url = trim($url);
        if (!empty($get)) {
            $url .= '?' . http_build_query($get);
        }

        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function curlPost($url, $post = array(), $options = array())
    {
        $url = trim($url);
        if (!empty($post)) {
            $data = http_build_query($post);
        }
        

        $defaults = array(
            CURLOPT_POST => true,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FORBID_REUSE => true,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}