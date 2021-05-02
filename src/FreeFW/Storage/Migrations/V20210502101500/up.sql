CREATE TABLE `sys_automate` (
  `auto_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `auto_name` varchar(255) NOT NULL,
  `auto_desc` text DEFAULT NULL,
  `auto_object_name` varchar(80) NOT NULL,
  `grp_id` bigint(20) unsigned DEFAULT NULL,
  `auto_service` varchar(80) DEFAULT NULL,
  `auto_method` varchar(80) DEFAULT NULL,
  `auto_params` text DEFAULT NULL,
  PRIMARY KEY (`auto_id`)
);