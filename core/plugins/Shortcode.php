<?php
namespace PhalartCMS\Plugin;

require __DIR__ . '/WP/formatting.php';
require __DIR__ . '/WP/shortcodes.php';

class Shortcode

{
    function __construct($controller)
    {
        add_shortcode("ads_item", function($atts, $content) use ($controller){


            return 'test  dsaf sdashortcut';

        });
    }



    function do_shortcode($content)
    {
        return do_shortcode($content);
    }
}