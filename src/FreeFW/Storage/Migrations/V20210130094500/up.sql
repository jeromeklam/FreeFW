CREATE TABLE `sys_edition_lang` (
  `edil_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edi_id` bigint(20) unsigned NOT NULL,
  `lang_id` bigint(20) unsigned NOT NULL,
  `edil_data` longblob DEFAULT NULL,
  PRIMARY KEY (`edil_id`),
  KEY `fk_edition_lang_edition` (`edi_id`),
  CONSTRAINT `fk_edition_lang_edition` FOREIGN KEY (`edi_id`) REFERENCES `sys_edition` (`edi_id`),
  KEY `fk_edition_lang_lang` (`lang_id`),
  CONSTRAINT `fk_edition_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `sys_lang` (`lang_id`)
);
CREATE TABLE `sys_email_lang` (
  `emaill_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email_id` bigint(20) unsigned NOT NULL,
  `lang_id` bigint(20) unsigned NOT NULL,
  `emaill_subject` varchar(255) DEFAULT NULL,
  `emaill_body` longtext DEFAULT NULL,
  PRIMARY KEY (`emaill_id`),
  KEY `fk_email_lang_email` (`email_id`),
  CONSTRAINT `fk_email_lang_email` FOREIGN KEY (`email_id`) REFERENCES `sys_email` (`email_id`),
  KEY `fk_email_lang_lang` (`lang_id`),
  CONSTRAINT `fk_email_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `sys_lang` (`lang_id`)
);