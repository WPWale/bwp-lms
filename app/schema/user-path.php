<?php

return "CREATE TABLE {$table_name} (
         id bigint(20) unsigned NOT NULL auto_increment,
		 path_map_id bigint(20),
		 user_id bigint(20),
		 unit_id bigint(20),
         unit_status varchar(255) NULL,
         PRIMARY KEY (id),
         KEY pathway ( path_map_id, user_id, unit_id, unit_status )
         )
         {$charset_collate}";
