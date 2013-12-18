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
    }

    public function lists($page,$condition=null)
    {
        // TODO: Implement lists() method.
      /*  $sql = "select * from (select row_number() over (order by a.id desc) as rownumber, a.*,b.name as buissnesser from  $this->table_servers a,$this->table_buissnesser b where a.bid = b.id and a.stat=1 and b.stat=1) as t where t.rownumber > $page->start and t.rownumber <= $page->limit";
        $res = $this->db->query($sql)->result_objects();*/
        $res = $this -> db -> select(" a.*,b.name as buissnesser,c.name as series,c.version")
               -> from("$this->table_servers a,$this->table_buissnesser b,$this->table_version c")
               -> where("a.bid = b.id and a.gamever = c.id")
               -> limit($page->start,$page->limit,'a.id desc')
               -> get()
               -> result_objects();

        foreach($res as &$obj){
             $obj -> gamever = $obj->series.'_'.$obj->version;
             $obj -> mergetime = date('Y-m-d H:i:s',$obj->mergetime);
        }

        return $res;
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
        $stat = $status;
        /**
         * sid的填写方式
         * 公式  服务器唯一标识 = 服务器ID + 运营商ID*10000;
         */

        //查询游戏版本属于的系列
        $series = $this->db->select('series') -> from($this->table_version) -> where(" id = $version") -> get() ->result_object() -> series;

        if(!isset($obj->id)){
            if($this->is_serverID_exists($sid))return FALSE;
            $sql = "insert into $this->table_servers (id,name,ip,port,bid,dbuser,dbpwd,status,stat,dynamic_dbname,server_ip,server_port,payurl,gamever,gameseries)
                    values ($sid,'$name','$ip',$port,$bid,'$dbuser','$dbpwd',$status,$stat,'$dynamic_dbname','$server_ip','$server_port','$payurl',$version,$series)";
            error_log($sql);
        }else{
            $sql = "update $this->table_servers set name='$name',ip='$ip',port=$port,bid=$bid,dbuser='$dbuser',dbpwd='$dbpwd',status=$status,dynamic_dbname='$dynamic_dbname',server_ip='$server_ip',server_port='$server_port',stat=$stat,payurl='$payurl',gamever=$version,gameseries=$series where id = $obj->id";
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

    public function num_rows($null)
    {
        // TODO: Implement num_rows() method.
        $sql = "select count(id) as num from $this->table_servers where stat=1";
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
        $sql = "select id,name,status,bid,dynamic_dbname from $this->table_servers where bid = $bid and stat=1";
        $result = $this -> db -> query($sql) -> result_objects();
        foreach($result as &$res){
            switch($res->status){
                case 0: $res -> status = '否';break;

                case 1: $res -> status = '是';break;
            }
        }
        return $result;
    }

    public function getServersByBids($buissnessers){
        $ids = '';
        foreach($buissnessers as $v){
            $ids .= $v -> id.',';
        }

        $ids = substr($ids,0,strlen($ids) - 1);
        $sql = "select id,name,status,bid,dynamic_dbname,server_ip,server_port from $this->table_servers where bid in ($ids) and stat=1";
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

    public function serversNoPageByExceptSid($sid){
            $list =  $this->db->select(" a.id,a.name,b.name as bname ") -> from("$this->table_servers a,$this->table_buissnesser b") -> where(" a.bid = b.id and a.id <> $sid") -> get() -> result_objects();
            foreach($list as &$obj){
               $obj -> name = $obj->bname.'_'.$obj->name;
             }
            return $list;
    }

}
