<?php
/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 7/17/18
 * Time: 4:41 PM
 */

namespace Cms\Model;


use Application\Mvc\Model\Model;

class District extends Model
{
    public $id;
    public $province_id;
    public $name;
    public $slug;

    public function getSource()
    {
        return "district";
    }

    public function initialize()
    {
        $this->belongsTo('province_id', 'Cms\Model\Province', 'id', array(
            'alias' => 'province',
            'foreignKey' => true
        ));
    }
}