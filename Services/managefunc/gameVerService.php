<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-10
 * Time: 上午9:56
 */

class GameVerService extends Service{
        function GameVerService(){
            parent::__construct();
            $this->table_version = DB_PREFIX.'versions';
            $this->table_server = DB_PREFIX.'servers';
            $this->table_series = DB_PREFIX.'series';
            $this->db->select_db(DB_NAME);
        }

        public function lists($page){
            return $this -> db -> select() -> from($this->table_version)
                         -> limit($page->start,$page->limit,'id desc')
                        -> get() -> result_objects();
        }


        public function save($form){
                $version = $form->version;
                $series_id = $form->series_id;
                $series_name = $form->series_name;

                $params = array(
                    'version' => $version,
                    'series' => $series_id,
                    'name' => $series_name
                );

                if(!empty($form->id)){
                    return $this->db->update($this->table_version,$params," id = $form->id");
                }

                return $this->db->insert($this->table_version,$params);
        }

        public function edit($id){
               return $this->db->select()->from($this->table_version) -> where("id = $id") -> get() -> result_object();
        }

        public function num_rows($condition){
             return $this->db->select("count(id) as num") -> from($this->table_version) -> get() -> result_object() -> num;
        }

        public function del($ids){
            if(!empty($ids)){

                $isDeletable = TRUE;
                $ids_array =  explode(',',$ids);
                //先查询系列中 是否有版本在使用该系列 如果有 不予删除
                foreach($ids_array as $id){
                    $num = $this->db->query("select count(id) as num from $this->table_server where gamever = $id") -> result_object() -> num;
                    if($num > 0){
                        $isDeletable = FALSE;
                        break;
                    }
                }

                if(!$isDeletable)return -1;

                $sql = "delete from $this->table_version where id in ($ids)";
                $this -> db -> query($sql);
                if($this -> db -> query($sql) -> queryState)return 1;
                return -2;
            }
        }

        public function listsNoPage($null){
            $list = $this -> db -> select() -> from($this->table_version)
                -> get() -> result_objects();

            foreach($list as &$obj){
                  $obj -> version = $obj->name.'_'.$obj->version;
            }
            return $list;
        }

        public function listsNoPageBySeries($series){
            $list = $this -> db -> select() -> from($this->table_version) ->where("series = $series")
                -> get() -> result_objects();

            return $list;
        }

       public function listsNoPageBySeriesAndVersion($series,$version){
           $list = $this -> db -> select("a.*,b.name as seriesname") -> from("$this->table_version a , $this->table_series b") ->where("a.series = $series and a.series = b.id and a.id <> $version")
               -> get() -> result_objects();

           return $list;
       }
}