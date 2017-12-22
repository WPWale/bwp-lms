<?php

namespace BWP_LMS\App\Data;

/*
 * When a module's map changes, it mutates the actual module
 * So when a unit is deleted from the map, the unit is actually deleted from db
 * 
 * Deleteing modules that contain units
 *  * Move all units to a parent module ?
 *  * Disallow deleting, if their is no parent module (V seems like the right way)
 *  * Orphan units?
 */
class Module_Map {

	public $module_id;

	public function __construct( $module_id = false ) {
		$this->init( $module_id );
	}

	public function init( $module_id = false ) {
		if ( ! $module_id ) {
			global $bwp_lms;
			$module_id = $bwp_lms->module_id;
		}

		$this->module_id = $module_id;
	}

	public function get( $module_id = false ) {

		$this->init( $module_id );

		$raw_module_map = $this->_get_raw();

		// key the array by id
		$final_module_map = array();

		foreach ( $raw_module_map as &$value ) {
			$final_module_map[ $value[ 'unit_id' ] ] = &$value;
		}
		unset( $value );

		// tree it


		foreach ( $final_module_map as &$value ) {
			if ( $value[ 'parent_unit_id' ] > 0 ) {
				$final_module_map[ $value[ 'parent_unit_id' ] ][ 'children' ][ $value[ 'unit_id' ] ] = &$value;
			}
		}
		unset( $value );


		return $final_module_map;
	}

	public function set( $map, $module_id = false ) {

		$this->init( $module_id );

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

	public function delete( $module_id = false ) {
		$this->init( $module_id );

		$saved_map = $this->_get_raw();

		foreach ( $saved_map as $map_unit_id => $map_unit ) {
			$this->delete_unit( $unit_id );
		}
	}

	public function set_from_json( $json = '{}', $module_id = false ) {

		$raw_map = json_decode( $json, true );

		$final_map = array();

		$order_count = 0;

		$this->format_from_json_recurse( $raw_map, &$final_map, &$order_count );

		$this->set( $final_map, $module_id );
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

	private function _get_raw() {
		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-module-map';

		// join both the tables to get it
		$module_map_raw = $wpdb->get_results(
			"SELECT * from $map_table_name"
			. " WHERE module_id = '$this->module_id'", ARRAY_A
		);

		return $module_map_raw;
	}

	private function add_unit( $unit ) {
		
		//since we're actually going to create a new unit
		// (no other way to add posts at least)
		// we'll first create the unit and get its unit_id
		// then update the database

		if ( ! isset( $unit[ 'unit_id' ] ) ) {
			$unit['unit_id'] = $this->add_wp_unit($unit);
		}

		if ( ! isset( $unit[ 'parent_unit_id' ] ) ) {
			$unit[ 'parent_unit_id' ] = 0;
		}

		if ( ! isset( $unit[ 'module_id' ] ) ) {
			$this->init();
			$unit[ 'module_id' ] = $this->module_id;
		}

		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-module-map';

		$inserted = $wpdb->insert(
			$map_table_name, array(
			'unit_id' => $unit[ 'unit_id' ],
			'unit_type' => $unit[ 'unit_type' ],
			'parent_unit_id' => $unit[ 'parent_unit_id' ],
			'unit_order' => $unit[ 'unit_order' ],
			'module_id' => $unit[ 'module_id' ],
			), array(
			'%d',
			'%s',
			'%d',
			'%d',
			'%d',
			) );
		
		return $inserted;
	}
	
	private function add_wp_unit($unit){
		switch ($unit['unit_type']){
			case 'module':
				$wp_item = wp_insert_term($unit['name'], 'module', array('parent' => $unit['parent_unit_id']));
				break;
			case 'lesson':
			case 'test':
				// insert the post (lesson/ test)
				$wp_item = wp_insert_post(
					array(
						'post_title' => $unit['name'],
						'menu_order' => $unit['unit_order'],
					)
					);
				// assign parent module to post
				wp_set_post_terms( $wp_item, $unit['parent_unit_id'], 'module' );
				break;
			default:
				break;
		}
	}

	private function update_unit( $unit ) {

		if ( ! isset( $unit[ 'unit_id' ] ) ) {
			return false;
		}

		if ( ! isset( $unit[ 'parent_unit_id' ] ) ) {
			$unit[ 'parent_unit_id' ] = 0;
		}

		if ( ! isset( $unit[ 'module_id' ] ) ) {
			$this->init();
			$unit[ 'module_id' ] = $this->module_id;
		}

		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-module-map';

		$updated = $wpdb->update(
			$map_table_name, array(
			'parent_unit_id' => $unit[ 'parent_unit_id' ],
			'unit_order' => $unit[ 'unit_order' ],
			), array(
			'unit_id' => $unit[ 'unit_id' ],
			'unit_type' => $unit[ 'unit_type' ],
			'module_id' => $unit[ 'module_id' ],
			), array(
			'%d',
			'%d',
			) );
		$this->update_wp_unit($unit);
		return $updated;
	}
	
	private function update_wp_unit($unit){
		switch ($unit['unit_type']){
			case 'module':
				$wp_item = wp_update_term($unit['unit_id'], 'module', array('parent' => $unit['parent_unit_id']));
				break;
			case 'lesson':
			case 'test':
				// insert the post (lesson/ test)
				$wp_item = wp_update_post(
					array(
						'ID' => $unit['unit_id'],
						'post_title' => $unit['name'],
						'menu_order' => $unit['unit_order'],
					)
					);
				// assign parent module to post
				wp_set_post_terms( $wp_item, $unit['parent_unit_id'], 'module' );
				break;
			default:
				break;
		}
	}

	private function delete_unit( $unit_id, $module_id = false ) {

		$this->init( $module_id );

		if ( ! $this->module_id ) {
			return false;
		}

		if ( ! $unit_id ) {
			return false;
		}

		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-module-map';

		// Using where formatting.
		$deleted = $wpdb->delete( $map_table_name, array(
			'unit_id' => $unit_id,
			'module_id' => $module_id
			), array(
			'%d',
			'%d'
			)
		);

		return $deleted;
	}
	
	private function delete_wp_unit($unit){
		switch ($unit['unit_type']){
			case 'module':
				$wp_item = wp_delete_term($unit['unit_id'], 'module');
				break;
			case 'lesson':
			case 'test':
				// insert the post (lesson/ test)
				$wp_item = wp_delete_post($unit['unit_id']);
				break;
			default:
				break;
		}
	}


}
