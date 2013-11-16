<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lost
 * Date: 13-4-7
 * Time: 下午1:42
 * To change this template use File | Settings | File Templates.
 */
class ItemService extends ServerDBChooser
{
    function ItemService(){
        $this -> table_items = $this->prefix_1.'item';
    }

    public function lists($server){
        $list = array();
        if(!empty($server)){
			$db = $this->getNewStaticDB();
            $sql = "select id,name from $this->table_items";
            $list =  $db -> query($sql) -> result_objects();
      
        }
        return $list;
    }

    public function getCondition($condition){}
}
?>