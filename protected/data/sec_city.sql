/*
Navicat MySQL Data Transfer

Source Server         : 会计追数
Source Server Version : 50541
Source Host           : pma.vps8094.youdomain.hk:3306
Source Database       : arsystem_db

Target Server Type    : MYSQL
Target Server Version : 50541
File Encoding         : 65001

Date: 2018-12-17 11:22:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sec_city
-- ----------------------------
DROP TABLE IF EXISTS `sec_city`;
CREATE TABLE `sec_city` (
  `code` char(5) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `region` char(5) DEFAULT NULL,
  `incharge` varchar(30) DEFAULT NULL,
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sec_city
-- ----------------------------
INSERT INTO `sec_city` VALUES ('HK', 'Hong Kong', '', '', 'admin', 'admin', '2018-12-14 12:06:08', '2018-12-14 15:16:39');

-- ----------------------------
-- Table structure for sec_login_log
-- ----------------------------
DROP TABLE IF EXISTS `sec_login_log`;
CREATE TABLE `sec_login_log` (
  `station_id` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `client_ip` varchar(20) DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sec_login_log
-- ----------------------------
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 17:52:37');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 17:53:11');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 17:53:18');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 17:55:42');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 17:59:30');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 17:59:54');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:01:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:02:27');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:05:17');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:08:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:09:54');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:10:00');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:10:08');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:10:56');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:11:31');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.36.39', '2018-12-14 18:11:47');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:27:32');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:27:38');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:29:18');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:29:22');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:30:54');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:30:59');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:42:03');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:42:50');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 09:43:56');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 10:29:34');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 10:29:41');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 10:34:36');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 10:34:41');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 10:37:01');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 10:59:57');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:00:04');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:00:51');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:02:13');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:10:40');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:11:16');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:11:33');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:13:47');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:14:06');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:15:23');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:15:34');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:16:44');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:16:49');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:17:19');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '14.116.37.142', '2018-12-17 11:17:24');

-- ----------------------------
-- Table structure for sec_user
-- ----------------------------
DROP TABLE IF EXISTS `sec_user`;
CREATE TABLE `sec_user` (
  `username` varchar(30) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `disp_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logon_time` datetime DEFAULT NULL,
  `logoff_time` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `fail_count` tinyint(3) unsigned DEFAULT '0',
  `locked` char(1) DEFAULT 'N',
  `session_key` varchar(500) DEFAULT NULL,
  `city` char(5) NOT NULL DEFAULT '',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sec_user
-- ----------------------------
INSERT INTO `sec_user` VALUES ('shenchao', '0b9540da4b19ee19aee19c610ed38f9c', 'Shen Chao', 'test@lbsgroup.com.cn', '2018-12-17 11:17:24', '2018-12-17 11:11:26', 'A', '0', 'N', '2986c7c5b9be6732f2bc6e9794f2a5e2', 'HK', 'admin', 'test', '2017-02-22 00:35:16', '2018-12-17 11:17:24');

-- ----------------------------
-- Table structure for sec_user_access
-- ----------------------------
DROP TABLE IF EXISTS `sec_user_access`;
CREATE TABLE `sec_user_access` (
  `username` varchar(30) NOT NULL,
  `system_id` varchar(15) NOT NULL,
  `a_read_only` varchar(255) DEFAULT '',
  `a_read_write` varchar(255) DEFAULT '',
  `a_control` varchar(255) DEFAULT '',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_sec_user_access_01` (`username`,`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sec_user_access
-- ----------------------------
INSERT INTO `sec_user_access` VALUES ('shenchao', 'sev', '', 'CU01CU02', '', 'admin', 'test', '2017-05-22 09:39:03', '2018-11-09 14:10:44');

-- ----------------------------
-- Table structure for sec_user_info
-- ----------------------------
DROP TABLE IF EXISTS `sec_user_info`;
CREATE TABLE `sec_user_info` (
  `username` varchar(30) NOT NULL,
  `field_id` varchar(30) NOT NULL,
  `field_value` varchar(2000) DEFAULT NULL,
  `field_blob` longblob,
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `user_info` (`username`,`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sec_user_info
-- ----------------------------

-- ----------------------------
-- Table structure for sec_user_option
-- ----------------------------
DROP TABLE IF EXISTS `sec_user_option`;
CREATE TABLE `sec_user_option` (
  `username` varchar(30) NOT NULL,
  `option_key` varchar(30) NOT NULL,
  `option_value` varchar(255) DEFAULT NULL,
  UNIQUE KEY `idx_sec_user_option_1` (`username`,`option_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sec_user_option
-- ----------------------------
INSERT INTO `sec_user_option` VALUES ('shenchao', 'system', 'sev');
