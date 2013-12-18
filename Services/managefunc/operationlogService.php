<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-17
 * Time: 上午9:43
 */

class OperationlogService extends Service{
        function OperationlogService(){
                parent::__construct();
                $this->table_operationlog = DB_PREFIX.'operationlog';
                $this->db->select_db(DB_NAME);
        }


       public function logTypes($null){
                include BASEPATH.'/Common/log.php';
                $obj = new stdClass();
                $obj->type = '';
                $obj->name='全部';
                $list = array($obj);
                foreach($log_operation_type as $k =>$v){
                        $obj = new stdClass();
                        $obj -> type = $k;
                        $obj -> name = $v;
                        $list[] = $obj;
                }
                return $list;
       }

       public function lists($page,$cond){
                DB::$debug = true;
                include BASEPATH.'/Common/log.php';
                $condition = $this->getCondition($cond);
                $list = $this->db->select() -> from($this->table_operationlog." $condition ")
                                                -> limit($page->start,$page->limit,' id desc ')
                                                -> get() -> result_objects();

                foreach($list as &$obj){
                       $obj->logname = $log_operation_type[$obj->logtype];
                       if($obj->state == 1){
                           $obj->statename = '已完成';
                       }else{
                           $obj->statename = '执行中';
                       }

                       $obj->donetime = date('Y-m-d H:i:s',$obj->donetime);
                }

                 return $list;
       }

      public function num_rows($cond){
              $condition = $this->getCondition($cond);
              return  $this->db->select('count(id) as num') -> from($this->table_operationlog." $condition ")
                          -> get() -> result_object() -> num;
      }

      private function getCondition($condition){
                $sql1  = '';
                if(!empty($condition->account_or_name)){
                        $sql1 = " admin_flagname = '$condition->account_or_name' or admin = '$condition->account_or_name' ";
                }

                $sql2 = '';
                if(!empty($condition->state)){
                        $sql2 = " state = $condition->state ";
                }

                $sql3 = '';
               if(!empty($condition->starttime)){
                          $time = strtotime($condition->starttime);
                         $sql3 = " donetime > $time ";
               }

               $sql4 = '';
               if(!empty($condition->endtime)){
                        $time = strtotime($condition->endtime);
                        $sql4 = " donetime < $time ";
               }


               $sql5 = '';
               if(!empty($condition->logtype)){
                        $sql5 = " logtype = $condition->logtype ";
               }

              $sql = array();
              for($i = 1; $i < 6; $i++){
                    if(!empty( ${'sql'.$i} )){
                            $sql[] = ${'sql'.$i};
                    }
              }

               if(count($sql) > 1){
                   $sql = ' where ' .implode(' and ',$sql);
               }else if(count($sql) == 1){
                   $sql = ' where '.$sql[0];
               }else{
                   $sql = '';
               }

              return $sql;
      }
} 