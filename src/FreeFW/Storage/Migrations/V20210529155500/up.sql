ALTER TABLE `sys_email` ADD COLUMN `email_edi1_id` bigint(20) unsigned DEFAULT NULL;
ALTER TABLE `sys_email` ADD COLUMN `email_edi1_object` varchar(80) DEFAULT NULL;
ALTER TABLE `sys_email` ADD COLUMN `email_edi2_id` bigint(20) unsigned DEFAULT NULL;
ALTER TABLE `sys_email` ADD COLUMN `email_edi2_object` varchar(80) DEFAULT NULL;