<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-10
 * Time: 下午3:35
 */

class ServerToolService extends ServerService{

        function ServerToolService(){
              parent::__construct();
              $this->table_operationlog = DB_PREFIX.'operationlog';
             $this->table_backupdata = DB_PREFIX.'backlog';
        }

        public function lists($page,$condition=null){

                $condition = $this->getCondition($condition);
                $list = $this->db->select("a.*,b.name as buissnesser,c.version")->from("$this->table_servers a , $this->table_buissnesser b ,$this->table_version c")
                                        -> where("a.bid = b.id and a.gamever=c.id  $condition")
                                        ->limit($page->start,$page->limit,' a.id desc ')
                                        ->get() -> result_objects();

                //得到所有条目的series
                $series_values = ArrayUtil::array_object_delete_repeat_values_by_key('gameseries',$list);
                $series_values = implode(',',$series_values);
                $series = $this->db->select('max(version) as lastversion,series')->from($this->table_version) -> where("series in ($series_values)")
                                                        -> group_by(" series ")
                                                        -> get()
                                                        -> result_objects();

                $apikey = $this->getApiKey();
                $operation = new ServerSysOperation();
                $operation -> setOptions('ljzm_cp_api','operation.php');
                $operation->send($list,ServerSysOperation::$OPERATION['check_game_process'],$apikey);
                $results = $operation->getResults();

                foreach($list as $obj){
                        foreach($series as $s){
                             if($obj->gameseries == $s->series){

                                  if($obj->version < $s->lastversion){
                                      $obj->version .= "(最新版本$s->lastversion)";
                                      $obj->color = 'purple';
                                  }else{
                                      // $obj->version .= '(最新)';
                                       $obj->color = 'black';
                                  }

                                  $obj->lastversion = $s->lastversion;
                                  break 1;
                             }
                        }

                        $obj->operationable = 1;
                        if($results[$obj->id] == 1)
                             $obj->stat = '开启';
                        else if($results[$obj->id] == -2)
                             $obj->stat = '关闭';
                        else if($results[$obj->id] == -3){
                             $obj->stat = 'url错误';
                             $obj->operationable = -1;
                        }
                        else{
                              $obj->stat = '检测失败';
                              $obj->operationable = -1;
                        }

                }

                return $list;
        }

        public function num_rows($condition){
                $condition = $this->getCondition($condition);
                return $this->db->select(" count(a.id) as num") -> from("$this->table_servers a , $this->table_buissnesser b")-> where("a.bid = b.id $condition")->get()->result_object()->num;
        }

        private function getCondition($condition){
                $sql = '';
                if(!empty($condition->bid)){
                    $sql .= " and a.bid = $condition->bid";
                }

                if(!empty($condition->series)){
                      $sql .= " and a.gameseries = $condition->series ";
                }

                if(!empty($condition->version)){
                      $sql .= " and a.gamever = $condition->version ";
                }

                return $sql;
        }


       public function is_server_open($serverid){
           $server = $this->db->select()->from($this->table_servers)->where("id= $serverid") -> get() -> result_object();
           $servers[] = $server;
           $apikey = $this->getApiKey();
           $operation = new ServerSysOperation();
           $operation -> setOptions('ljzm_cp_api','operation.php');
           $operation->send($servers,ServerSysOperation::$OPERATION['check_game_process'],$apikey);
           $results = $operation->getResults();
           return $results[$serverid];
       }


       public function getBackUpData($sid){
            $list =  $this->db->select()->from($this->table_backupdata) -> where(" sid = $sid ") -> order_by(" time desc ") -> get() -> result_objects();
            foreach($list as &$obj){
                 $obj -> time = date('Y-m-d H:i:s',$obj->time);
            }
           return $list;
       }

        /*======================操作=======================================*/
        private function getApiKey(){//得到验证加密key
          return   $this->db->select('code')->from(TB_APIKEY)->where('id=1')->get()->result_object()->code;
        }


        public function op_open($serverids,$admin){
                  return  $this -> doOperation($serverids,$admin,ServerSysOperation::$OPERATION['open'],1,60);
        }


