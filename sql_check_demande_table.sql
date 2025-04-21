-- Check structure of the "demande" table
DESCRIBE demande;

-- Check privileges of current user on "demande" table
SHOW GRANTS FOR CURRENT_USER();

-- Alternatively, check privileges on the "demande" table specifically
SHOW GRANTS FOR CURRENT_USER() LIKE 'demande';
