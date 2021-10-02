ALTER TABLE `sys_history` ADD KEY `ix_history_ts` (`hist_ts`);
ALTER TABLE `sys_history` ADD KEY `ix_history_object` (`hist_object_name`,`hist_object_id`);