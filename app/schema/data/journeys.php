<?php

return "CREATE TABLE {$table_name} (
         journey_step_id bigint(20) unsigned NOT NULL auto_increment,
		 path_id bigint(20),
		 user_id bigint(20),
		 unit_id bigint(20),
         status varchar(255) NULL,
		 visible boolean FALSE,
		 allowed boolean FALSE,
         PRIMARY KEY (id),
         KEY journey ( path_id, user_id, unit_id, status, visible, allowed )
         )
         {$charset_collate}";
