CREATE INDEX `pawbx_brokers_idx1` ON `pawbx_brokers` (`brk_key`, `brk_active`);
CREATE INDEX `pawbx_brokers_sessions_idx1` ON `pawbx_brokers_sessions` (`brk_key`, `brs_token`);
CREATE INDEX `pawbx_brokers_sessions_idx2` ON `pawbx_brokers_sessions` (`brs_end`);
CREATE INDEX `pawbx_users_idx1` ON `pawbx_users` (`user_login`, `user_active`);
CREATE INDEX `pawbx_users_idx2` ON `pawbx_users` (`user_val_string`);
CREATE INDEX `pawbx_mailings_idx1` ON `pawbx_mailings` (`mailg_code`);
CREATE INDEX `pawbx_sessions_idx1` ON `pawbx_sessions` (`sess_end`);
CREATE INDEX `pawbx_passwords_tokens_idx1` ON `pawbx_passwords_tokens` (`ptok_token`, `ptok_used`, `ptok_end`);