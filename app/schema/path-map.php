<?php

return "CREATE TABLE {$table_name} (
         id bigint(20) unsigned NOT NULL auto_increment,
		 path_id bigint(20),
         module_id bigint(20),
		 module_order int(5),
		 parent_module_id bigint(20),
         PRIMARY KEY (id),
         KEY pathway ( path_id, module_id, module_order, parent_module_id )
         )
         {$charset_collate}";