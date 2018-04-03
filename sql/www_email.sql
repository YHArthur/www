/*
Navicat MySQL Data Transfer

Source Server         : www.fnying.com
Source Server Version : 50173
Source Host           : 
Source Database       : 

Target Server Type    : MYSQL
Target Server Version : 50173
File Encoding         : 65001

Date: 2018-03-27 18:08:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for www_email
-- ----------------------------
DROP TABLE IF EXISTS `www_email`;
CREATE TABLE `www_email` (
  `logid` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `email` varchar(50) NOT NULL COMMENT 'Email地址',
  `uuid` varchar(50) NOT NULL COMMENT '用户ID',
  `user_ip` int(11) NOT NULL DEFAULT '0' COMMENT '用户IP地址',
  `is_void` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否取消订阅',
  `utime` int(11) DEFAULT '0' COMMENT '更新时间戳',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`logid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='用户邮件列表';
