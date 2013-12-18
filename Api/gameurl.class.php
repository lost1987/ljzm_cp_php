<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-9
 * Time: 下午4:21
 */

class Gameurl extends Baseapi{
        function __construct(){
              parent::__construct();
              $this->table_server = 'ljzm_servers';
        }

        public function payurl(){
            echo $this->server->payurl;
        }
} 