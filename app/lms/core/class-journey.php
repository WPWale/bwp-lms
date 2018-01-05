<?php
/**
 * 
 */

namespace BWP_LMS\App\LMS\Core;

use BWP_LMS\App\LMS as LMS;

use BWP_LMS\App\Data as Data;


/**
 * 
 */
class Journey {
	
	public $route = false;
	
	public $map = false;
	
	public $map_utils;
	
	public $previous = false;
	public $current = false;
	public $next = false;
	
	function __construct($path_id, $user = false) {
		
		$this->map_utils = new LMS\Utilities\Map();
		
		$this->init($path_id, $user);
	}
	
	function init($path_id, $user){
		
		$this->populate($path_id, $user);
	}
	
	private function populate($path_id, $user){
		
		$path = new Data\Pathway();
		
		$role = ( int ) array_search( $user->persona, bwp_lms()->registered_roles );
		
		$path_route = $path->get_route($path_id, $role );
		
		if(!$path_route){
			return false;
		}
		
		$this->setup_map($path_id, $path_route, $user);
	}
	
	private function setup_map($path_id, $path_route, $user){
		
		$journey = new Data\Journey($path_id, $user);
		
		$journey_route = $journey->get_route();
		
		$merged_steps = $this->map_utils->merge_recursive($path_route, $journey_route);
		
		$this->route = $this->order($merged_steps);
		
		$map = $this->map_utils->map_from_route($merged_steps);
		
		$this->map = $this->order($map);

	}
	
	private function order($map){
		$counter = 0;
		
		return $this->map_utils->order_recursive($map, &$counter);
	}
	
	
	
	public function refresh(){
		
	}
	
	
}
	

