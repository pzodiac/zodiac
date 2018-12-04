<?php

namespace PhalartCms\Plugin;

use Cms\Model\Configuration;

class Options
{
    public $options;

    public function __construct()
    {
        $options = Configuration::findFirst(array(
            'conditions' => 'key = :key:',
            'bind' => array(
                'key' => 'global_option'
            )
        ));
        $optionsValue = array();
        if ($options) {
            $optionsValue = unserialize($options->value);
        }

        $this->options = $optionsValue;
    }

    public function get($key, $default = '')
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        } else {
            return $default;
        }
    }

}