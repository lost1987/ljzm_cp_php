<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lost
 * Date: 13-3-6
 * Time: 上午11:42
 * To change this template use File | Settings | File Templates.
 */

//账号数据库,分发数据库


define('DB_HOST','127.0.0.1');
define('DB_NAME','mmo2d_admin');
define('DB_USER','root');
//define('DB_PWD','li/5210270');
define('DB_PWD','');
define('DB_TYPE','Mysql');
define('DB_PORT','3306');
define('DB_PREFIX','ljzm_');
define('DB_STATIC','mmo2d_staticljzm');
define('DB_BASE','mmo2d_baseljzm');
define('TB_APIKEY',DB_PREFIX.'apikey');

/*
define('DB_HOST','221.228.196.138');
define('DB_NAME','mmo2d_admin');
define('DB_USER','root');
define('DB_PWD','li/5210270');
define('DB_TYPE','Mysql');
define('DB_PORT','3306');
define('DB_PREFIX','ljzm_');
define('DB_STATIC','mmo2d_staticljzm');
define('DB_BASE','mmo2d_baseljzm');
define('TB_APIKEY',DB_PREFIX.'apikey');*/
/*
define('DB_HOST','192.168.20.229');
define('DB_NAME','mmo2d_admin');
define('DB_USER','root');
//define('DB_PWD','li/5210270');
define('DB_PWD','1234');
define('DB_TYPE','Mysql');
define('DB_PORT','3306');
define('DB_PREFIX','ljzm_');
define('DB_STATIC','mmo2d_staticljzm');
define('DB_BASE','mmo2d_baseljzm');*/


//define('MEMCACHED_HOST','115.238.101.156');
define('MEMCACHED_HOST','192.168.20.229');//内部测试
define('MEMCACHED_PORT','11211');
define('MEMCACHED_TIMEOUT',60*60*12);//12小时
