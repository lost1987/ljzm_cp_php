<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-15
 * Time: 上午10:14
 */

class ArrayUtil {
    /**
     * 取数组下标从start 到 end条
     *
     */
    public static  function array_fetch($start , $limit ,$array){
        if(!is_int($start) || !is_int($limit)){
            return;
        }

        $newarray = array();

        for($i =$start ; $i < $limit; $i++){
            if(!empty($array[$i]))
                $newarray[] = $array[$i];
        }

        return $newarray;
    }



    /**
     * @param $element  要插入的元素
     * @param $index    要插入的索引位置
     * @param $array    原始数组
     * @return array
     * 将元素插入到$index的数组位置
     */
   public static  function array_insert_element($element,$index,$array){
        if($index < 0)
            throw new OutOfRangeException('index must be > 0');
        $next_array = array_slice($array,$index-1);
        $pre_array = array_slice($array,0,$index-1);
        $pre_array[] = $element;
        return array_merge($pre_array,$next_array);
    }


    /**
     * @param $index
     * @param $array
     * @return mixed
     * @throws OutOfRangeException
     * 删除下标为index的数组元素
     */
   public static  function array_delete_element($index,$array){
        if($index < 0)
            throw new OutOfRangeException('index must be > 0');
        unset($array[$index-1]);
        return $array;
    }

    /**
     * 条件 数组元素必须是对象 索引必须是数字
     * @param $array
     * @return Array
     * 删除指定对象key的数组中value重复的值 ,并将这些不重复的值返回到一个数组中
     */
    public static function array_object_delete_repeat_values_by_key($key,$array){
        $value_array = array();
           foreach($array as $obj){
               $value_array[] = $obj->$key;
           }
          return array_unique($value_array);
    }

    /**
     * 条件 数组元素必须是对象 索引必须是数字
     * @param $key       对象key
     * @param $symbol 连接符号
     * @param $array
     * @return String
     * 将数组中指定对象key的值 , 进行符号连接,返回一个连接后的字符串
     */
    public static function array_object_implode_values_by_key($key,$symbol,$array){
            $value_array = array();
            foreach($array as $obj){
                $value_array[] = $obj->$key;
            }
            return implode($symbol,$value_array);
    }
} 