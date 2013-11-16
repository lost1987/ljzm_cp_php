<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-15
 * Time: 下午4:29
 */

class OpenServerApplyService extends Service{
    private $stat = array(
        1 => '未审核',
        2=> '已批准',
        3=> '已拒绝'
    );

    function __construct(){
        parent::__construct();
        $this -> table_openservers = DB_PREFIX.'openservers';
        $this -> table_buissnesser = DB_PREFIX.'buissnesser';
        $this -> db ->  select_db(DB_NAME);
    }

    public function lists($page){
        $list = $this -> db -> select("id,servername,buissnesser,stat,serverip,time,optime,applytime")
            -> from($this->table_openservers)
            -> limit($page->start,$page->limit,'id desc')
            -> get()
            -> result_objects();

        foreach($list as &$obj){
            $obj->time = date('Y-m-d H:i:s',$obj->time);
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


    public function save($condition){
        $time = strtotime($condition->time);
        $servername = $condition->servername;
        $serverip = $condition->serverip;
        $buissnesser=$condition->buissnesser->name;
        $bid = $condition->buissnesser->id;

        $values = array(
            'time' => $time,
            'servername'=>$servername,
            'serverip' => $serverip,
            'buissnesser'=>$buissnesser,
            'bid' => $bid,
            'stat' => 1,
            'applytime' => time()
        );

        return $this->db->insert($this->table_openservers,$values);
    }

    public function num_rows($condition){
        return $this->db->select("count(id) as num") -> from($this->table_openservers) -> get() -> result_object() -> num;
    }

    function refuse($id){
        if(empty($id)) return FALSE;
        $values = array(
            'stat' => 3,
            'optime' => time()
        );
        return  $this -> db -> update($this->table_openservers,$values,"id = $id");
    }

    function allow($id){
        if(empty($id)) return FALSE;
        $values = array(
            'stat' => 2,
            'optime' => time()
        );
        return  $this -> db -> update($this->table_openservers,$values,"id = $id");
    }
} 