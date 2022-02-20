ALTER TABLE `sys_inbox` ADD COLUMN `inbox_keep` tinyint(1) DEFAULT 0;
ALTER TABLE `sys_inbox` ADD COLUMN `grp_id` bigint(20) unsigned DEFAULT NULL;