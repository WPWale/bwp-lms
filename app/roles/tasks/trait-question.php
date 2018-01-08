<?php
namespace BWP_LMS\App\Roles\Tasks;

trait Question{
	public $stem;
	public $key;
	public $response;
	public $result;
	
	abstract function set_key($key);
}
