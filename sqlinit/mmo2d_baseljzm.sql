/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50133
Source Host           : 127.0.0.1:3306
Source Database       : mmo2d_baseljzm

Target Server Type    : MYSQL
Target Server Version : 50133
File Encoding         : 65001

Date: 2013-11-14 16:58:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `fr2_activecode`
-- ----------------------------
DROP TABLE IF EXISTS `fr2_activecode`;
CREATE TABLE `fr2_activecode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acode` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `astate` int(11) NOT NULL DEFAULT '0',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NULL DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `amask` int(11) NOT NULL DEFAULT '0',
  `itemid0` int(11) DEFAULT '0',
  `nums0` int(11) DEFAULT NULL,
  `itemid1` int(11) DEFAULT NULL,
  `nums1` int(11) DEFAULT NULL,
  `itemid2` int(11) DEFAULT NULL,
  `nums2` int(11) DEFAULT NULL,
  `itemid3` int(11) DEFAULT NULL,
  `nums3` int(11) DEFAULT NULL,
  `itemid4` int(11) DEFAULT NULL,
  `nums4` int(11) DEFAULT NULL,
  `itemid5` int(11) DEFAULT NULL,
  `nums5` int(11) DEFAULT NULL,
  `itemid6` int(11) DEFAULT NULL,
  `nums6` int(11) DEFAULT NULL,
  `itemid7` int(11) DEFAULT NULL,
  `nums7` int(11) DEFAULT NULL,
  `aid` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fr2_activecode
-- ----------------------------

-- ----------------------------
-- Table structure for `fr2_base`
-- ----------------------------
DROP TABLE IF EXISTS `fr2_base`;
CREATE TABLE `fr2_base` (
  `aountid` int(11) NOT NULL AUTO_INCREMENT,
  `loginname` varchar(33) NOT NULL,
  `password` varchar(33) NOT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `yuanbao` int(11) NOT NULL DEFAULT '0',
  `yuanbaonum` int(11) NOT NULL DEFAULT '0',
  `areaid` int(11) NOT NULL DEFAULT '1',
  `onlinttime` int(11) NOT NULL DEFAULT '0',
  `todayonlinetime` int(11) NOT NULL DEFAULT '0',
  `rightcode` int(11) NOT NULL DEFAULT '1',
  `lastlogintime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ydiamond` int(11) NOT NULL DEFAULT '-1',
  `qqsrvinfo` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`aountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fr2_base
-- ----------------------------

-- ----------------------------
-- Table structure for `fr2_payinfo`
-- ----------------------------
DROP TABLE IF EXISTS `fr2_payinfo`;
CREATE TABLE `fr2_payinfo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eventid` varchar(32) NOT NULL,
  `paytime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IX_fr2_payinfo` (`eventid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fr2_payinfo
-- ----------------------------
