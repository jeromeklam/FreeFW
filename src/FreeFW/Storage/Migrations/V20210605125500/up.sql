CREATE TABLE `sys_inbox` (
  `inbox_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inbox_filename` varchar(255) NOT NULL,
  `inbox_ts` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `inbox_content` longblob DEFAULT NULL,
  `inbox_name` varchar(255) DEFAULT NULL,
  `inbox_desc` longtext DEFAULT NULL,
  `inbox_object_name` varchar(80) DEFAULT NULL,
  `inbox_params` longtext DEFAULT NULL,
  `inbox_download_ts` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`inbox_id`)
);