<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-18
 * Time: 上午9:41
 * 兼容低版本函数
 */

if(!function_exists('lcfirst')){
    function lcfirst($str){
            $len = strlen($str);
            $prefix = substr($str,0,1);
            $duffix =  substr($str,1,$len);
            return strtolower($prefix).$duffix;
    }
}