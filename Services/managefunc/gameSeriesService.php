<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-10
 * Time: 上午11:43
 */

class GameSeriesService extends Service{
    function GameSeriesService(){
        parent::__construct();
        $this->table_series = DB_PREFIX.'series';
        $this->table_version = DB_PREFIX.'versions';
        $this->db->select_db(DB_NAME);
    }

    public function lists($page){
        return $this -> db -> select() -> from($this->table_series)
            -> limit($page->start,$page->limit,'id desc')
            -> get() -> result_objects();
    }

    public function listsNoPage($null){
        return $this -> db -> select() -> from($this->table_series)
            -> get() -> result_objects();
    }


    public function save($form){
        $name = $form->name;
        $desp = $form->desp;

        $params = array(
            'name' => $name,
            'desp' => $desp
        );

        if(!empty($form->id)){
            return $this->db->update($this->table_series,$params," id = $form->id");
        }

        return $this->db->insert($this->table_series,$params);
    }

    public function edit($id){
        return $this->db->select()->from($this->table_series) -> where("id = $id") -> get() -> result_object();
    }

    public function num_rows($condition){
        return $this->db->select("count(id) as num") -> from($this->table_series) -> get() -> result_object() -> num;
    }

    public function del($ids){
        if(!empty($ids)){
            $isDeletable = TRUE;
            $ids_array =  explode(',',$ids);
            //先查询系列中 是否有版本在使用该系列 如果有 不予删除
            foreach($ids_array as $id){
                 $num = $this->db->query("select count(id) as num from $this->table_version where series = $id") -> result_object() -> num;
                 if($num > 0){
                     $isDeletable = FALSE;
                     break;
                 }
            }

            if(!$isDeletable)return -1;


            $sql = "delete from $this->table_series where id in ($ids)";
            $this -> db -> query($sql);
            if($this -> db -> query($sql) -> queryState)return 1;
            return -2;
        }
    }
} 