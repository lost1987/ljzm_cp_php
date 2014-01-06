<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lost
 * Date: 13-4-7
 * Time: 下午4:21
 * To change this template use File | Settings | File Templates.
 * 礼包
 */
class ActivecodeService extends  ServerDBChooser
{
    function ActivecodeService(){
        $this -> table_activecode = $this->prefix_2.'activecode';
        $this -> table_item = $this->prefix_1.'item';

        $this -> db_activecode = 'mmo2d_baseljzm';
        $this -> db_static = 'mmo2d_staticljzm';
    }

    public function lists($page,$condition){
            $servers = $condition->servers;
            $list = array();
            $flag = 0;
            $this ->db = new DB;
            $this -> db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
            $this -> db -> select_db($this->db_activecode);
            foreach($servers as $server){
                $ctime = $this->db->datetime('ctime');
                $sql = "select
                count(id) as nums , name,$ctime as ctime from $this->table_activecode where sid = $server->id  group by
                name,ctime order by ctime desc";
                $templist = $this -> db -> query($sql) -> result_objects();
                if(is_array($templist)){
                    foreach($templist as $temp){
                        if($flag >= $page->start && $flag < $page -> limit){
                            $temp -> servername = $server->name;
                            $temp -> server = $server;
                            $log = new Syslog();
                            $loginfo = $log -> getlogByTime($temp->ctime,$server->id);
                            $temp -> flagname = empty($loginfo->flagname) ? '' : $loginfo->flagname;
                            $list[] = $temp;
                        }
                        $flag++;
                    }
                }
                $this -> dbClose();
            }
        return $list;
    }

    public function num_rows($condition){
        $servers = $condition->servers;
        $nums = 0;
        $this ->db = new DB;
        $this -> db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
        $this -> db -> select_db($this->db_activecode);
        foreach($servers as $server){
            $sql = "select count(id) as nums  from $this->table_activecode where sid = $server->id  group by
                name,ctime";
            $templist = $this -> db -> query($sql) -> result_objects();
            if(is_array($templist)){
                foreach($templist as $temp){
                    $nums++;
                }
            }
        }
        return $nums;
    }

