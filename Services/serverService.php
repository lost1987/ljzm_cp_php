<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lost
 * Date: 13-3-12
 * Time: 下午1:56
 * To change this template use File | Settings | File Templates.
 * 服务器
 */
class ServerService extends Service implements IService
{

    function serverService(){
        parent::__construct();
        $this -> db -> select_db(DB_NAME);
        $this -> table_servers = DB_PREFIX.'servers';
        $this -> table_buissnesser = DB_PREFIX.'buissnesser';
        $this -> table_version = DB_PREFIX.'versions';
        $this -> table_series = DB_PREFIX.'series';
        $this-> table_cversion = DB_PREFIX.'client_versions';
    }

    public function lists($page,$condition=null)
    {
        // TODO: Implement lists() method.
      /*  $sql = "select * from (select row_number() over (order by a.id desc) as rownumber, a.*,b.name as buissnesser from  $this->table_servers a,$this->table_buissnesser b where a.bid = b.id and a.stat=1 and b.stat=1) as t where t.rownumber > $page->start and t.rownumber <= $page->limit";
        $res = $this->db->query($sql)->result_objects();*/

        $cond = '';
        if($condition!=null)
        $cond = $this->getCondition($condition);
        $res = $this -> db -> select(" a.*,b.name as buissnesser,d.name as series,c.version,e.version as cversion")
               -> from("$this->table_servers a,$this->table_buissnesser b,$this->table_version c,$this->table_series d,$this->table_cversion e")
               -> where("a.bid = b.id and a.gamever = c.id and a.gameseries=d.id and a.gamecver=e.id $cond")
               -> limit($page->start,$page->limit,'a.id desc')
               -> get()
               -> result_objects();

        foreach($res as &$obj){
             $obj -> version = $obj->series.'_'.$obj->version;
             $obj -> cversion = $obj->series.'_'.$obj->cversion;
              if(!empty($obj->mergetime))
             $obj -> mergetime = date('Y-m-d H:i:s',$obj->mergetime);
        }

        return $res;
    }

    private function getCondition($condition){
            $cond = '';
            $bid = $condition -> bid;
            $is_require_mergeid = $condition -> is_require_mergeid;
            $is_require_complexid = $condition -> is_require_complexid;

            if($bid > 0){
                $cond .= " and a.bid = $bid ";
            }

           if($is_require_complexid == 1){
                $cond .= " and a.complexid <> 0 ";
           }

           if($is_require_mergeid == 1){
                $cond .= " and a.mergeid <> 0 ";
           }

          return $cond;
    }

    public function save($obj)
    {
        // TODO: Implement save() method.
        // TODO: Implement save() method.
        $name = $obj -> name;
        $ip = $obj -> ip;
        $port = $obj -> port;
        $bid = $obj -> bid;
        $dbuser = $obj -> dbuser;
        $dbpwd = $obj -> dbpwd;
        $status = $obj -> status;
        $dynamic_dbname = $obj-> dynamic_dbname;
        $server_ip = $obj->server_ip;
        $server_port = $obj->server_port;
        $sid = isset($obj -> sid) ? $obj->sid : '';
        $payurl = $obj->payurl;
        $version = $obj->gamever;
        $cversion = $obj->gamecver;
        $stat = $status;
        $complex_server_id = $obj->complex_server_id;
        $ck_complexed = $obj->ck_complexed;
        $ck_complex_main = $obj->ck_complex_main;
        $complexid = 0;
        $complexflag = '';
        $complextime = 0;

        if(!empty($complex_server_id) && $ck_complexed == 1){
        
	          $buissnesser =  $this->db->select('a.name,b.name as bname')->from("$this->table_servers a,$this->table_buissnesser b")->where("a.bid = b.id and a.id = $complex_server_id")->get()->result_object();

	          $complexid = $complex_server_id;
	          $complexflag = 'C_'.$buissnesser -> bname.'_'.$buissnesser->name;
	          $complextime = time();
	          
	          
        }else if($ck_complex_main == 1){
	          $buissnesser =  $this->db->select('name')->from($this->table_buissnesser)->where("id = $bid")->get()->result_object();

               if(empty($obj->sid))$complexid = $obj->id;
               else $complexid = $obj->sid;
	          $complexflag = 'C_'.$buissnesser -> name.'_'.$name;
	          $complextime = time();
        }

        /**
         * sid的填写方式
         * 公式  服务器唯一标识 = 服务器ID + 运营商ID*10000;
         */

        //查询游戏版本属于的系列
        $series = $this->db->select('series') -> from($this->table_version) -> where(" id = $version") -> get() ->result_object() -> series;


        if(!isset($obj->id)){
            if($this->is_serverID_exists($sid))return FALSE;
            $sql = "insert into $this->table_servers (id,name,ip,port,bid,dbuser,dbpwd,status,stat,dynamic_dbname,server_ip,server_port,payurl,gamever,gameseries,gamecver,complexflag,complexid,complextime)
                    values ($sid,'$name','$ip',$port,$bid,'$dbuser','$dbpwd',$status,$stat,'$dynamic_dbname','$server_ip','$server_port','$payurl',$version,$series,$cversion,'$complexflag',$complexid,$complextime)";
        }else{
            $sql = "update $this->table_servers set name='$name',ip='$ip',port=$port,bid=$bid,dbuser='$dbuser',dbpwd='$dbpwd',status=$status,dynamic_dbname='$dynamic_dbname',server_ip='$server_ip',server_port='$server_port',stat=$stat,payurl='$payurl',gamever=$version,gameseries=$series,gamecver=$cversion,complexflag='$complexflag',complexid=$complexid,complextime=$complextime  where id = $obj->id";
        }

        if($this->db->query($sql)->queryState){
            return TRUE;
        }

        return FALSE;
    }

