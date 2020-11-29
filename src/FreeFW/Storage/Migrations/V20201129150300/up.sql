CREATE TABLE `sys_edition` (
  `edi_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brk_id` bigint(20) unsigned NOT NULL,
  `edi_object_name` varchar(32) NOT NULL,
  `edi_object_id` bigint(20) unsigned DEFAULT NULL,
  `edi_ts` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `edi_name` varchar(255) NOT NULL,
  `edi_desc` longtext DEFAULT NULL,
  `edi_data` longblob DEFAULT NULL,
  `edi_type` enum('IMPRESS','CALC','WRITER','HTML') NOT NULL,
  PRIMARY KEY (`edi_id`),
  KEY `fk_edition_broker` (`brk_id`),
  KEY `fk_edition_object` (`edi_object_name`,`edi_object_id`)
);
