-- 
-- C'reated' 'by' SQL::Translator::Producer::SQLite
-- C'reated' 'on' F'ri' A'ug' 10 23:07:59 2012
-- 

BEGIN TRANSACTION;

--
-- Table: image_data
--
CREATE TABLE `image_data` (
  'id' INTEGER PRIMARY KEY NOT NULL,
  'image_id' int(10) NOT NULL,
  'size' int(10) NOT NULL,
  'width' int(10) NOT NULL,
  'height' int(10) NOT NULL,
  'type' varchar(64) NOT NULL,
  'mime' varchar(255) NOT NULL,
  'filename' varchar(1024) NOT NULL,
  'data' 'longblob' NOT NULL,
  'created' 'timestamp' NOT NULL
);

CREATE INDEX 'item_index' ON 'image_data' (image_id);

--
-- Table: image_label
--
CREATE TABLE `image_label` (
  'image_id' int(11) NOT NULL,
  'label_id' int(11) NOT NULL,
  'priority' int(10) NOT NULL
);

CREATE INDEX 'image_index' ON 'image_label' (image_id);

CREATE INDEX 'label_index' ON 'image_label' (label_id);

CREATE UNIQUE INDEX 'image_label_index' ON 'image_label' (image_id, label_id);

--
-- Table: item
--
CREATE TABLE `item` (
  'id' INTEGER PRIMARY KEY NOT NULL,
  'slug' varchar(64) NOT NULL,
  'type' varchar(255) NOT NULL,
  'priority' int(10) NOT NULL DEFAULT 0,
  'parent' int(10) NOT NULL DEFAULT 0,
  'visible' tinyint(2) NOT NULL DEFAULT 0,
  'name' varchar(255) NOT NULL DEFAULT '',
  'description' varchar(2048) NOT NULL DEFAULT '',
  'appendix' 'longblob' NOT NULL,
  'updated' 'timestamp' NOT NULL DEFAULT '0000-00-00 00:00:00',
  'inserted' 'timestamp' NOT NULL DEFAULT '0000-00-00 00:00:00'
);

--
-- Table: item_content
--
CREATE TABLE `item_content` (
  'id' INTEGER PRIMARY KEY NOT NULL,
  'item_id' int(10) NOT NULL,
  'value' text,
  'draft' text,
  'html' text,
  'updated' 'timestamp' NOT NULL DEFAULT '0000-00-00 00:00:00'
);

CREATE INDEX 'itemk_index' ON 'item_content' (item_id);

--
-- Table: link
--
CREATE TABLE `link` (
  'id' INTEGER PRIMARY KEY NOT NULL,
  'group' varchar(255) NOT NULL,
  'parent_id' int(10) NOT NULL DEFAULT 0000000000,
  'priority' int(10) DEFAULT 0,
  'title' varchar(255) DEFAULT NULL,
  'url' varchar(4096) DEFAULT NULL,
  'description' varchar(4096) DEFAULT NULL
);

--
-- Table: link_group
--
CREATE TABLE `link_group` (
  'id' INTEGER PRIMARY KEY NOT NULL,
  'name' varchar(255) NOT NULL,
  'description' varchar(1024) NOT NULL
);

--
-- Table: setting
--
CREATE TABLE `setting` (
  'id' INTEGER PRIMARY KEY NOT NULL,
  'group' varchar(255) NOT NULL,
  'name' varchar(255) NOT NULL,
  'title' varchar(255) NOT NULL,
  'description' varchar(1024) NOT NULL,
  'type' varchar(255) NOT NULL,
  'value' varchar(255) NOT NULL,
  'updated' 'timestamp' NOT NULL DEFAULT '0000-00-00 00:00:00'
);

COMMIT;