    public function edit($id)
    {
        // TODO: Implement edit() method.
        if(!empty($id)){
            $sql = "select a.*,b.name as buissnesser from $this->table_servers a ,$this->table_buissnesser b  where a.id = $id and a.bid = b.id";
            return $this->db->query($sql)->result_object();
        }
    }

    public function num_rows($condition)
    {
        // TODO: Implement num_rows() method.
        $cond = '';
        if($condition!=null)
        $cond = $this->getCondition($condition);
        $sql = "select count(a.id) as num from $this->table_servers a where a.stat=1 $cond";
        return $this -> db -> query($sql) -> result_object() -> num;
    }

    public function del($ids)
    {
        // TODO: Implement del() method.
        if(!empty($ids)){
            $sql = "update $this->table_servers set stat=0,status=0 where id in ($ids)";
            return $this -> db -> query($sql) -> queryState;
        }
    }

    public function getServer($bid){
        $sql = "select id,name,status,bid,dynamic_dbname,mergeflag,complexflag from $this->table_servers where bid = $bid and stat=1";
        $result = $this -> db -> query($sql) -> result_objects();
        foreach($result as &$res){
            switch($res->status){
                case 0: $res -> status = '否';break;

                case 1: $res -> status = '是';break;
            }
        }
        return $result;
    }

    public function getServerBySid($sid){
        $sql = "select id,name,status,bid,dynamic_dbname,mergeflag,complexflag from $this->table_servers where id = $sid";
        $result = $this -> db -> query($sql) -> result_object();
        return $result;
    }

    public function getServersByBids($buissnessers){
        $ids = '';
        foreach($buissnessers as $v){
            $ids .= $v -> id.',';
        }

        $ids = substr($ids,0,strlen($ids) - 1);
        $sql = "select a.id,a.name,a.status,a.bid,a.dynamic_dbname,a.server_ip,a.server_port,a.mergeflag,a.complexflag,b.name as bname from $this->table_servers a,$this->table_buissnesser b where a.bid = b.id and  bid in ($ids) and a.stat=1";
        $result = $this -> db -> query($sql) -> result_objects();
        foreach($result as &$res){
            switch($res->status){
                case 0: $res -> status = '否';break;

                case 1: $res -> status = '是';break;
            }
        }
        return $result;
    }

    function is_serverID_exists($id){
        $num = $this -> db -> select("count(name) as num") -> from("$this->table_servers")
            -> where("id = $id") -> get() -> result_object()->num;
        if($num > 0) return TRUE;
        return FALSE;
    }

    public function serversNoPageByExceptSid($condition){
            $sid = $condition->sid;
            $mergeid = $condition->mergeid;
            if($sid != $mergeid && $mergeid != 0 && !empty($mergeid)){//被合过的服 是不能被操作的
                 return null;
            }
            $bid = $condition->bid;//只有同一平台的才能合服
            $version = $condition->version;
            $series = $condition->series;
            $cversion = $condition->cversion;
            $list =  $this->db->select(" a.id,a.name,b.name as bname ") -> from("$this->table_servers a,$this->table_buissnesser b") -> where(" a.bid = $bid and a.bid = b.id and a.id <> $sid and a.gamever = $version and a.gameseries = $series and a.gamecver = $cversion and (a.mergeid = 0 or a.id = a.mergeid) and (a.complexid = 0 or a.id = a.complexid)") -> get() -> result_objects();
            foreach($list as &$obj){
               $obj -> name = $obj->bname.'_'.$obj->name;
             }
            return $list;
    }

}
