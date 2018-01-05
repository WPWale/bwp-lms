<?php

namespace BWP_LMS\App\Data;

/*
 * Handling Modules?
 * 
 * Changes in Course Map don't mean that the modules or units placed on the map
 * need to change. Think of them as instances of the original modules.
 * 
 * We need to get the title and the edit link for each unit and module when displaying in admin
 */
/*
 * Initially, we build as post types, we assume non-shareable modules and units
 * Next, we iterate.
 */

class Pathway {
	/*
	 * get the units
	 * tree them and order each sub-tree
	 */

	private $path_id;
	private $tablename;

	public function __construct() {

		$this->tablename = \BWP_LMS\TABLE_PREFIX . 'pathways';
		

	}

	public function get_route( $path_id=false, $role = 0 ) {
		
		if(!$path_id){
			$path_id = $this->path_id;
		}
		
		if(!$path_id){
			return false;
		}
		
		global $wpdb;

		// join both the tables to get it
		$raw_unit_list = $wpdb->get_results(
			"SELECT * from $this->tablename"
			. " WHERE path_id = '$path_id'"
			. " AND role >= $role"
			. " ORDER BY unit_order ASC", ARRAY_A
		);

		$route = $this->_indexed($raw_unit_list);
		
		return $route;
	
	}
	
	private function _indexed( $raw_unit_list ) {
		$indexed_unit_list = array();

		foreach ( $raw_unit_list as $unit ) {
			$indexed_unit_list[ $unit[ 'unit_id' ] ] = $unit;
		}

		unset( $unit );

		return $indexed_unit_list;
	}
	
	
	
	/**
	 * public function save( $map='' , $path_id=false) {
	
		
		if(!$path_id){
			$path_id = $this->path_id;
		}
		
		if(empty($map)){
			$map = $this->map;
		}

		$map_for_db = $this->sanitize($map);
		
		global $wpdb;

		$updated = $wpdb->update(
			$this->tablename, array(
			'pathway' => $map_for_db,
			), array(
			'path_id' => $path_id,
			), array(
			'%s',
			) );
		
		$this->refresh($path_id);
		return $updated;
	}
	
	public function delete($path_id){
		if(!$path_id){
			$path_id = $this->path_id;
		}

		global $wpdb;

		// Using where formatting.
		$deleted = $wpdb->delete( $this->tablename, array(
			'path_id' => $path_id,
			), array(
			'%d',
			)
		);

		return $deleted;
	}
	
	private function sanitize($map = '{}'){
		return $map;
	}
	
	public function order($map){
		
		
		
	}

	private function _indexed( $raw_unit_list ) {
		$indexed_unit_list = array();

		foreach ( $raw_unit_list as $unit ) {
			$unit[ 'edit_link' ] = get_edit_post_link( $unit[ 'unit_id' ] );
			$indexed_unit_list[ $unit[ 'unit_id' ] ] = $unit;
		}

		unset( $unit );

		return $indexed_unit_list;
	}

	private function _treed( $indexed_unit_list ) {

		// key the array by id
		$treed_units = array();


		// tree it
		foreach ( $indexed_unit_list as $unit ) {
			if ( $unit[ 'parent_unit_id' ] > 0 ) {
				$treed_units[ $value[ 'parent_unit_id' ] ][ 'children' ][ $unit[ 'unit_id' ] ] = $unit;
			}
		}
		unset( $unit );

		return $treed_units;
	}

	public function set( $map, $path_id = false ) {

		$this->init( $path_id );

		$saved_map = $this->_get_raw();

		foreach ( $map as $map_unit_id => $map_unit ) {

			if ( $this->is_unit_deleted( $map_unit ) ) {

				$this->delete_unit( $map_unit );
			} else if ( is_unit_new( $map_unit_id, $saved_map ) ) {

				$this->add_unit( $map_unit );
			} else if ( is_unit_modified( $map_unit, $saved_map ) ) {

				$this->update_unit( $map_unit );
			}
			// the ones that haven't changed don't need to be updated in db
		}

		return $map;
	}

	public function delete( $path_id = false ) {
		$this->init( $path_id );

		$saved_map = $this->_get_raw();

		foreach ( $saved_map as $map_unit_id => $map_unit ) {
			$this->delete_unit( $unit_id );
		}
	}

	public function set_from_json( $json = '{}', $path_id = false ) {

		$raw_map = json_decode( $json, true );

		$final_map = array();

		$order_count = 0;

		$this->format_from_json_recurse( $raw_map, &$final_map, &$order_count );

		$this->set( $final_map, $path_id );
	}

	private function format_from_json_recurse( $raw_map, &$final_map, &$order_count ) {
		foreach ( $raw_map as $id => $map_unit ) {
			$final_map[ $id ] = $map_unit;
			$final_map[ $id ][ 'unit_order' ] = $order_count;
			$order_count ++;
			if ( isset( $map_unit[ 'children' ] ) ) {
				$this->format_from_json_recurse( $map_unit, &$final_map, &$order_count );
			}
		}
	}

	private function is_unit_deleted( $unit ) {
		if ( $unit[ 'deleted' ] === true ) {
			return true;
		}
		return false;
	}

	private function is_unit_new( $unit_id, $raw_map ) {
		if ( ! array_key_exists( $unit_id, $raw_map ) ) {
			return true;
		}

		return false;
	}

	private function is_unit_modified( $unit, $raw_map ) {
		return $unit == $raw_map[ $unit[ 'unit_id' ] ];
	}

	public function register_user( $user_id ) {
		// create a new journey for the user based on the path;
	}
*/
}
