CREATE TABLE `pawbx_links_users` (
  `lku_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `lku_partner_type` varchar(20) NOT NULL,
  `lku_partner_id` bigint(20) NOT NULL,
  `lku_auth_method` varchar(20) NOT NULL DEFAULT 'AUTO',
  `lku_auth_datas` text,
  `lku_end` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lku_id`),
  UNIQUE KEY `idx_links_users_uniq` (`user_id`,`lku_partner_type`,`lku_partner_id`)
) ENGINE=InnoDB;