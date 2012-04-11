CREATE TABLE `builder_block` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(64) NOT NULL,
  `data` longblob NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `inserted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent_id`),
  KEY `typek` (`type`)
) TYPE=InnoDB AUTO_INCREMENT=100;
