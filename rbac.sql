/*
Navicat MySQL Data Transfer

Source Server         : 192.168.0.57
Source Server Version : 50726
Source Host           : 192.168.0.57:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-11-27 16:59:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for test_admin
-- ----------------------------
DROP TABLE IF EXISTS `test_admin`;
CREATE TABLE `test_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员名字',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态  1正常  2禁用',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `is_del` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1 正常  2删除',
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE,
  KEY `is_del` (`is_del`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for test_permission
-- ----------------------------
DROP TABLE IF EXISTS `test_permission`;
CREATE TABLE `test_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `identity` varchar(60) NOT NULL DEFAULT '' COMMENT '接口标识',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '导航名称',
  `sort_order` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `url` varchar(120) NOT NULL DEFAULT '' COMMENT 'url地址',
  `is_web` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1web页面  2不是',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `url` (`url`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Table structure for test_role
-- ----------------------------
DROP TABLE IF EXISTS `test_role`;
CREATE TABLE `test_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `is_del` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 正常  2删除',
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Table structure for test_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `test_role_admin`;
CREATE TABLE `test_role_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`,`role_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Table structure for test_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `test_role_permission`;
CREATE TABLE `test_role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_id` (`permission_id`,`role_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='权限角色表';
