/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : severaldev

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2019-03-12 16:44:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dm_doc_type
-- ----------------------------
DROP TABLE IF EXISTS `dm_doc_type`;
CREATE TABLE `dm_doc_type` (
  `doc_type_code` varchar(10) NOT NULL,
  `doc_type_desc` varchar(255) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`doc_type_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dm_doc_type
-- ----------------------------

-- ----------------------------
-- Table structure for dm_file
-- ----------------------------
DROP TABLE IF EXISTS `dm_file`;
CREATE TABLE `dm_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mast_id` int(10) unsigned NOT NULL,
  `phy_file_name` varchar(300) NOT NULL,
  `phy_path_name` varchar(100) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `archive` char(1) DEFAULT 'N',
  `remove` char(1) DEFAULT 'N',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_dm_file_01` (`mast_id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dm_file
-- ----------------------------
INSERT INTO `dm_file` VALUES ('124', '162', '9704c6b646eee06f86528150e8ff1428.xls', '/docman/dev/95/1', 'Book6.xls', 'application/vnd.ms-excel', 'N', 'Y', 'test', 'test', null, '2019-03-11 09:51:45');

-- ----------------------------
-- Table structure for dm_master
-- ----------------------------
DROP TABLE IF EXISTS `dm_master`;
CREATE TABLE `dm_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc_type_code` varchar(10) NOT NULL,
  `doc_id` int(10) NOT NULL,
  `remove` char(1) DEFAULT 'N',
  `lcu` varchar(30) DEFAULT NULL,
  `luu` varchar(30) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dm_master
-- ----------------------------
INSERT INTO `dm_master` VALUES ('162', 'CUST', '25', 'N', 'test', null, null, '2019-03-11 09:47:47');

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
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-17 11:25:20');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-17 11:26:40');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-17 11:26:49');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-17 11:34:51');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-17 11:48:34');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 14:57:33');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 14:58:22');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:02:45');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:02:57');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:15:13');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:16:17');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:16:35');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:18:26');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:22:53');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:32:43');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:32:55');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:33:26');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:34:22');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:57:20');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:57:32');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:58:19');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:59:02');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 15:59:38');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:00:03');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:05:20');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:06:26');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-17 16:07:14');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-17 16:07:56');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:08:26');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:18:36');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:33:23');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:34:42');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:34:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:35:22');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:35:32');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:36:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:39:14');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:42:10');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:42:31');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:43:56');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:47:31');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:47:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:49:32');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:49:41');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 16:50:13');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:11:10');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:11:15');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:12:19');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:14:00');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:22:58');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:23:34');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:27:29');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:27:52');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:29:31');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:29:37');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:30:16');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:30:20');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:32:27');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:32:31');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:51:24');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:51:26');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:51:29');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:51:57');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:52:31');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:53:19');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:54:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:55:18');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:55:41');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:59:18');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-17 17:59:31');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 09:29:49');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '127.0.0.1', '2018-12-18 09:30:15');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 09:30:42');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 09:31:29');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 10:32:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 10:33:28');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 10:33:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 10:34:41');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 10:35:27');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:35:43');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:35:57');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:36:14');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:38:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:39:08');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:39:13');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:39:29');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:39:33');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:39:43');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:40:06');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:41:02');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:41:33');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:42:04');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:42:19');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:44:15');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:44:29');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 10:44:37');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:47:08');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:47:15');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:50:04');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:50:08');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:50:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:50:43');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:51:10');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:52:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:53:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 10:54:42');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 10:57:58');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 11:21:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '::1', '2018-12-18 11:49:29');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 11:58:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 11:58:54');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 11:59:21');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 14:12:45');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 14:13:56');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 14:13:59');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 14:38:53');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 14:43:53');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2018-12-18 14:57:39');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-18 15:26:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2018-12-19 16:41:51');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2018-12-19 16:42:10');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2018-12-19 16:42:37');
INSERT INTO `sec_login_log` VALUES ('N/A', 'shenchao', '192.168.1.5', '2018-12-20 10:49:38');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-01-07 09:27:25');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-01-07 11:31:23');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-11 10:04:02');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-11 10:36:43');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-11 10:39:22');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-11 12:01:01');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-11 14:29:21');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-23 09:48:36');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-25 09:18:16');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-25 13:45:35');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-26 10:15:02');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-26 11:36:24');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-26 14:52:43');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-27 13:55:26');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-28 09:18:11');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-28 13:56:01');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-02-28 15:16:44');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-01 15:44:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-04 09:09:39');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-04 10:12:45');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-04 14:33:07');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-06 16:48:17');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-07 11:57:13');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-07 13:17:27');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-07 14:49:27');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-07 16:41:40');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-08 09:21:14');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-08 15:15:50');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-11 09:15:14');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-11 11:53:49');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-11 14:09:48');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-11 16:30:46');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-12 11:09:42');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-12 13:50:41');
INSERT INTO `sec_login_log` VALUES ('N/A', 'test', '192.168.1.5', '2019-03-12 14:19:32');

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
  `in_firm` text COMMENT '管轄公司',
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
INSERT INTO `sec_user` VALUES ('shenchao', '0b9540da4b19ee19aee19c610ed38f9c', 'Shen Chao', 'test@lbsgroup.com.cn', '2018-12-20 10:49:38', '2018-12-20 10:49:52', 'A', '0', 'N', null, '', 'HK', 'admin', 'test', '2017-02-22 00:35:16', '2018-12-20 10:49:52');
INSERT INTO `sec_user` VALUES ('test', '0b9540da4b19ee19aee19c610ed38f9c', 'test', 'test@lbsgroup.com.cn', '2019-03-12 14:19:32', '2019-03-12 16:44:09', 'A', '0', 'N', '78,79,80,81,82,83', '', 'HK', 'shenchao', 'test', null, '2019-03-12 16:44:09');

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
INSERT INTO `sec_user_access` VALUES ('shenchao', 'sev', '', 'CU01CU02XD01XR01XR02XR03XR04MR01MR02MR03MR04', '', 'admin', 'test', '2017-05-22 09:39:03', '2019-02-11 10:39:08');
INSERT INTO `sec_user_access` VALUES ('test', 'sev', '', 'CU01CU02MR01MR02XD01XR01XR02XR03XR04', '', 'shenchao', 'test', null, '2019-02-28 15:11:10');

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
INSERT INTO `sec_user_option` VALUES ('test', 'system', 'sev');

-- ----------------------------
-- Table structure for sev_company
-- ----------------------------
DROP TABLE IF EXISTS `sev_company`;
CREATE TABLE `sev_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_code` varchar(255) NOT NULL COMMENT '客戶編號',
  `customer_name` varchar(255) NOT NULL COMMENT '客戶名字',
  `group_id` int(11) DEFAULT NULL COMMENT '集團編號id',
  `z_index` varchar(100) DEFAULT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` date DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自己公司表單';

-- ----------------------------
-- Records of sev_company
-- ----------------------------

-- ----------------------------
-- Table structure for sev_customer
-- ----------------------------
DROP TABLE IF EXISTS `sev_customer`;
CREATE TABLE `sev_customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '客戶公司id',
  `staff_id` int(11) DEFAULT NULL COMMENT '負責員工',
  `salesman_id` int(11) DEFAULT NULL COMMENT '銷售員id',
  `group_id` int(11) DEFAULT NULL COMMENT '集團id',
  `customer_year` int(5) NOT NULL COMMENT '年份',
  `firm_name_id` text,
  `firm_name_us` text COMMENT '有權限公司名字',
  `lcu` varchar(100) DEFAULT NULL,
  `luu` varchar(100) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='會計追數表';

-- ----------------------------
-- Records of sev_customer
-- ----------------------------

-- ----------------------------
-- Table structure for sev_customer_firm
-- ----------------------------
DROP TABLE IF EXISTS `sev_customer_firm`;
CREATE TABLE `sev_customer_firm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `firm_id` int(11) NOT NULL,
  `curr` varchar(255) DEFAULT NULL COMMENT '貨幣類型',
  `amt` float(10,2) DEFAULT '0.00' COMMENT '剩餘數額',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='哪個公司可以管理追數客戶';

-- ----------------------------
-- Records of sev_customer_firm
-- ----------------------------

-- ----------------------------
-- Table structure for sev_customer_info
-- ----------------------------
DROP TABLE IF EXISTS `sev_customer_info`;
CREATE TABLE `sev_customer_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firm_cus_id` int(11) NOT NULL COMMENT 'customer_firm的id',
  `customer_id` int(11) NOT NULL,
  `amt_gt` int(2) NOT NULL DEFAULT '1' COMMENT '0:小於  1：等於',
  `amt_name` varchar(255) NOT NULL,
  `amt_num` float(10,2) NOT NULL DEFAULT '0.00',
  `lcu` varchar(50) DEFAULT NULL,
  `luu` varchar(50) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sev_customer_info
-- ----------------------------

-- ----------------------------
-- Table structure for sev_firm
-- ----------------------------
DROP TABLE IF EXISTS `sev_firm`;
CREATE TABLE `sev_firm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firm_name` varchar(255) NOT NULL COMMENT '公司名字',
  `z_index` varchar(100) DEFAULT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` date DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COMMENT='自己公司表單';

-- ----------------------------
-- Records of sev_firm
-- ----------------------------
INSERT INTO `sev_firm` VALUES ('78', 'LBS总公司', '99', 'test', null, '2019-02-27', '2019-02-27 14:21:14');
INSERT INTO `sev_firm` VALUES ('79', 'Air Puri', '88', 'test', null, '2019-02-27', '2019-02-27 14:21:31');
INSERT INTO `sev_firm` VALUES ('80', 'Envronment', '77', 'test', null, '2019-02-27', '2019-02-27 14:21:43');
INSERT INTO `sev_firm` VALUES ('81', 'Kitchen', '55', 'test', null, '2019-02-27', '2019-02-27 14:21:53');
INSERT INTO `sev_firm` VALUES ('82', 'Puriscent', '44', 'test', null, '2019-02-27', '2019-02-27 14:22:04');
INSERT INTO `sev_firm` VALUES ('83', 'Refreshment', '22', 'test', null, '2019-02-27', '2019-02-27 14:22:13');

-- ----------------------------
-- Table structure for sev_group
-- ----------------------------
DROP TABLE IF EXISTS `sev_group`;
CREATE TABLE `sev_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_code` varchar(255) DEFAULT NULL COMMENT '集團編號',
  `assign_id` int(11) DEFAULT NULL COMMENT '跟進員工id',
  `assign_date` date DEFAULT NULL COMMENT '指派日期',
  `cross_district` varchar(255) DEFAULT NULL COMMENT '跨區',
  `occurrences` int(11) NOT NULL DEFAULT '0' COMMENT '公司編號在欠款列表出現次數',
  `salesman_one` text COMMENT '負責的銷售員',
  `salesman_one_ts` text COMMENT '負責的銷售員',
  `z_index` int(11) DEFAULT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` date DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8 COMMENT='客戶公司表';

-- ----------------------------
-- Records of sev_group
-- ----------------------------
INSERT INTO `sev_group` VALUES ('73', 'AA10', '73', '2019-01-30', '*', '0', null, null, null, 'test', null, '2019-02-26', '2019-02-26 10:18:35');
INSERT INTO `sev_group` VALUES ('74', 'A010', '74', '2018-05-16', null, '0', null, null, null, null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_group` VALUES ('75', 'A020', '75', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_group` VALUES ('76', 'A030', '74', '2018-05-16', null, '0', null, null, null, null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_group` VALUES ('77', 'A040', '76', '2018-05-16', null, '2', '75,73', 'May Chiu,测试', null, null, null, null, '2019-03-08 10:24:33');
INSERT INTO `sev_group` VALUES ('78', 'A050', '74', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_group` VALUES ('79', 'A060', '74', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_group` VALUES ('80', 'A070', '74', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_group` VALUES ('81', 'A080', '74', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_group` VALUES ('82', 'A090', '77', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('83', 'A100', '76', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('84', 'A110', '74', '2018-05-16', null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('85', 'A120', '77', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('86', 'A130', '76', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('87', 'A140', '74', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('88', 'A150', '75', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('89', 'A160', '76', null, null, '0', null, null, null, null, null, null, '2019-02-26 10:27:58');
INSERT INTO `sev_group` VALUES ('90', 'AAT0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 11:13:16');
INSERT INTO `sev_group` VALUES ('91', 'AU70', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:03');
INSERT INTO `sev_group` VALUES ('92', 'AAS0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:04');
INSERT INTO `sev_group` VALUES ('93', 'BN70', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:04');
INSERT INTO `sev_group` VALUES ('94', 'BZ40', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:05');
INSERT INTO `sev_group` VALUES ('95', 'APX0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:05');
INSERT INTO `sev_group` VALUES ('96', 'AOS0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:06');
INSERT INTO `sev_group` VALUES ('97', 'AMCD', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:07');
INSERT INTO `sev_group` VALUES ('98', 'A280', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:09');
INSERT INTO `sev_group` VALUES ('99', 'CB30', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:10');
INSERT INTO `sev_group` VALUES ('100', 'AIA0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:12');
INSERT INTO `sev_group` VALUES ('101', 'BX80', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:12');
INSERT INTO `sev_group` VALUES ('102', 'BC20', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:13');
INSERT INTO `sev_group` VALUES ('103', 'A820', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:13');
INSERT INTO `sev_group` VALUES ('104', 'ACE0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:14');
INSERT INTO `sev_group` VALUES ('105', 'AMCF', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:15');
INSERT INTO `sev_group` VALUES ('106', 'AAR0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:16');
INSERT INTO `sev_group` VALUES ('107', 'AIV0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:18');
INSERT INTO `sev_group` VALUES ('108', 'AGH0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:19');
INSERT INTO `sev_group` VALUES ('109', 'AAQ0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:22');
INSERT INTO `sev_group` VALUES ('110', 'AQF0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:23');
INSERT INTO `sev_group` VALUES ('111', 'APY0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:25');
INSERT INTO `sev_group` VALUES ('112', 'AGZ0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:26');
INSERT INTO `sev_group` VALUES ('113', 'AWL0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:26');
INSERT INTO `sev_group` VALUES ('114', 'CB70', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:26');
INSERT INTO `sev_group` VALUES ('115', 'AOX0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:27');
INSERT INTO `sev_group` VALUES ('116', 'AQX0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:29');
INSERT INTO `sev_group` VALUES ('117', 'BT20', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:30');
INSERT INTO `sev_group` VALUES ('118', 'A3H0', null, null, null, '0', null, null, null, 'test', null, '2019-03-12', '2019-03-12 12:04:31');

-- ----------------------------
-- Table structure for sev_queue
-- ----------------------------
DROP TABLE IF EXISTS `sev_queue`;
CREATE TABLE `sev_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rpt_desc` varchar(250) NOT NULL,
  `req_dt` datetime DEFAULT NULL,
  `fin_dt` datetime DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  `rpt_type` varchar(10) NOT NULL,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rpt_content` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sev_queue
-- ----------------------------

-- ----------------------------
-- Table structure for sev_queue_param
-- ----------------------------
DROP TABLE IF EXISTS `sev_queue_param`;
CREATE TABLE `sev_queue_param` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `param_field` varchar(50) NOT NULL,
  `param_value` varchar(500) DEFAULT NULL,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sev_queue_param
-- ----------------------------

-- ----------------------------
-- Table structure for sev_queue_user
-- ----------------------------
DROP TABLE IF EXISTS `sev_queue_user`;
CREATE TABLE `sev_queue_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sev_queue_user
-- ----------------------------

-- ----------------------------
-- Table structure for sev_remark_list
-- ----------------------------
DROP TABLE IF EXISTS `sev_remark_list`;
CREATE TABLE `sev_remark_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firm_cus_id` int(11) NOT NULL COMMENT 'customer_firm的id',
  `remark` text NOT NULL,
  `lcu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sev_remark_list
-- ----------------------------
INSERT INTO `sev_remark_list` VALUES ('1', '25', '5558', 'test', '2019-03-11 10:16:01');
INSERT INTO `sev_remark_list` VALUES ('2', '25', '1111', 'test', '2019-03-11 10:18:24');
INSERT INTO `sev_remark_list` VALUES ('3', '23', '777', 'test', '2019-03-11 10:21:38');

-- ----------------------------
-- Table structure for sev_staff
-- ----------------------------
DROP TABLE IF EXISTS `sev_staff`;
CREATE TABLE `sev_staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(255) NOT NULL COMMENT '员工名字',
  `staff_type` int(3) DEFAULT '1' COMMENT '員工類型：2:銷售  1:技術員',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` date DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COMMENT='客戶公司表';

-- ----------------------------
-- Records of sev_staff
-- ----------------------------
INSERT INTO `sev_staff` VALUES ('73', '测试', '1', 'test', null, '2019-02-26', '2019-02-26 10:17:48');
INSERT INTO `sev_staff` VALUES ('74', 'May Yung', '1', null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_staff` VALUES ('75', 'May Chiu', '1', null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_staff` VALUES ('76', 'LISA', '1', null, null, null, '2019-02-26 10:27:57');
INSERT INTO `sev_staff` VALUES ('77', '(NO O/S)', '1', null, null, null, '2019-02-26 10:27:58');
