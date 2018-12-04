<?php
/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 7/17/18
 * Time: 4:32 PM
 */

namespace Cms\Model;


use Application\Mvc\Model\Model;

class Province extends Model
{
    public $id;
    public $name;
    public $slug;

    public function getSource()
    {
        return "province";
    }

    public function initialize()
    {

    }
}