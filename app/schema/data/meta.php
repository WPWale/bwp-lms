<?php
return "CREATE TABLE $table_name (
		meta_id bigint(20) unsigned NOT NULL auto_increment,
		{$type}_id bigint(20) unsigned NOT NULL default '0',
		meta_key varchar(255) default NULL,
		meta_value longtext,
		PRIMARY KEY  (meta_id),
		KEY {$type} ({$type}_id),
		KEY meta_key (meta_key)
	) $charset_collate";