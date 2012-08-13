-- MySQL dump 10.13  Distrib 5.1.62, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mbr
-- ------------------------------------------------------
-- Server version	5.1.62-0ubuntu0.11.04.1
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `image_data`
--

DROP TABLE IF EXISTS `image_data`;
CREATE TABLE `image_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned NOT NULL,
  `size` int(10) unsigned zerofill NOT NULL,
  `width` int(10) unsigned zerofill NOT NULL,
  `height` int(10) unsigned zerofill NOT NULL,
  `type` varchar(64) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `filename` varchar(1024) NOT NULL,
  `data` longblob NOT NULL,
  `created` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item` (`image_id`)
) TYPE=MyISAM AUTO_INCREMENT=272;

--
-- Table structure for table `image_label`
--

DROP TABLE IF EXISTS `image_label`;
CREATE TABLE `image_label` (
  `image_id` int(11) unsigned NOT NULL,
  `label_id` int(11) unsigned NOT NULL,
  `priority` int(10) unsigned zerofill NOT NULL,
  UNIQUE KEY `image_label` (`image_id`,`label_id`),
  KEY `image` (`image_id`),
  KEY `label` (`label_id`)
) TYPE=InnoDB;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(64) NOT NULL,
  `type` varchar(255) NOT NULL,
  `priority` int(10) unsigned NOT NULL DEFAULT '0',
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `visible` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(2048) NOT NULL DEFAULT '',
  `appendix` longblob NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `inserted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=69;

--
-- Table structure for table `item_content`
--

DROP TABLE IF EXISTS `item_content`;
CREATE TABLE `item_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `value` text,
  `draft` text,
  `html` text,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `itemk` (`item_id`)
) TYPE=InnoDB AUTO_INCREMENT=3;

--
-- Table structure for table `link`
--

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
) TYPE=MyISAM;

--
-- Table structure for table `link_group`
--

DROP TABLE IF EXISTS `link_group`;
CREATE TABLE `link_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-13 21:25:30
