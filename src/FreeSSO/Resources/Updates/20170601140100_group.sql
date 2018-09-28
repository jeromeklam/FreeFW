CREATE TABLE `pawbx_groups` (
  `grp_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grp_name` varchar(80) DEFAULT NULL,
  `grp_active` tinyint(1) DEFAULT '1',
  `grp_ips` text,
  `grp_key` varchar(256) DEFAULT NULL,
  `grp_verif_key` varchar(256) DEFAULT NULL,
  `grp_cnx` text,
  PRIMARY KEY (`grp_id`)
) ENGINE=InnoDB;
CREATE TABLE `pawbx_groups_users` (
  `gru_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `grp_id` bigint(20) DEFAULT NULL,
  `gru_key` varchar(256) DEFAULT NULL,
  `gru_infos` text,
  `gru_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`gru_id`),
  UNIQUE KEY `idx_groups_users_uniq` (`user_id`,`grp_id`)
) ENGINE=InnoDB;