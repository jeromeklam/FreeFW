ALTER TABLE pawbx_users ADD COLUMN user_extern_code varchar(255) DEFAULT NULL;
UPDATE pawbx_users SET user_extern_code = user_login WHERE 1=1;

ALTER TABLE pawbx_groups ADD COLUMN grp_extern_code varchar(255) DEFAULT NULL;
UPDATE pawbx_groups SET grp_extern_code = grp_name WHERE 1=1;
