DROP TABLE `sys_alert`;
CREATE TABLE `sys_alert` (
  `alert_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant de l''alerte',
  `brk_id` bigint(20) unsigned NOT NULL COMMENT 'Identifiant du broker',
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'Identifiant de l''utilisateur à l''origine de l''alerte',
  `alert_object_name` varchar(32) NOT NULL COMMENT 'Object lié : FreeAsso_Cause, FreeAsso_Site, FreeAsso_Contract, FreeAsso_Client',
  `alert_object_id` bigint(20) unsigned NOT NULL COMMENT 'Identifiant de l''objet lié',
  `alert_title` varchar(255) DEFAULT NULL COMMENT 'Titre de l''alerte',
  `alert_from` timestamp NULL DEFAULT NULL COMMENT 'Date de création',
  `alert_to` timestamp NULL DEFAULT NULL COMMENT 'Date de fin',
  `alert_ts` timestamp NULL DEFAULT NULL COMMENT 'Date de dernière mise à jour',
  `alert_deadline` timestamp NULL DEFAULT NULL COMMENT 'Echéance',
  `alert_done_ts` timestamp NULL DEFAULT NULL COMMENT 'Date de réalisation',
  `alert_done_action` varchar(80) DEFAULT NULL COMMENT 'Action de réalisation pour les automatismes',
  `alert_done_user_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Identifiant de l''utilisateur ayant clôturé l''alerte',
  `alert_done_text` longtext DEFAULT NULL COMMENT 'Texte de réalisation',
  `alert_code` varchar(80) DEFAULT NULL COMMENT 'Variable en fonction de l''objet lié',
  `alert_text` longtext DEFAULT NULL COMMENT 'Description détaillée de l''alerte',
  `alert_activ` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Alerte active ou temporairement désactivée',
  `alert_priority` enum('IMPORTANT','CRITICAL','INFORMATION','TODO','NONE') NOT NULL DEFAULT 'NONE' COMMENT 'Priorité',
  `alert_recur_type` enum('HOUR','MINUTE','DAY','MONTH','YEAR','MANUAL') DEFAULT NULL COMMENT 'Type de récurrence',
  `alert_recur_number` int(11) DEFAULT NULL COMMENT 'Délai pour la récurrence',
  `alert_email_1` enum('NONE','15M','30M','1H','2H','1D','2D') DEFAULT NULL COMMENT 'Rappel par email 1',
  `alert_email_2` enum('NONE','15M','30M','1H','2H','1D','2D') DEFAULT NULL COMMENT 'Rappel par email 2',
  `alert_string_1` varchar(32) DEFAULT NULL COMMENT 'Variable chaine 1',
  `alert_string_2` varchar(32) DEFAULT NULL COMMENT 'Variable chaine 2',
  `alert_number_1` int(11) DEFAULT NULL COMMENT 'Variable entier 1',
  `alert_numer_2` int(11) DEFAULT NULL COMMENT 'Variable entier 2',
  `alert_bool_1` int(11) DEFAULT NULL COMMENT 'Variable booléen 1',
  `alert_bool_2` int(11) DEFAULT NULL COMMENT 'Variable booléen 2',
  `alert_text_1` longtext DEFAULT NULL COMMENT 'Variable Text 1',
  `alert_text_2` longtext DEFAULT NULL COMMENT 'Variable Text 2',
  PRIMARY KEY (`alert_id`),
  KEY `fk_alert_broker` (`brk_id`),
  KEY `fk_alert_user` (`user_id`),
  KEY `fk_alert_object` (`alert_object_name`,`alert_object_id`)
);