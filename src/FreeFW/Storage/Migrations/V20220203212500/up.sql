CREATE TABLE `sys_template` (
  `tpl_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_name` varchar(80) DEFAULT NULL,
  `tpl_desc` text DEFAULT NULL,
  `tpl_content` longtext DEFAULT NULL,
  PRIMARY KEY (`tpl_id`)
);
ALTER TABLE `sys_email` ADD COLUMN `tpl_id` bigint(20) unsigned DEFAULT NULL;
ALTER TABLE `sys_email` ADD CONSTRAINT `fk_email_template` FOREIGN KEY (`tpl_id`) REFERENCES `sys_template` (`tpl_id`);