/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : vege

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-07-24 08:58:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `title` varchar(255) DEFAULT NULL COMMENT '管理员title',
  `last_time` datetime DEFAULT NULL COMMENT '最后一次登录时间',
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('2', '张三', '202cb962ac59075b964b07152d234b70', '18611632458', 'aaaa', '2018-07-10 17:30:59', null);
INSERT INTO `admin` VALUES ('3', '只是打发斯蒂芬', 'e10adc3949ba59abbe56e057f20f883e', '123123123123', '阿斯顿发斯蒂芬', '2018-07-11 08:48:27', null);
INSERT INTO `admin` VALUES ('5', '张三222', '21232f297a57a5a743894a0e4a801fc3', '18611632549', null, '2018-07-10 17:31:20', null);
INSERT INTO `admin` VALUES ('10', 'admin', '81dc9bdb52d04dc20036dbd8313ed055', '4134234', null, '2018-07-11 15:21:33', null);
INSERT INTO `admin` VALUES ('9', 'admin88', '6512bd43d9caa6e02c990b0a82652dca', '123456789', null, '2018-07-10 18:39:48', null);
INSERT INTO `admin` VALUES ('11', '李四', '21232f297a57a5a743894a0e4a801fc3', '18611632547', null, '2018-07-13 20:34:21', '');

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` varchar(255) DEFAULT NULL COMMENT '商家名称',
  `v_id` varchar(255) DEFAULT NULL COMMENT '蔬菜名称',
  `vege_price` varchar(45) DEFAULT NULL COMMENT '蔬菜当日价格',
  `vege_num` int(11) DEFAULT NULL COMMENT '蔬菜斤两',
  `sum_price` varchar(10) DEFAULT NULL,
  `is_pay` tinyint(4) DEFAULT '0' COMMENT '是否支付',
  `order_time` varchar(225) DEFAULT NULL COMMENT '订单时间',
  `order_year` int(11) DEFAULT NULL COMMENT '订单年',
  `order_month` int(11) DEFAULT NULL COMMENT '订单月',
  `order_date` int(11) DEFAULT NULL COMMENT '订单日',
  `order_insert_time` datetime DEFAULT NULL,
  `user_adminid` int(11) DEFAULT NULL COMMENT '录入订单的管理员id',
  `order_number` varchar(255) DEFAULT NULL COMMENT '唯一订单号',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('1', '1', '1', '3', '10', '30', '0', '2018-07-06 10:23:10', '2018', '7', '13', '2018-07-06 14:09:01', '2', null);
INSERT INTO `order` VALUES ('2', '1', '1', '3', '10', '30', '0', '2018-07-06 10:23:10', '2018', '7', '13', '2018-07-06 14:09:01', '2', null);
INSERT INTO `order` VALUES ('8', '1', '4', '', '0', '', '0', '2013-04-23', '2013', '4', '23', '2018-07-11 15:26:59', '10', '2018071151975449');
INSERT INTO `order` VALUES ('9', '1', '4', '321', '3213', '231', '0', '2013-04-23', '2013', '4', '23', '2018-07-11 15:27:42', '10', '2018071110156545');
INSERT INTO `order` VALUES ('5', '1', '1', '11', '11', '11', '0', '2018-07-11', '2018', '7', '11', '2018-07-11 10:49:23', '3', '2018071151579756');
INSERT INTO `order` VALUES ('6', '1', '3', '11', '11', '11', '0', '2018-07-01', '2018', '7', '1', '2018-07-11 10:52:20', '3', '2018071152101575');
INSERT INTO `order` VALUES ('7', '1', '1', '11', '1', '11', '0', '2018-07-12', '2018', '7', '12', '2018-07-11 14:08:17', '3', '2018071149535652');
INSERT INTO `order` VALUES ('10', '9', '8', '5.00', '100', '500', '0', '2018-07-13', '2018', '7', '13', '2018-07-13 17:56:33', '2', '2018071349515052');
INSERT INTO `order` VALUES ('11', '9', '6', '1.00', '500', '500', '0', '2018-07-13', '2018', '7', '13', '2018-07-13 17:56:46', '2', '2018071310199101');
INSERT INTO `order` VALUES ('12', '1', '7', '2.00', '100', '200', '0', '2018-07-13', '2018', '7', '13', '2018-07-13 17:57:08', '11', '2018071352100501');
INSERT INTO `order` VALUES ('13', '1', '1', '3.00', '131', '393', '0', '2018-07-13', '2018', '7', '13', '2018-07-13 20:35:02', '11', '2018071354521019');
INSERT INTO `order` VALUES ('14', '5', '7', '2.00', '6', '12', '0', '2018-07-13', '2018', '7', '13', '2018-07-13 20:47:27', '11', '2018071310249535');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_name` varchar(255) DEFAULT '' COMMENT '商家名称',
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家id',
  `is_use` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `user_phone` varchar(45) DEFAULT NULL COMMENT '联系电话',
  `user_contacts` varchar(255) DEFAULT NULL COMMENT '联系人',
  `user_address` varchar(255) DEFAULT NULL COMMENT '客户地址',
  `user_time` datetime DEFAULT NULL COMMENT '添加时间',
  `user_adminid` int(11) DEFAULT NULL COMMENT '添加客户的管理员id',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('张老板杂货店', '1', '0', '18611632458', '张老二', '西安高新软件新城', '2018-07-06 11:13:17', '2');
