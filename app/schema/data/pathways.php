<?php

return "CREATE TABLE {$table_name} (
         path_step_id bigint(20) unsigned NOT NULL auto_increment,
		 path_id bigint(20),
         unit_id bigint(20),
		 unit_type varchar(255),
		 unit_title varchar(255),
		 unit_order int(5),
		 status varchar(255) NULL,
		 visible boolean FALSE,
		 allowed boolean FALSE,
		 role int(3),
		 parent_unit_id bigint(20),
         PRIMARY KEY (id),
         KEY pathway ( path_id, unit_id, unit_type, unit_order, parent_unit_id )
         )
         {$charset_collate}";