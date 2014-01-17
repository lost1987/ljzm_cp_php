<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 14-1-16
 * Time: 上午11:27
 */

class PayMonthService extends ServerDBChooser{

        function PayMonthService(){
        	$this->table_record = $this->prefix_2.'record';
        }


	   //param4 = 44 and  id2=0 and param1=90000001 and SUBSTR(str2,1,6) <> 'REWARD'
       public function lists($condition){
                $starttime = $condition -> starttime.' 00:00:00';
                $endtime = $condition -> endtime.' 23:59:59';
                $server = $this->getServer($condition->server->id);
                $this->dbConnect($server,$server->dynamic_dbname);

                $lists = $this->db->select("sum(param2) as yuanbao , left(time,10) as date ")->from($this->table_record)
                			-> where(" param4 = 44 and id2 = 0 and param1 = 90000001 and SUBSTR(str2,1,6) <> 'REWARD' ")
							-> group_by(" left(time,10) ")
							-> get()
							-> result_objects();

                $templist = array();
                foreach($lists as $obj){
                     $templist[$obj->date] = $obj->yuanbao/10;
                }
                unset($lists);

                $list = array();
			    $datediff = timeTickArrayPoint($starttime,$endtime,60*60*24,'Y-m-d');
                array_pop($datediff);
                $today =  date('Y-m-d');
                foreach($datediff as $date){
                       if(strtotime($date) > strtotime($today))continue;

                       $obj = new stdClass();
                       if($date == $today){
                                $obj -> color = 'red';
                                $obj -> weight = 'bold';
                       }else{
                               $obj -> color = 'black';
                               $obj -> weight = 'normal';
                       }

                       if(array_key_exists($date,$templist)){
                                $obj->date = $date;
                                $obj->money = '￥'.$templist[$date];
                       }else{
                                $obj->date = $date;
                                $obj->money = '';
                       }
                       $list[] = $obj;
                }

                return $list;
       }

       public function getCondition($condition){}
} 