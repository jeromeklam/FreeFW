CREATE TABLE `sys_version` (
  `vers_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vers_version` varchar(80) NOT NULL,
  `vers_install_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `vers_install_text` longtext DEFAULT NULL,
  `vers_install_status` enum('WAITING','PENDING','OK','ERROR') DEFAULT 'PENDING',
  `vers_install_content` longtext DEFAULT NULL,
  `vers_install_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vers_id`),
  UNIQUE KEY `version` (`vers_version`)
);