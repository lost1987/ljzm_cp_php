/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50519
Source Host           : 127.0.0.1:3306
Source Database       : mmo2d_admin

Target Server Type    : MYSQL
Target Server Version : 50519
File Encoding         : 65001

Date: 2013-11-16 14:04:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `areainfo`
-- ----------------------------
DROP TABLE IF EXISTS `areainfo`;
CREATE TABLE `areainfo` (
  `areaid` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`areaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of areainfo
-- ----------------------------
INSERT INTO `areainfo` VALUES ('1', '360');
INSERT INTO `areainfo` VALUES ('2', '370');
INSERT INTO `areainfo` VALUES ('3', '可可国');

-- ----------------------------
-- Table structure for `gmlog`
-- ----------------------------
DROP TABLE IF EXISTS `gmlog`;
CREATE TABLE `gmlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ctrlid` int(11) NOT NULL,
  `areaid` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `param0` int(11) NOT NULL,
  `curtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gmlog
-- ----------------------------

-- ----------------------------
-- Table structure for `ljzm_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_admin`;
CREATE TABLE `ljzm_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` varchar(32) NOT NULL,
  `passwd` varchar(32) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '0',
  `bid` int(11) NOT NULL,
  `flagname` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_admin
-- ----------------------------
INSERT INTO `ljzm_admin` VALUES ('1', '6jadmin', 'ffbf9dbde595c9e6c7bcffa19555d2eb', '2047', '-1', '齐进管理员');
INSERT INTO `ljzm_admin` VALUES ('15', 'buhuan', '9d4a95bb5ec5b9da97901c6067114484', '2047', '-1', '卜峘');
INSERT INTO `ljzm_admin` VALUES ('21', 'liuchao', '7a7b9f7facf4492cbcb962026bd5f491', '2047', '-1', '刘超');
INSERT INTO `ljzm_admin` VALUES ('22', 'jiuqu_admin', 'e9ccf2253abb645e849f94005b435092', '1023', '-1', '最高管理帐号');
INSERT INTO `ljzm_admin` VALUES ('23', 'jiuqu_yunying', 'e9ccf2253abb645e849f94005b435092', '511', '-1', '运营帐号');
INSERT INTO `ljzm_admin` VALUES ('24', 'jiuqu_kefu', 'e9ccf2253abb645e849f94005b435092', '248', '-1', '客服帐号');
INSERT INTO `ljzm_admin` VALUES ('25', 'jiuqu_jihuoma', 'e9ccf2253abb645e849f94005b435092', '188', '-1', '激活码帐号');

-- ----------------------------
-- Table structure for `ljzm_admin_apply`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_admin_apply`;
CREATE TABLE `ljzm_admin_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` varchar(32) NOT NULL,
  `passwd` varchar(32) NOT NULL,
  `state` tinyint(3) unsigned NOT NULL,
  `applytime` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `op_admin` varchar(32) NOT NULL,
  `op_adminid` int(11) NOT NULL,
  `buissnesser` varchar(32) NOT NULL,
  `flagname` varchar(32) NOT NULL DEFAULT '''''',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_admin_apply
-- ----------------------------

-- ----------------------------
-- Table structure for `ljzm_buissnesser`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_buissnesser`;
CREATE TABLE `ljzm_buissnesser` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `stat` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `bkey` varchar(100) NOT NULL,
  `bflag` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_buissnesser
-- ----------------------------
INSERT INTO `ljzm_buissnesser` VALUES ('1', '久趣', '1', 'fa679424d8186ed4273a1eb1c1dbba302749ad71', 'jiuqu');
INSERT INTO `ljzm_buissnesser` VALUES ('2', 'Q9999', '1', '9a8bbe9a7e829556cdb10ae477c957ddcc77a223', 'Q9999');
INSERT INTO `ljzm_buissnesser` VALUES ('3', '101yo', '1', '47257cb9e74a691899ae355a4db3c99c4541a545', '101yo');
INSERT INTO `ljzm_buissnesser` VALUES ('4', '17miyou', '1', 'fb3b9ac45bdf05532a9b3eae79855df4c6929a33', '17miyou');
INSERT INTO `ljzm_buissnesser` VALUES ('5', '311wan', '1', 'ccf61253ea50ae2da01d852523a6dbc458fc9a51', '311wan');
INSERT INTO `ljzm_buissnesser` VALUES ('6', '33456', '1', 'b7e9dfa40e5a73c3851ff57e4759603c05d6cd7b', '33456');
INSERT INTO `ljzm_buissnesser` VALUES ('7', '51wanw', '1', '0c7eaf26ee7936017ff5d9f0ff2a7cfd272b5106', '51wanw');
INSERT INTO `ljzm_buissnesser` VALUES ('8', '65wan', '1', 'f248fc4ae684ba84905b2d2e7d37ff9f04005c23', '65wan');
INSERT INTO `ljzm_buissnesser` VALUES ('9', '76zu', '1', 'cb97ebcb96dbbefebf2f8e668cdb938d6dfd7f9e', '76zu');
INSERT INTO `ljzm_buissnesser` VALUES ('10', '8090', '1', 'bbbfd4e7ddcf0fd1ef4d404eef2e2dd5c229c872', '8090');
INSERT INTO `ljzm_buissnesser` VALUES ('11', '9137game', '1', '0a016f090c254e8f0cda53c91fbfaf177bca8931', '9137game');
INSERT INTO `ljzm_buissnesser` VALUES ('12', '91牛', '1', 'c3886b21ffc5b793bdec692e6c0f5d7ff467a920', '91niu');
INSERT INTO `ljzm_buissnesser` VALUES ('13', '959wan', '1', 'b8b401ad00897bdd7bb5ea91b7c2ec5454c5598a', '959wan');
INSERT INTO `ljzm_buissnesser` VALUES ('14', 'lcyx', '1', '57b48db6110d4ccf2304bb530a92926bf86fe010', 'lcyx');
INSERT INTO `ljzm_buissnesser` VALUES ('15', 'uxi78', '1', '15816372b17bc3b083b47f1e5098d96a31347627', 'uxi78');
INSERT INTO `ljzm_buissnesser` VALUES ('16', 'wan669', '1', 'eeee065f6cb26f47fd5026a89e0a7c06cb71a77c', 'wan669');
INSERT INTO `ljzm_buissnesser` VALUES ('17', '雷讯', '1', '89673d5919031d1d2f127ec6c8a219e1f7617ebc', 'leixun');
INSERT INTO `ljzm_buissnesser` VALUES ('18', '60游戏', '1', '7ecf684cdd9eb7b92359cc6389d8e2ca685eed11', '60youxi');
INSERT INTO `ljzm_buissnesser` VALUES ('19', '8787wan', '1', 'ec90cdaf3481c97eec4a220d89103e2757348538', '8787wan');
INSERT INTO `ljzm_buissnesser` VALUES ('20', '南瓜网', '1', '9141a53158c0e6d95c9c0e3786f0274adcab8e2b', 'nanguawang');
INSERT INTO `ljzm_buissnesser` VALUES ('21', '沁雨', '1', '5cdb3e33abfea9889a4550bcfaf73fa267da5dc3', 'qinyu');
INSERT INTO `ljzm_buissnesser` VALUES ('22', '三界', '1', '879fd8c4892f12bec787ac811cd40ae0027b0179', 'sanjie');
INSERT INTO `ljzm_buissnesser` VALUES ('23', '天海', '1', '26f5092f3e2bdd8c22deee3b429a303be1f145df', 'tianhai');
INSERT INTO `ljzm_buissnesser` VALUES ('24', '星碟', '1', '7678d504a8a51dad062157e39056bd6e900482e4', 'xingdie');

-- ----------------------------
-- Table structure for `ljzm_mail_records`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_mail_records`;
CREATE TABLE `ljzm_mail_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL,
  `playername` varchar(32) NOT NULL,
  `playerid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lid` (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_mail_records
-- ----------------------------

-- ----------------------------
-- Table structure for `ljzm_mergeservers`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_mergeservers`;
CREATE TABLE `ljzm_mergeservers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromserverids` varchar(100) NOT NULL,
  `toserverid` int(11) NOT NULL,
  `mergetime` int(11) NOT NULL,
  `fromservernames` varchar(512) NOT NULL,
  `toservername` varchar(32) NOT NULL,
  `stat` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=未审核 2=同意  3=不同意',
  `tobuissnesser` varchar(32) NOT NULL,
  `tobid` int(11) NOT NULL,
  `frombids` varchar(100) NOT NULL,
  `frombuissnessers` varchar(512) NOT NULL,
  `applytime` int(11) NOT NULL,
  `optime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='合服申请表';

-- ----------------------------
-- Records of ljzm_mergeservers
-- ----------------------------

-- ----------------------------
-- Table structure for `ljzm_openservers`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_openservers`;
CREATE TABLE `ljzm_openservers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL COMMENT '开服时间',
  `bid` int(11) NOT NULL,
  `buissnesser` varchar(32) NOT NULL,
  `servername` varchar(32) NOT NULL,
  `serverip` varchar(100) NOT NULL,
  `stat` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=未审核 2= 允许 3=拒绝',
  `applytime` int(11) NOT NULL,
  `optime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='开服申请表';

-- ----------------------------
-- Records of ljzm_openservers
-- ----------------------------
INSERT INTO `ljzm_openservers` VALUES ('1', '1384589400', '1', '久趣', '双线1服', 'http://ljzm.9797wan.com/s1', '3', '1384581823', '1384581835');

-- ----------------------------
-- Table structure for `ljzm_permission`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_permission`;
CREATE TABLE `ljzm_permission` (
  `id` int(11) NOT NULL,
  `sjbb` int(11) NOT NULL,
  `lsl` int(11) NOT NULL,
  `lchy` int(11) NOT NULL,
  `yxjsxx` int(11) NOT NULL,
  `rzgl1` int(11) NOT NULL,
  `xtgl` int(11) NOT NULL,
  `yygl` int(11) NOT NULL,
  `glygl` int(11) NOT NULL,
  `zhgl` int(11) NOT NULL,
  `rzgl2` int(11) NOT NULL DEFAULT '0',
  `rzgl3` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_permission
-- ----------------------------
INSERT INTO `ljzm_permission` VALUES ('1', '31', '7', '15', '1', '8191', '15', '15', '7', '3', '4095', '8191');
INSERT INTO `ljzm_permission` VALUES ('15', '127', '7', '15', '1', '16383', '15', '15', '7', '3', '4095', '8191');
INSERT INTO `ljzm_permission` VALUES ('21', '127', '7', '15', '1', '16383', '15', '15', '7', '3', '4095', '8191');
INSERT INTO `ljzm_permission` VALUES ('22', '127', '7', '15', '1', '16383', '15', '15', '4', '0', '4095', '8191');
INSERT INTO `ljzm_permission` VALUES ('23', '127', '7', '15', '1', '16383', '10', '15', '0', '0', '4095', '8191');
INSERT INTO `ljzm_permission` VALUES ('24', '0', '0', '0', '1', '16383', '10', '0', '0', '0', '4095', '8191');
INSERT INTO `ljzm_permission` VALUES ('25', '0', '0', '8', '1', '1', '6', '0', '0', '0', '128', '0');

-- ----------------------------
-- Table structure for `ljzm_reward_records`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_reward_records`;
CREATE TABLE `ljzm_reward_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL,
  `playername` varchar(32) NOT NULL,
  `playerid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lid_copy` (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_reward_records
-- ----------------------------

-- ----------------------------
-- Table structure for `ljzm_servers`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_servers`;
CREATE TABLE `ljzm_servers` (
  `name` varchar(32) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `stat` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `port` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `dbuser` varchar(32) NOT NULL,
  `dbpwd` varchar(32) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dynamic_dbname` varchar(32) NOT NULL,
  `server_ip` varchar(32) DEFAULT NULL,
  `server_port` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_servers
-- ----------------------------
INSERT INTO `ljzm_servers` VALUES ('测试服务器', '221.228.203.17', '1', '3306', '1', 'root', 'li/5210270', '1', 'mmo2d_userljzm1', '221.228.203.17', '5001', '10001');

-- ----------------------------
-- Table structure for `ljzm_syslog`
-- ----------------------------
DROP TABLE IF EXISTS `ljzm_syslog`;
CREATE TABLE `ljzm_syslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` varchar(32) NOT NULL,
  `flagname` varchar(32) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `donetime` varchar(100) NOT NULL,
  `server_id` int(11) NOT NULL,
  `server_name` varchar(32) NOT NULL,
  `refer_id` int(11) NOT NULL,
  `refer_name` varchar(32) NOT NULL,
  `typename` varchar(32) NOT NULL,
  `aid` int(11) NOT NULL,
  `itemid` int(11) DEFAULT NULL,
  `itemnum` int(11) DEFAULT '0',
  `content` text,
  `title` varchar(100) DEFAULT NULL,
  `state` tinyint(3) unsigned DEFAULT NULL,
  `optime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_donetime` (`donetime`),
  KEY `idx_refer_id` (`refer_id`),
  KEY `idx_refer_name` (`refer_name`),
  KEY `idx_server_id` (`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ljzm_syslog
-- ----------------------------

-- ----------------------------
-- Table structure for `requestinfo`
-- ----------------------------
DROP TABLE IF EXISTS `requestinfo`;
CREATE TABLE `requestinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `requesttype` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accepttime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `requeststate` int(11) NOT NULL DEFAULT '0',
  `areaid` int(11) NOT NULL,
  `serverid` int(11) NOT NULL DEFAULT '0',
  `param0_str` text,
  `param1_str` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of requestinfo
-- ----------------------------
INSERT INTO `requestinfo` VALUES ('1', '1', '1', '2012-09-13 19:36:00', '2012-09-13 19:36:00', '1', '1', '1', null, null);
INSERT INTO `requestinfo` VALUES ('2', '1', '1', '2012-09-17 00:02:00', '2012-09-17 00:02:00', '0', '1', '0', '12-09-20', '123');
INSERT INTO `requestinfo` VALUES ('3', '1', '1', '2012-09-17 00:02:00', '2012-09-17 00:02:00', '0', '1', '0', '12-09-20', '456');

-- ----------------------------
-- Table structure for `serverinfo`
-- ----------------------------
DROP TABLE IF EXISTS `serverinfo`;
CREATE TABLE `serverinfo` (
  `serverid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `areaid` int(11) NOT NULL,
  `areaindex` int(11) NOT NULL,
  `serverip` varchar(32) NOT NULL,
  `serverport` int(11) NOT NULL DEFAULT '5000',
  `dbconname` varchar(32) NOT NULL,
  `dbname` varchar(32) NOT NULL,
  `dbuser` varchar(32) NOT NULL,
  `dbpwd` varchar(32) NOT NULL,
  `isclose` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serverid`),
  KEY `IX_serverinfo` (`areaid`),
  KEY `IX_serverinfo_1` (`areaindex`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of serverinfo
-- ----------------------------
INSERT INTO `serverinfo` VALUES ('1', '测试服务器', '1', '1', '123.150.106.130', '5001', '123.150.106.130', 'mmo2d_userljzm1', 'root', 'li/5210270', '0');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `areaid` int(11) NOT NULL DEFAULT '-1',
  `rightcode` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'zhs007', 'zhs007', '-1', '0');
