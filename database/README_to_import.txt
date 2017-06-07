# to import db
pg_dump -U postgres <you new database> < collective_intelligence_db_dump.sql

# to import functions
pg_restore -U postgres -d <your new database> -L function_list collective_intelligence_functions_dump
