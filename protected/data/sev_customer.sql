/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : severaldev

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2018-12-18 09:58:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sev_customer
-- ----------------------------
DROP TABLE IF EXISTS `sev_customer`;
CREATE TABLE `sev_customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(100) DEFAULT NULL COMMENT '客戶編號',
  `customer_name` varchar(150) NOT NULL COMMENT '客戶名字',
  `customer_year` int(5) NOT NULL COMMENT '年份',
  `company_code` varchar(100) DEFAULT NULL COMMENT '公司編號',
  `curr` varchar(10) DEFAULT NULL,
  `amt` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '剩餘數額',
  `city` varchar(20) DEFAULT NULL,
  `lcu` varchar(100) DEFAULT NULL,
  `luu` varchar(100) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='客戶表';

-- ----------------------------
-- Table structure for sev_customer_info
-- ----------------------------
DROP TABLE IF EXISTS `sev_customer_info`;
CREATE TABLE `sev_customer_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `amt_gt` int(2) NOT NULL DEFAULT '1' COMMENT '0:小於  1：等於',
  `amt_name` varchar(255) NOT NULL,
  `amt_num` float(10,2) NOT NULL DEFAULT '0.00',
  `lcu` varchar(50) DEFAULT NULL,
  `luu` varchar(50) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
