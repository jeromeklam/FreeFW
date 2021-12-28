ALTER TABLE `sys_email` ADD COLUMN `grp_id` bigint(20) unsigned DEFAULT NULL;
UPDATE `sys_email` SET grp_id = 15;