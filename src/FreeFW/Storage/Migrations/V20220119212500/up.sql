CREATE TABLE `sys_taxonomy` (
  `tx_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tx_code` varchar(80) DEFAULT NULL,
  `tx_desc` text DEFAULT NULL,
  `tx_object_name` varchar(80) DEFAULT NULL,
  `tx_object_id` bigint(20) unsigned DEFAULT NULL,
  `tx_parent_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`tx_id`)
);
CREATE TABLE `sys_taxonomy_lang` (
  `txl_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tx_id` bigint(20) unsigned DEFAULT NULL,
  `lang_id` bigint(20) unsigned DEFAULT NULL,
  `txl_content` longtext DEFAULT NULL,
  PRIMARY KEY (`txl_id`),
  KEY `fk_taxonomy_lang_taxonomy` (`tx_id`),
  CONSTRAINT `fk_taxonomy_lang_taxonomy` FOREIGN KEY (`tx_id`) REFERENCES `sys_taxonomy` (`tx_id`)
);