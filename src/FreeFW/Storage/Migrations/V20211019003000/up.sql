ALTER TABLE `sys_message` ADD COLUMN `dest_id` bigint(20) unsigned DEFAULT NULL;
ALTER TABLE `sys_message` ADD COLUMN `grp_id` bigint(20) unsigned DEFAULT NULL;
ALTER TABLE `sys_message` ADD KEY `ix_message_dest` (`dest_id`);
