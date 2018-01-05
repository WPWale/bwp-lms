<?php
namespace BWP_LMS\App\Data;

class Journey{
	
	public $path_post;
	
	public $path_id;
	
	public $user_id;
	
	private $tablename;

	public function __construct( $path_id, $user ) {
		
		$this->user = $user;
		
		$this->path_id = $path_id;
		
		$pathway =  new \BWP_LMS\App\Data\Pathway($this->path_id);
		
		$this->pathway = $pathway->get_raw();

		$this->tablename = \BWP_LMS\TABLE_PREFIX . 'journeys';
	}

	public function get() {

		$step_list = $this->get_raw();
		$indexed_step_list = $this->_indexed($step_list);
		$treed_steps = $this->_treed($indexed_step_list);
		

		return $treed_steps;
	}
	
	
	public function get_route() {
		global $wpdb;

		// join both the tables to get it
		$step_list = $wpdb->get_results(
			"SELECT * from $this->tablename"
			. " WHERE path_id = '$this->path_id'"
			. " AND user_id = '$this->user_id'", ARRAY_A
		);

		return $step_list;
	}
	
	private function _indexed($raw_unit_list){
		$indexed_unit_list = array();
		
		foreach ( $raw_unit_list as $unit ) {
			$indexed_unit_list[ $unit[ 'unit_id' ] ] = array_merge($this->pathway[$unit['unit_id']],$unit);
			
			
		}
		
		unset( $unit );
		
		return $indexed_unit_list;
	}
	
	private function _treed($indexed_unit_list){
		
		// key the array by id
		$treed_units = array();

		
		// tree it

		foreach ( $indexed_unit_list as $unit ) {
			if ( $value[ 'parent_unit_id' ] > 0 ) {
				$treed_units[ $value[ 'parent_unit_id' ] ][ 'children' ][ $unit[ 'unit_id' ] ] = $unit;
			}
		}
		unset( $unit );

		return $treed_units;
	}
	
	public function create(){
		
	}
	
	public function update(){
		
	}
	
	public function refresh(){
		$this->save();
		$this->get();
	}
	

}