INSERT INTO `user` VALUES ('凄凄切切', '3', '1', '123456789', '呜呜呜呜', '呜呜呜呜', '2018-07-11 11:08:46', '3');
INSERT INTO `user` VALUES ('凄凄切切', '4', '1', '123456789', '呜呜呜呜', '呜呜呜呜', '2018-07-11 11:10:52', '3');
INSERT INTO `user` VALUES ('凄凄切切11', '5', '1', '111111111', '11', '1111', '2018-07-11 14:09:20', '3');
INSERT INTO `user` VALUES ('', '6', '0', '', '', '', '2018-07-11 15:27:56', '10');
INSERT INTO `user` VALUES ('啊啊啊啊啊', '8', '0', '12312312312', '阿萨德', '阿斯顿发斯蒂芬', '2018-07-13 17:54:26', '11');
INSERT INTO `user` VALUES ('阿斯顿发生发的', '9', '0', '234234242', '爱上对方答复', '阿斯顿发生', '2018-07-13 17:54:37', '11');
INSERT INTO `user` VALUES ('阿斯顿发送到', '10', '1', '12313123123', '阿斯顿发', '按时', '2018-07-13 20:36:01', '11');

-- ----------------------------
-- Table structure for vegetable
-- ----------------------------
DROP TABLE IF EXISTS `vegetable`;
CREATE TABLE `vegetable` (
  `vege_name` varchar(200) NOT NULL COMMENT '蔬菜名',
  `vege_price` decimal(10,2) DEFAULT NULL COMMENT '蔬菜价格',
  `vege_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '蔬菜id',
  `is_on` tinyint(4) DEFAULT '1' COMMENT '是否上架',
  `vege_spec` varchar(255) DEFAULT NULL COMMENT '蔬菜规格',
  `vege_unit` tinyint(4) DEFAULT '0' COMMENT '蔬菜单位 0:斤  1：盒  2：瓶 3：箱  4：个  5：件',
  `vege_time` datetime DEFAULT NULL COMMENT '添加蔬菜的时间',
  `user_adminid` int(11) DEFAULT NULL COMMENT '添加蔬菜的管理员id',
  PRIMARY KEY (`vege_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of vegetable
-- ----------------------------
INSERT INTO `vegetable` VALUES ('西红柿', '3.00', '1', '1', '1', '0', '2018-07-06 10:09:13', '2');
INSERT INTO `vegetable` VALUES ('凄凄切切', '11.00', '4', '1', '11', '0', '2018-07-11 11:11:03', '3');
INSERT INTO `vegetable` VALUES ('西红柿--1', '1.00', '6', '1', '12', '0', '2018-07-13 17:55:31', '11');
INSERT INTO `vegetable` VALUES ('西红柿--2', '2.00', '7', '1', '122', '0', '2018-07-13 17:55:51', '11');
INSERT INTO `vegetable` VALUES ('西红柿--3', '5.00', '8', '1', '123', '1', '2018-07-13 17:56:06', '11');
