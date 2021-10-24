CREATE TABLE `sys_translation` (
  `tr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_key` varchar(128) NOT NULL DEFAULT '',
  `tr_desc` longtext DEFAULT NULL,
  `tr_html` tinyint(1) NOT NULL DEFAULT 0,
  `tr_type` enum('NODE','SHEET') NOT NULL DEFAULT 'SHEET',
  `tr_lang_fr` longtext DEFAULT NULL,
  `tr_lang_en` longtext DEFAULT NULL,
  `tr_lang_ch` longtext DEFAULT NULL,
  `tr_lang_de` longtext DEFAULT NULL,
  `tr_lang_es` longtext DEFAULT NULL,
  `tr_lang_id` longtext DEFAULT NULL,
  PRIMARY KEY (`tr_id`),
  UNIQUE KEY `ix_translation_key` (`tr_key`)
);