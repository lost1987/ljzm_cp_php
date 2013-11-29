<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lost
 * Date: 13-4-26
 * Time: 下午1:32
 * To change this template use File | Settings | File Templates.
 */
class LogEventsService
{
    public function lists($null){
        require BASEPATH.'/Common/event.php';
        $list = array();
        foreach($gameevent as $index => $v){
            $temp = new stdClass();
            $temp->name = $v;
            $temp->id = $index;
            $list[] = $temp;
        }
        return $list;
    }

    public function listsByType($type){
        require BASEPATH.'/Common/event.php';
        if($type==0)//收益
            $gameevent = ArrayUtil::array_fetch(1,87,$gameevent);
        else//消耗
            $gameevent = ArrayUtil::array_fetch(88,238,$gameevent);

        foreach($gameevent as $index => $v){
            $temp = new stdClass();
            $temp->name = $v;
            $temp->id = $index;
            $list[] = $temp;
        }

        return $list;
    }
}
