<?php
/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 7/6/18
 * Time: 11:26 AM
 */

namespace Application\Utils;


class Utility
{
    public static function isSerialized($str) {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }
}