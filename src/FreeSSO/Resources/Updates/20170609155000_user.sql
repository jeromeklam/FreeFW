ALTER TABLE `pawbx_users` MODIFY COLUMN `user_email` varchar(255) DEFAULT NULL;
ALTER TABLE `pawbx_users` MODIFY COLUMN `user_type` enum('USER','IP','UUID','ANONYMOUS','REST') NOT NULL DEFAULT 'USER';