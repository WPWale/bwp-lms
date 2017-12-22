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

class Path_Map {

	public $path_id;

	public function __construct( $path_id = false ) {
		$this->init( $path_id );
	}

	public function init( $path_id = false ) {
		if ( ! $path_id ) {
			global $bwp_lms;
			$path_id = $bwp_lms->path_id;
		}

		$this->path_id = $path_id;
	}

	public function get( $path_id = false, $for_admin = false ) {

		$this->init( $path_id );

		$raw_course_map = $this->_get_raw( $for_admin );

		// key the array by id
		$final_course_map = array();

		foreach ( $raw_course_map as &$value ) {
			$final_course_map[ $value[ 'unit_id' ] ] = &$value;
		}
		unset( $value );

		// tree it


		foreach ( $final_course_map as &$value ) {
			if ( $value[ 'parent_unit_id' ] > 0 ) {
				$final_course_map[ $value[ 'parent_unit_id' ] ][ 'children' ][ $value[ 'unit_id' ] ] = &$value;
			}
		}
		unset( $value );


		return $final_course_map;
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

	private function _get_raw( $for_admin = false ) {
		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-course-map';

		// join both the tables to get it
		$course_map_raw = $wpdb->get_results(
			"SELECT * from $map_table_name"
			. " WHERE path_id = '$this->path_id'", ARRAY_A
		);

		return $course_map_raw;
	}

	private function add_unit( $unit ) {

		if ( ! isset( $unit[ 'unit_id' ] ) ) {
			return false;
		}

		if ( ! isset( $unit[ 'parent_unit_id' ] ) ) {
			$unit[ 'parent_unit_id' ] = 0;
		}

		if ( ! isset( $unit[ 'path_id' ] ) ) {
			$this->init();
			$unit[ 'path_id' ] = $this->path_id;
		}

		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-course-map';

		$inserted = $wpdb->insert(
			$map_table_name, array(
			'unit_id' => $unit[ 'unit_id' ],
			'unit_type' => $unit[ 'unit_type' ],
			'parent_unit_id' => $unit[ 'parent_unit_id' ],
			'unit_order' => $unit[ 'unit_order' ],
			'path_id' => $unit[ 'path_id' ],
			), array(
			'%d',
			'%s',
			'%d',
			'%d',
			'%d',
			) );
		return $inserted;
	}

	private function update_unit( $unit ) {

		if ( ! isset( $unit[ 'unit_id' ] ) ) {
			return false;
		}

		if ( ! isset( $unit[ 'parent_unit_id' ] ) ) {
			$unit[ 'parent_unit_id' ] = 0;
		}

		if ( ! isset( $unit[ 'path_id' ] ) ) {
			$this->init();
			$unit[ 'path_id' ] = $this->path_id;
		}

		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-course-map';

		$updated = $wpdb->update(
			$map_table_name, array(
			'parent_unit_id' => $unit[ 'parent_unit_id' ],
			'unit_order' => $unit[ 'unit_order' ],
			), array(
			'unit_id' => $unit[ 'unit_id' ],
			'unit_type' => $unit[ 'unit_type' ],
			'path_id' => $unit[ 'path_id' ],
			), array(
			'%d',
			'%d',
			) );
		return $updated;
	}

	private function delete_unit( $unit_id, $path_id = false ) {

		$this->init( $path_id );

		if ( ! $this->path_id ) {
			return false;
		}

		if ( ! $unit_id ) {
			return false;
		}

		global $wpdb;

		$map_table_name = $wpdb->prefix . 'bwp-lms-course-map';

		// Using where formatting.
		$deleted = $wpdb->delete( $map_table_name, array(
			'unit_id' => $unit_id,
			'path_id' => $path_id
			), array(
			'%d',
			'%d'
			)
		);

		return $deleted;
	}

}
