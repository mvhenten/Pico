/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50141
Source Host           : localhost:3306
Source Database       : wedding

Target Server Type    : MYSQL
Target Server Version : 50141
File Encoding         : 65001

Date: 2011-03-30 00:34:27
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `image_data`
-- ----------------------------
DROP TABLE IF EXISTS `image_data`;
CREATE TABLE `image_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned NOT NULL,
  `size` int(10) unsigned zerofill NOT NULL,
  `width` int(10) unsigned zerofill NOT NULL,
  `height` int(10) unsigned zerofill NOT NULL,
  `type` int(10) unsigned zerofill NOT NULL,
  `mime` varchar(255) NOT NULL,
  `filename` varchar(1024) NOT NULL,
  `data` longblob NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of image_data
-- ----------------------------

-- ----------------------------
-- Table structure for `image_label`
-- ----------------------------
DROP TABLE IF EXISTS `image_label`;
CREATE TABLE `image_label` (
  `image_id` int(11) unsigned NOT NULL,
  `label_id` int(11) unsigned NOT NULL,
  `priority` int(10) unsigned zerofill NOT NULL,
  UNIQUE KEY `image_label` (`image_id`,`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of image_label
-- ----------------------------

-- ----------------------------
-- Table structure for `item`
-- ----------------------------
DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `parent` int(10) unsigned zerofill DEFAULT NULL,
  `visible` tinyint(4) unsigned zerofill DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `appendix` longblob,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `inserted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of item
-- ----------------------------

-- ----------------------------
-- Table structure for `item_content`
-- ----------------------------
DROP TABLE IF EXISTS `item_content`;
CREATE TABLE `item_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `value` text,
  `draft` text,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of item_content
-- ----------------------------

-- ----------------------------
-- Table structure for `link`
-- ----------------------------
DROP TABLE IF EXISTS `link`;
CREATE TABLE `link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `parent_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `priority` int(10) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(4096) DEFAULT NULL,
  `description` varchar(4096) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of link
-- ----------------------------

-- ----------------------------
-- Table structure for `link_group`
-- ----------------------------
DROP TABLE IF EXISTS `link_group`;
CREATE TABLE `link_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of link_group
-- ----------------------------

-- ----------------------------
-- Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of setting
-- ----------------------------
