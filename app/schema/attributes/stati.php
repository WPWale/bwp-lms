<?php

return $stati = array(
	array(
		'name' => 'registered',
		'timeline' => 'before',
		'verb' => 'http://adlnet.gov/expapi/verbs/registered',
	),
	array(
		'name' => 'initialized',
		'timeline' => 'on',
		'verb' => 'http://adlnet.gov/expapi/verbs/initialized',
	),
	array(
		'name' => 'requested-attention',
		'timeline' => 'on',
		'verb' => 'http://id.tincanapi.com/verb/requested-attention',
	),
	array(
		'name' => 'suspended',
		'timeline' => 'on',
		'verb' => 'http://adlnet.gov/expapi/verbs/suspended',
	),
	array(
		'name' => 'resumed',
		'timeline' => 'on',
		'verb' => 'http://adlnet.gov/expapi/verbs/resumed',
	),
	array(
		'name' => 'completed',
		'timeline' => 'after',
		'verb' => 'http://adlnet.gov/expapi/verbs/completed',
	),
	array(
		'name' => 'passed',
		'timeline' => 'after',
		'verb' => 'http://adlnet.gov/expapi/verbs/passed',
	),
	array(
		'name' => 'failed',
		'timeline' => 'after',
		'verb' => 'http://adlnet.gov/expapi/verbs/failed',
	),
);

