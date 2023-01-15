ALTER TABLE `sys_edition` MODIFY COLUMN `edi_type` enum('IMPRESS','CALC','WRITER','HTML', 'PDF') NOT NULL;
ALTER TABLE `sys_edition` ADD COLUMN `edi_mapping` longtext DEFAULT NULL;