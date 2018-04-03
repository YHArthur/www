/*
Navicat MySQL Data Transfer

Source Server         : www.fnying.com
Source Server Version : 50173
Source Host           : 
Source Database       : 

Target Server Type    : MYSQL
Target Server Version : 50173
File Encoding         : 65001

Date: 2018-03-27 18:08:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for www_contact
-- ----------------------------
DROP TABLE IF EXISTS `www_contact`;
CREATE TABLE `www_contact` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `uuid` varchar(50) NOT NULL COMMENT '用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '用户称呼',
  `user_email` varchar(50) NOT NULL DEFAULT '' COMMENT '用户Email',
  `user_suggestion` text COMMENT '用户建议',
  `user_ip` int(11) NOT NULL DEFAULT '0' COMMENT '用户IP地址',
  `utime` int(11) DEFAULT '0' COMMENT '更新时间戳',
  `ctime` datetime DEFAULT NULL COMMENT '提交时间',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户建议表';
