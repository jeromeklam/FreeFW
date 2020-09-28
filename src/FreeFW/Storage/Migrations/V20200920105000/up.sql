CREATE TABLE `sys_alert_category` (
  `alerc_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant',
  `brk_id` bigint(20) unsigned NOT NULL COMMENT 'Broker',
  `alerc_name` varchar(80) NOT NULL COMMENT 'Nom',
  `alerc_bg_color` varchar(20) DEFAULT NULL COMMENT 'Couleur de fond',
  `alerc_fg_color` varchar(20) DEFAULT NULL COMMENT 'Couleur de texte',
  PRIMARY KEY (`alerc_id`),
  KEY `fk_alert_category_broker` (`brk_id`)
);
ALTER TABLE `sys_alert` ADD COLUMN `alert_parent_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Identifiant de l''objet parent';
ALTER TABLE `sys_alert` ADD COLUMN `alerc_id` BIGINT(20) UNSIGNED NULL COMMENT 'Catégorie d\'alerte';
ALTER TABLE `sys_alert` ADD CONSTRAINT `fk_alert_alert_category` FOREIGN KEY (`alerc_id`) REFERENCES `sys_alert_category`(`alerc_id`);