CREATE OR REPLACE TRIGGER trg_lang_insert
BEFORE INSERT ON sys_lang
FOR EACH ROW 
BEGIN 
   REPLACE INTO sys_cache VALUES ( 'sys_lang', NOW( )) ;
END