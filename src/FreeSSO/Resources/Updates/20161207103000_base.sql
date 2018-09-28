CREATE TABLE `pawbx_domains` (
  `dom_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dom_key` varchar(32) NOT NULL DEFAULT '',
  `dom_name` varchar(80) NOT NULL DEFAULT '',
  `dom_concurrent_user` tinyint(2) NOT NULL DEFAULT '0',
  `dom_maintain_seconds` int(11) NOT NULL DEFAULT '3600',
  `dom_session_minutes` int(11) NOT NULL DEFAULT '0',
  `dom_sites` text,
  PRIMARY KEY (`dom_id`)
) ENGINE=InnoDB;
CREATE TABLE `pawbx_brokers` (
  `brk_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dom_id` bigint(20) unsigned NOT NULL,
  `brk_key` varchar(32) NOT NULL DEFAULT '',
  `brk_name` varchar(80) NOT NULL DEFAULT '',
  `brk_certificate` varchar(32) NOT NULL DEFAULT '',
  `brk_active` tinyint(1) NOT NULL DEFAULT '0',
  `brk_ts` timestamp NULL DEFAULT NULL,
  `brk_api_scope` text,
  `brk_users_scope` text,
  `brk_ips` text,
  PRIMARY KEY (`brk_id`),
  KEY `fk_broker_dom_id` (`dom_id`),
  CONSTRAINT `fk_broker_dom_id` FOREIGN KEY (`dom_id`) REFERENCES `pawbx_domains` (`dom_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB;
CREATE TABLE `pawbx_users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(255) NOT NULL DEFAULT '',
  `user_password` varchar(255) NOT NULL DEFAULT '',
  `user_active` tinyint(1) NOT NULL DEFAULT '0',
  `user_salt` varchar(80) NOT NULL DEFAULT '',
  `user_email` varchar(255) NOT NULL DEFAULT '',
  `user_first_name` varchar(80) DEFAULT NULL,
  `user_last_name` varchar(80) DEFAULT NULL,
  `user_title` enum('MISTER','MADAM','MISS','OTHER') NOT NULL DEFAULT 'OTHER',
  `user_roles` text,
  `user_type` enum('USER','IP','UUID','ANONYMOUS') NOT NULL DEFAULT 'USER',
  `user_ips` text,
  `user_last_update` timestamp NULL DEFAULT NULL,
  `user_preferred_language` varchar(3) DEFAULT 'FR',
  `user_avatar` blob,
  `user_cache` text,
  `user_val_string` varchar(32) DEFAULT NULL,
  `user_val_end` timestamp NULL DEFAULT NULL,
  `user_val_login` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB;
CREATE TABLE `pawbx_sessions` (
  `sess_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `sess_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sess_end` timestamp NULL DEFAULT NULL,
  `sess_touch` timestamp NULL DEFAULT NULL,
  `sess_content` text,
  `sess_client_addr` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sess_id`),
  KEY `fk_session_user_id` (`user_id`),
  CONSTRAINT `fk_session_user_id` FOREIGN KEY (`user_id`) REFERENCES `pawbx_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB;
CREATE TABLE `pawbx_brokers_sessions` (
  `brs_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brk_key` varchar(32) NOT NULL DEFAULT '',
  `brs_token` varchar(32) NOT NULL DEFAULT '',
  `brs_session_id` varchar(80) DEFAULT '',
  `brs_client_address` varchar(50) DEFAULT NULL,
  `brs_date_created` timestamp NULL DEFAULT NULL,
  `brs_end` timestamp NULL DEFAULT NULL,
  `sess_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`brs_id`),
  KEY `fk_brokers_sess_id` (`sess_id`),
  CONSTRAINT `fk_brokers_sess_id` FOREIGN KEY (`sess_id`) REFERENCES `pawbx_sessions` (`sess_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;
CREATE TABLE `pawbx_passwords_tokens` (
  `ptok_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ptok_token` varchar(80) NOT NULL DEFAULT '',
  `ptok_used` tinyint(1) NOT NULL DEFAULT '0',
  `ptok_email` varchar(255) NOT NULL DEFAULT '',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ptok_request_ip` varchar(50) DEFAULT NULL,
  `ptok_resolve_ip` varchar(50) DEFAULT NULL,
  `ptok_end` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ptok_id`)
) ENGINE=InnoDB;
CREATE TABLE `pawbx_autologin_cookies` (
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `auto_cookie` varchar(32) NOT NULL,
  `auto_ip` varchar(32) DEFAULT NULL,
  `auto_paswd` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`auto_cookie`)
) ENGINE=InnoDB;
replace into `pawbx_domains` (`dom_id`, `dom_key`, `dom_name`, `dom_concurrent_user`, `dom_maintain_seconds`, `dom_session_minutes`, `dom_sites`) values('1','jvsonline.fr','jvsonline.fr','0','3600','0',NULL);
replace into `pawbx_brokers` (`brk_id`, `dom_id`, `brk_key`, `brk_name`, `brk_certificate`, `brk_active`, `brk_ts`, `brk_api_scope`, `brk_users_scope`, `brk_ips`) values('2','1','omega-web-bo','Omega WEB BackOffice','dfc9f994dcdb0404296662e4a68d7c4e','1',NULL,NULL,NULL,NULL);
replace into `pawbx_users` (`user_id`, `user_login`, `user_password`, `user_active`, `user_salt`, `user_email`, `user_first_name`, `user_last_name`, `user_title`, `user_roles`, `user_type`, `user_ips`, `user_last_update`, `user_preferred_language`, `user_avatar`, `user_cache`, `user_val_string`, `user_val_end`, `user_val_login`) values('1','jerome.klam@jvs.fr','6efff2f95cca7a0509d5685aac291403','1','f2befebe6e3ebede8d255cd99eaf8d16','jerome.klam@jvs.fr','KLAM','Jérôme','MISTER',NULL,'USER',NULL,NULL,'FR',NULL,NULL,NULL,NULL,NULL);