       public function op_stop($serverids,$admin){
           return  $this -> doOperation($serverids,$admin,ServerSysOperation::$OPERATION['stop'],2);
       }


       public function op_reboot($serverids,$admin){
           return  $this -> doOperation($serverids,$admin,ServerSysOperation::$OPERATION['reboot'],3,60);
       }


       public function op_updateVer($serverids,$admin){
           return  $this -> doOperation($serverids,$admin,ServerSysOperation::$OPERATION['update'],4);
       }


       public function  op_merge($fromserverids,$toserverid,$admin){
           return  $this -> doOperation($toserverid,$admin,ServerSysOperation::$OPERATION['merge'],5,0,$fromserverids);
       }


       public function  op_rollback($serverid,$backupid,$admin){
           return  $this -> doOperation($serverid,$admin,ServerSysOperation::$OPERATION['rollback'],6,0,'',$backupid);
       }


       public function  op_clear($serverid,$admin){
          return  $this -> doOperation($serverid,$admin,ServerSysOperation::$OPERATION['clear'],7);
       }


       private function doOperation($serverids,$admin,$operation_type,$logtype,$sleep=0,$fromServerids='',$backupid=''){
           if(!empty($serverids)){
               $apikey = $this->getApiKey();
               $servers = $this->db->query("select * from $this->table_servers where id in ($serverids)")->result_objects();
               if(count($servers) == 0) return -1;

               $filename = '';
               if(!empty($backupid)){
                     $backup  =   $this->db->query("select filename from $this->table_backupdata where id = $backupid")->result_object();
                     $filename = $backup -> filename;
               }

               if($operation_type == 7){//更新必定为单项操作 所以serverids 是一个id
                      $info  = $this->db->select("b.version,c.name") -> from("$this->table_servers a , $this->table_version b , $this->table_series c")
                                    -> where("a.id = $serverids and  a.gamever = b.id and a.gameseries=c.id") -> get() -> result_object();
                       $filename = $info->name.'_'.$info->version;
               }

               $params = array(
                   'admin' => $admin->admin,
                   'admin_flagname' => $admin->flagname,
                   'adminid' => $admin->id,
                   'donetime' => time(),
                   'logtype' => $logtype
               );
                //写入操作日志
               $this->db->insert($this->table_operationlog,$params);
               $logid = $this->db->insert_id();


               $operation = new ServerSysOperation();
               $operation -> setOptions('ljzm_cp_api','operation.php');
               $operation->send($servers,$operation_type,$apikey,$fromServerids,$filename,$logid);
               $success_servers = $operation->getSuccessServers();
               $failed_servers = $operation->getFailedServers();


               if(count($success_servers) > 0){
                   $success_serverids = ArrayUtil::array_object_implode_values_by_key('id',',',$success_servers);
                   $success_servernames = ArrayUtil::array_object_implode_values_by_key('name',',',$success_servers);
               }
               else{
                   $success_serverids = '';
                   $success_servernames = '';
               }

               if(count($failed_servers) > 0){
                   $failed_serverids = ArrayUtil::array_object_implode_values_by_key('id',',',$failed_servers);
                   $failed_servernames = ArrayUtil::array_object_implode_values_by_key('name',',',$failed_servers);
               }
               else{
                   $failed_serverids = '';
                   $failed_servernames = '';
               }


                $params=array(
                        'success_serverids' => $success_serverids,
                       'success_servernames'=>$success_servernames,
                       'failed_serverids' => $failed_serverids,
                       'failed_servernames' => $failed_servernames,
                );

               $this->db -> update($this->table_operationlog,$params," id = $logid");

               $return = new stdClass();
               $return ->  success_serverids = $success_serverids;
               $return ->  success_servernames = $success_servernames;
               $return ->  failed_serverids = $failed_serverids;
               $return -> failed_servernames = $failed_servernames;

               if($sleep!=0)sleep($sleep);

               return $return;
           }
           return null;
       }

} 