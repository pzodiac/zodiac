<?php

/**
 * Volt
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\View\Engine;

class Volt extends \Phalcon\Mvc\View\Engine\Volt
{

    public function initCompiler()
    {
        $compiler = $this->getCompiler();

        $compiler->addFunction('helper', function () {
            return '$this->helper';
        });
        $compiler->addFunction('translate', function ($resolvedArgs) {
            return '$this->helper->translate(\'.$resolvedArgs.\')';
        });
        $compiler->addFunction('langUrl', function ($resolvedArgs) {
            return '$this->helper->langUrl(' . $resolvedArgs . ')';
        });
        $compiler->addFunction('image', function ($resolvedArgs) {
            return '(new \Image\Storage(' . $resolvedArgs . '))';
        });
        $compiler->addFunction('widget', function ($resolvedArgs) {
            return '(new \Application\Widget\Proxy(' . $resolvedArgs . '))';
        });

        $compiler->addFunction('latest_post', function () {
            $hookController = new \HookLib\Controller\HookController();
            return '"' . $hookController->latestPost() . '"';
        });

        $compiler->addFunction('substr', 'substr');

        $compiler->addFunction('in_array', 'in_array');
        $compiler->addFunction('http_build_query', 'http_build_query');
        $compiler->addFunction('uniqid', 'uniqid');
        $compiler->addFunction('strtotime', 'strtotime');
        $compiler->addFunction('date', 'date');
        $compiler->addFunction('nl2br', 'nl2br');
        $compiler->addFunction('rand', 'rand');
        $compiler->addFunction('array_merge', 'array_merge');

    }

}
