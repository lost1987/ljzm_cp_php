<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-13
 * Time: 上午9:38
 */

class MergeServerApplyService extends  Service {

    private $stat = array(
        1 => '未审核',
        2=> '已批准',
        3=> '已拒绝'
    );

    function __construct(){
        parent::__construct();
        $this -> table_mergeservers = DB_PREFIX.'mergeservers';
        $this -> table_buissnesser = DB_PREFIX.'buissnesser';
        $this -> db ->  select_db(DB_NAME);
    }

    public function lists($page){
            $list = $this -> db -> select("id,fromservernames,toservername,stat,mergetime,tobuissnesser,frombuissnessers,optime,applytime")
                                -> from($this->table_mergeservers)
                                -> limit($page->start,$page->limit,'id desc')
                                -> get()
                                -> result_objects();

            foreach($list as &$obj){
                 $obj->mergetime = date('Y-m-d',$obj->mergetime);
                 $obj -> buissnesserchange = $obj->frombuissnessers.'  -->  '.$obj->tobuissnesser;
                 $obj -> statname = $this->stat[$obj->stat];
                $obj -> applytime = date('Y-m-d H:i:s',$obj->applytime);
                if(!empty($obj->optime))  date('Y-m-d H:i:s',$obj->optime);
                if($obj->stat != 1)
                    $obj->_enabled = FALSE;
                else
                    $obj->_enabled = TRUE;
            }
            return $list;
    }

    public function num_rows($condition){
        return $this->db->select("count(id) as num") -> from($this->table_mergeservers) -> get() -> result_object() -> num;
    }


    public function save($condition){
            $mergetime = $condition -> mergetime;
            $server = $condition -> server;
            $servers = $condition -> servers;
            $tobid = $server -> bid;
            $frombids = ArrayUtil::array_object_delete_repeat_values_by_key('bid',$servers);
            $frombids = implode(',',$frombids);

            if(empty($mergetime) || empty($server) || count($servers) < 1)return FALSE;

            $mergetime = strtotime($mergetime);
            $to_server_id = $server->id;
            $to_server_name = $server->name;
            $from_servers_ids = array();
            $from_servers_names = array();
            foreach($servers as $s){
                $from_servers_ids[] = $s->id;
                $from_servers_names[] = $s -> name;
            }

           $from_servers_ids = implode(',',$from_servers_ids);
           $from_servers_names = implode(',',$from_servers_names);

           $tobuissnesser = $this->db->query("select name from $this->table_buissnesser where id = $tobid") -> result_object();
           $frombuissnessers = $this->db->query("select name from $this->table_buissnesser where id in ($frombids)") -> result_objects();
           $frombuissnessers = ArrayUtil::array_object_implode_values_by_key('name',',',$frombuissnessers);

           $values = array(
               'fromserverids' => $from_servers_ids,
               'toserverid' => $to_server_id,
               'mergetime' => $mergetime,
               'fromservernames'=> $from_servers_names,
               'toservername' => $to_server_name,
               'tobuissnesser' => $tobuissnesser->name,
               'tobid' => $tobid,
               'frombids'=>$frombids,
               'frombuissnessers' => $frombuissnessers,
               'applytime' => time()
           );

          return $this->db -> insert($this->table_mergeservers,$values);
    }

    function refuse($id){
        if(empty($id)) return FALSE;
        $values = array(
            'stat' => 3,
            'optime' => time()
        );
       return  $this -> db -> update($this->table_mergeservers,$values,"id = $id");
    }

    function allow($id){
        if(empty($id)) return FALSE;
        $values = array(
            'stat' => 2,
            'optime' => time()
        );
        return  $this -> db -> update($this->table_mergeservers,$values,"id = $id");
    }

} 