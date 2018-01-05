<?php

return "CREATE TABLE {$table_name} (
         id bigint(20) unsigned NOT NULL auto_increment,
		 path_id bigint(20),
         user_id bigint(20),
		 role varchar(255) NULL,
		 PRIMARY KEY (id),
         )
         {$charset_collate}";