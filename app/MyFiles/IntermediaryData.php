<?php
/**
 * Created by PhpStorm.
 * User: coroi
 * Date: 4/7/2018
 * Time: 6:09 PM
 */

namespace App\MyFiles;


class IntermediaryData
{
    public static $global = null;

    public static function set($data){
        return self::$global = $data;
    }

    public static function get(){
        return self::$global;
    }
}