    public function save($activecode){
        set_time_limit(0);
        require BASEPATH.'/Common/log.php';
        //通过生成时间和服务器ID关联操作人
        $servers = $activecode -> servers;
        $server_ids = array();
        foreach($servers as $server){
            $server_ids[] = $server->id;
        }
        $server_ids = implode(',',$server_ids);
        $servers = $this->getServers($server_ids);
        $ctime = date('Y-m-d H:i:s');
        $ctime = substr($ctime,0,strlen($ctime)-2).'00';//smalldatetime
        for($i = 1; $i< 9 ; $i++){
            $activecode->{'id'.$i} = empty($activecode->{'id'.$i}) ? 0 : $activecode->{'id'.$i};
            $activecode->{'num'.$i} = empty($activecode->{'num'.$i}) ? 0 : $activecode->{'num'.$i};
        }
        $id1 = empty($activecode->id1) ? '' : $activecode->id1;
        //已执行过的事务DB数组
        $executed_dbs = array();
        //日志数据库连接
        $logdbs = array();

        //静态数据库
        $db_static = $this->getNewStaticDB();
		
        //base数据库
        $db_base = $this->getNewBaseDB();

        try{
            for($t = 0 ; $t < count($servers) ; $t++){
                $server = $servers[$t];

                //验证物品列表ID正确性
                $items = $db_static->query("select id from $this->table_item")->result_objects();
			
                $item_arr = array();
                foreach($items as $item){
                    $item_arr[] =  $item -> id;
                }

                $errorcode = 0;
                for($i = 1; $i< 9 ; $i++){
                    if(!in_array($activecode->{'id'.$i},$item_arr) && $activecode->{'id'.$i}!=0){
                        $errorcode = $i;
                        break;
                    }
                }

                if($errorcode > 0)return $errorcode;

                $db_base -> trans_begin();
                $nums = $activecode->nums;
                $sql = "insert into $this->table_activecode (acode,name,astate,ctime,amask,itemid0,nums0,itemid1,nums1,itemid2,nums2,itemid3,nums3,itemid4,nums4,itemid5,nums5,itemid6,nums6,itemid7,nums7,aid,sid )  ";
                $pernum = 100;//每次插入100条
                $cur = 1;//游标
                for($i =0 ; $i < $nums;$i++){
                    $acode  =  date('YmdHis').make_rand_str();
                    if($cur%$pernum == 0){
                        if(!$db_base->query($sql)->queryState)throw new Exception('activecode write data error!');
                        $sql = "insert into $this->table_activecode (acode,name,astate,ctime,amask,itemid0,nums0,itemid1,nums1,itemid2,nums2,itemid3,nums3,itemid4,nums4,itemid5,nums5,itemid6,nums6,itemid7,nums7,aid,sid )  ";
                        $sql .=  " select '$acode','$activecode->name',$activecode->astate,'$ctime',
                                    $activecode->amask,$activecode->id1,$activecode->num1,$activecode->id2,
                                    $activecode->num2,$activecode->id3,$activecode->num3,$activecode->id4,
                                    $activecode->num4,$activecode->id5,$activecode->num5,$activecode->id6,
                                    $activecode->num6, $activecode->id7,$activecode->num7,$activecode->id8,
                                    $activecode->num8 ,0, {$server->id} ";
                    }else if($cur == 1){
                        $sql .=  " select '$acode','$activecode->name',$activecode->astate,'$ctime',
                                    $activecode->amask,$activecode->id1,$activecode->num1,$activecode->id2,
                                    $activecode->num2,$activecode->id3,$activecode->num3,$activecode->id4,
                                    $activecode->num4,$activecode->id5,$activecode->num5,$activecode->id6,
                                    $activecode->num6, $activecode->id7,$activecode->num7,$activecode->id8,
                                    $activecode->num8 ,0, {$server->id} ";
                    }
                    else{
                        $sql .= " union all select '$acode','$activecode->name',$activecode->astate,'$ctime',
                                    $activecode->amask,$activecode->id1,$activecode->num1,$activecode->id2,
                                    $activecode->num2,$activecode->id3,$activecode->num3,$activecode->id4,
                                    $activecode->num4,$activecode->id5,$activecode->num5,$activecode->id6,
                                    $activecode->num6, $activecode->id7,$activecode->num7,$activecode->id8,
                                    $activecode->num8 ,0, {$server->id} ";
                    }

                    if($cur == $nums){
                        if(!$db_base->query($sql)->queryState)throw new Exception('activecode write data error!');
                    }

                    $cur++;
                }

                $log = new stdClass();
                $log -> aid = $activecode -> admin -> id;
                $log -> admin = $activecode -> admin -> admin;
                $log -> flagname = $activecode -> admin -> flagname;
                $log -> type = 4;
                $log -> typename = $log_action_type[4];
                $log -> donetime = $ctime;
                $log -> server_id = $servers[$t]->id;
                $log -> server_name = $servers[$t]->name;
                $log -> refer_id = 0;
                $log -> refer_name = '';

                $slog = new Syslog();
                $log_db = new DB();
                $logdbs[] = $log_db;
                $log_db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD,TRUE);
                $log_db -> select_db(DB_NAME);
                $log_db -> trans_begin();
                if(!$slog -> setlog($log) -> tran_save($log_db))throw new Exception('activecode write data error!');
            }

            //执行完成 提交
            $db_base -> commit();

            for($i = 0 ; $i < count($logdbs) ; $i++){
                $logdbs[$i] -> commit();
                $logdbs[$i] -> close();
            }
            return 0;
        }catch (Exception $e){
            $db_base->rollback();

            for($i = 0 ; $i < count($logdbs) ; $i++){
                $logdbs[$i] -> rollback();
                $logdbs[$i] -> close();
            }
        }
        return -1;
    }

    public function getCondition($condition){}

    public function detail($obj){
          $server = $obj -> server;
          $list = array();
            $this ->db = new DB;
            $this -> db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
            $this -> db -> select_db($this->db_activecode);
          if(!empty($server)){
              $sql = "select acode,amask,itemid0,nums0,itemid1,nums1,itemid2,nums2,
               itemid3,nums3,itemid4,nums4,itemid5,nums5,itemid6,nums6,itemid7,nums7
               from $this->table_activecode where ctime = '$obj->ctime'  and name='$obj->name'";
              $list = $this -> db -> query($sql) -> result_objects();
              foreach($list  as &$temp){
                  $temp -> server = $server;
              }
          }
          return $list;
    }
}
