<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zyy
 * Date: 13-5-2
 * Time: 下午3:00
 * To change this template use File | Settings | File Templates.
 * 符文宝箱日志
 */
class FuwenbagDailyService extends ServerDBChooser{
    function FuwenbagDailyService(){
        $this->table_record = 'fr2_record';
        $this->table_user = 'fr_user';
        $this->table_item= 'fr_item';
        $this->db_static = 'mmo2d_staticljzm';
    }
    public function getCondition($condition){
        $starttime = $condition->starttime;
        $endtime = $condition->endtime;
        $account_name = $condition -> account_name;
        $type = $condition -> type;
        $child_type = $condition -> child_type;
        $level_start = $condition -> level_start;
        $level_limit = $condition -> level_limit;
        $vip_start = $condition -> vip_start;
        $vip_limit = $condition -> vip_limit;

        $sql = '';
        if(!empty($starttime) && !empty($endtime)){
            $starttime .= ' 00:00:00';
            $endtime .= ' 23:59:59';
            $time = $this->db->cast('a.time');
            $cond1 = " $time >= '$starttime' and $time <= '$endtime'";
        }

        if(!empty($account_name)){
            $cond2 = " ( b.account_name like '$account_name%' or b.name like '$account_name%')";
        }

        if($type != -1){
            $cond3 = " a.type = $type ";
        }

        if(!empty($child_type)){
            $cond4 = " a.param4 = $child_type ";
        }

        if(!empty($level_limit) && !empty($level_start)){
            if($level_limit == $level_start){
                $cond5 = " b.levels = $level_limit ";
            }else{
                $cond5 = " (b.levels >= $level_start and b.levels <= $level_limit ) ";
            }
        }

        if(!empty($vip_start) && !empty($vip_limit)){
            if($vip_limit == $vip_start)
                $cond6 = " (b.mask0%100) = $vip_limit";
            else
                $cond6 = " ((b.mask0%100) >= $vip_start and (b.mask0%100) <= $vip_limit) ";
        }

        if(isset($cond1)){
            $sql .= $cond1;
        }

        if(isset($cond2)){
            if(!empty($sql)){
                $sql .= ' and '.$cond2;
            }else{
                $sql .= $cond2;
            }
        }

        if(isset($cond3)){
            if(!empty($sql)){
                $sql .= ' and '.$cond3;
            }else{
                $sql .= $cond3;
            }
        }

        if(isset($cond4)){
            if(!empty($sql)){
                $sql .= 'and '.$cond4;
            }else{
                $sql .= $cond4;
            }
        }

        if(isset($cond5)){
            if(!empty($sql)){
                $sql .= 'and '.$cond5;
            }else{
                $sql .= $cond5;
            }
        }

        if(isset($cond6)){
            if(!empty($sql)){
                $sql .= 'and '.$cond6;
            }else{
                $sql .= $cond6;
            }
        }


        if(empty($sql))
        return '';
        return " and ".$sql;

    }
    public function lists($page,$condition){
        $server = $condition->server;
        $list = array();
        if(!empty($server)){
            $this -> dbConnect($server,$this->db_static);
            $items = Datacache::getStaticItems($this->db);
            $_items = array();
            foreach($items as $item){
                if(strpos($item->name,'符文宝箱') > -1){
                    $_items[] = $item->id;
                }
            }
            $_items_ids = implode(',',$_items);
            if(empty($_items_ids))return $list;
            $this -> dbConnect($server,$server->dynamic_dbname);
            $consql = $this->getCondition($condition);
            $time = $this->db->datetime('a.time');
            $list = $this->db->select("a.id as tid,a.id1,a.type,a.str as action,a.param1,a.param2,a.param3,a.param4,$time
                                        as time,b.id,b.account_name,b.name,b.levels")
                ->from("$this->table_record a left join   $this->table_user b on a.id1=b.id")
                ->where("a.param1 in ($_items_ids)")
                ->where($consql)
                ->order_by('a.time desc')
                ->limit($page->start,$page->limit,'a.time desc')
                ->get()->result_objects();


            $this->db->close();
            include BASEPATH.'/Common/event.php';

            foreach($list as &$obj){
                $obj->detail = empty($gameevent[$obj->param4]) ? '未知' : $gameevent[$obj->param4];
                $obj->servername = $server->name;
                $obj->bagname = empty(fetch_object_by_key('id',$obj->param1,$items) -> name) ? '未知' : fetch_object_by_key('id',$obj->param1,$items) -> name;
                if($obj->type==1){
                    $obj->typename = '消耗';
                    $obj->rongyuchange = '-'.$obj->param2;
                }
                else {
                    $obj->typename = '获取';
                    $obj->rongyuchange = '+'.$obj->param2;
                }
            }
        }
        return $list;

    }
    public function num_rows($condition){
        $server = $condition->server;
        $this -> dbConnect($server,$this->db_static);
        $items = Datacache::getStaticItems($this->db);
        $_items = array();
        foreach($items as $item){
            if(strpos($item->name,'符文宝箱') > -1){
                $_items[] = $item->id;
            }
        }
        $_items_ids = implode(',',$_items);
        if(empty($_items_ids))return 0;
        $this -> dbConnect($server,$server->dynamic_dbname);
        $consql = $this->getCondition($condition);
        return $this->db->select("count(a.id1) as num")
               ->from("$this->table_record a left join $this->table_user b on a.id1 = b.id")
               ->where("a.param1 in ($_items_ids)")
               ->where($consql)
               ->get()->result_object()->num;
    }
}