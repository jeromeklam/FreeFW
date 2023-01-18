CREATE TABLE `sys_cache` (
  `tab_name` varchar(80) NOT NULL,
  `tab_last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tab_name`)
);