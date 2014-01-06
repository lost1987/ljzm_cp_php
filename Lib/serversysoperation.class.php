<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-11
 * Time: 下午4:29
 */

class ServerSysOperation {
            private $resultlist = array();
            public static $OPERATION = array(
                 'open' => 4,
                 'stop' => 5,
                 'reboot'=>6,
                 'update'=>7,
                 'merge' =>8,
                 'rollback'=>9,
                 'clear' => 10,
                 'check_game_process' => 11
            );

            private $api_folder_name ;

            private $api_gateway;

            private  $success_servers = array(); //成功处理的服务器

            private $failed_servers = array(); //处理失败的服务器



    public function setOptions($api_folder_name,$api_gateway){
            $this->api_folder_name = $api_folder_name;
            $this->api_gateway = $api_gateway;
    }




    /**
     * @param $servers 包含服务器ip和id 属性的对象
     * @param $operation 操作 为 $operation数组中的之一
     * @param $validateCode 静态数据库ljzm_apikey里的id为1的值
     */
    public function send($servers,$operation,$validateCode,$fromServerids='',$filename='',$logid='',$version=''){
                    $time = time();
                    if(empty($this->api_folder_name) || empty($this->api_gateway))throw new Exception('options is unset');

                     foreach($servers as $server){
                         $validateKey = md5($time.$server->id.$validateCode);
                         $url = 'http://'.$server->ip.'/'.$this->api_folder_name.'/'.$this->api_gateway.'?';
                         $params = array(
                                'm' => $operation,
                                'sid' => $server->id,
                                'time' => $time,
                                'key' => $validateKey
                         );

                         if(!empty($fromServerids))
                             $params['from_server_ids'] = str_replace(',',' ',$fromServerids);

                         if(!empty($filename))
                                $params['filename'] = $filename;

                         if(!empty($logid))
                                $params['logid'] = $logid;

                         if(!empty($version))
                                $params['version'] = $version;

                         $ch = curl_init();
                         curl_setopt ($ch, CURLOPT_URL, $url.http_build_query($params));
//                         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//HTTPS特有设置
//                         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//HTTPS特有设置
                         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                         curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                         curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
                         $result =  curl_exec($ch);
                         //error_log($result);
                         if(empty($result))$result = -3;
                         $this->resultlist[$server->id] = $result;

                         if($result == 1){
                                  $this->success_servers[] = $server;
                         }else{
                                 $this->failed_servers[] = $server;
                         }

                     }
            }


            public function getSuccessServers(){
                return $this->success_servers;
            }

            public function getFailedServers(){
                 return $this->failed_servers;
            }

            public function getResults(){
                return $this->resultlist;
            }

}