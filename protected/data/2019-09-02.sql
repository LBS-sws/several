/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : severaldev

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2019-09-02 15:16:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sev_automatic
-- ----------------------------
DROP TABLE IF EXISTS `sev_automatic`;
CREATE TABLE `sev_automatic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `min_num` int(11) NOT NULL DEFAULT '0' COMMENT '最小值',
  `max_num` int(11) NOT NULL COMMENT '最大值',
  `staff_name` varchar(255) NOT NULL COMMENT '員工名字（不是id）',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='非集團客戶的員工規則';

-- ----------------------------
-- Table structure for sev_customer
-- ----------------------------
DROP TABLE IF EXISTS `sev_customer`;
CREATE TABLE `sev_customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '客戶公司id',
  `staff_id` int(11) DEFAULT NULL COMMENT '負責員工',
  `salesman_id` int(11) DEFAULT NULL COMMENT '銷售員id',
  `state` int(2) DEFAULT '0' COMMENT '狀態',
  `payment` varchar(255) DEFAULT NULL COMMENT '付款期限',
  `group_type` int(2) NOT NULL DEFAULT '0' COMMENT '0:非集团  1：集团',
  `group_id` int(11) DEFAULT NULL COMMENT '集團id',
  `customer_year` int(5) NOT NULL COMMENT '年份',
  `firm_name_id` text,
  `firm_name_us` text COMMENT '有權限公司名字',
  `acca_username` varchar(255) DEFAULT NULL,
  `acca_phone` varchar(255) DEFAULT NULL,
  `acca_remark` text,
  `acca_fun` varchar(255) DEFAULT NULL,
  `acca_lang` varchar(255) DEFAULT NULL,
  `acca_discount` varchar(255) DEFAULT NULL,
  `acca_fax` varchar(255) DEFAULT NULL COMMENT '聯絡人傳真號碼',
  `status_type` varchar(255) DEFAULT 'n',
  `on_off` int(2) NOT NULL DEFAULT '1' COMMENT '是否暫停服務。0：暫停 1：服務',
  `pay_type` int(2) NOT NULL DEFAULT '0' COMMENT '是否現金支付  0：不是   1：是',
  `refer_code` int(6) DEFAULT NULL COMMENT '参考編碼',
  `usual_date` date DEFAULT NULL COMMENT '常規日期',
  `head_worker` varchar(100) DEFAULT NULL COMMENT '交予同事',
  `other_worker` varchar(100) DEFAULT NULL COMMENT '其它跟進同事',
  `advance_name` varchar(255) DEFAULT NULL COMMENT '預付客戶',
  `listing_name` varchar(255) DEFAULT NULL COMMENT '月結單新做法',
  `listing_email` varchar(255) DEFAULT NULL COMMENT '月結單電郵',
  `listing_fax` varchar(255) DEFAULT NULL COMMENT '月結單傳真號碼',
  `new_month` varchar(255) DEFAULT NULL COMMENT '客戶新增月份',
  `lbs_month` int(2) NOT NULL DEFAULT '0' COMMENT '總公司欠款月數',
  `other_month` int(2) NOT NULL DEFAULT '0' COMMENT '細公司欠款月數',
  `lcu` varchar(100) DEFAULT NULL,
  `luu` varchar(100) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9739 DEFAULT CHARSET=utf8 COMMENT='會計追數表';

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
) ENGINE=InnoDB AUTO_INCREMENT=14423 DEFAULT CHARSET=utf8 COMMENT='哪個公司可以管理追數客戶';

-- ----------------------------
-- Table structure for sev_customer_info
-- ----------------------------
DROP TABLE IF EXISTS `sev_customer_info`;
CREATE TABLE `sev_customer_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firm_cus_id` int(11) NOT NULL COMMENT 'customer_firm的id',
  `customer_id` int(11) NOT NULL,
  `amt_gt` int(2) NOT NULL DEFAULT '1' COMMENT '0:小於  1：等於',
  `amt_name` int(4) NOT NULL,
  `amt_num` float(10,2) NOT NULL DEFAULT '0.00',
  `lcu` varchar(50) DEFAULT NULL,
  `luu` varchar(50) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66539 DEFAULT CHARSET=utf8;
