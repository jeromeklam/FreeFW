CREATE TABLE `sys_help` (
  `help_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `help_name` varchar(255) NOT NULL DEFAULT '',
  `help_desc` longtext DEFAULT NULL,
  `help_position` int(11) NOT NULL,
  `help_type` enum('HTML','PDF','MP4','URL') NOT NULL DEFAULT 'HTML',
  `help_content` longtext DEFAULT NULL,
  `help_scope` text DEFAULT NULL,
  PRIMARY KEY (`help_id`)
);