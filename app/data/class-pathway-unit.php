<?php

namespace BWP_LMS\App\Data;

/*
 * Get the whole pathway
 * delete unit from pathway,
 * reorder pathway
 * save pathway
 * refresh pathway
 */

class Pathway_Unit implements \BWP_LMS\App\Data\Unit {

	private $path_id;
	public function __construct( $path_id = false, $map = false ) {

		if ( ! $path_id ) {
			global $lms;
			$path_id = $lms->path->ID;
		}

		if ( empty( $map ) ) {
			global $lms;
			$map = $lms->path->map;
		}

		$this->path_id = $path_id;

		$this->map = $map;

		$this->tablename = \BWP_LMS\TABLE_PREFIX . 'pathways';
	}
	

	public function add( $unit ) {

		$unit = $this->sanitize( $unit );

		if ( ! $unit ) {
			return false;
		}

		global $wpdb;

		$inserted = $wpdb->insert(
			$this->tablename, array(
			'unit_id' => $unit[ 'unit_id' ],
			'unit_type' => $unit[ 'unit_type' ],
			'parent_unit_id' => $unit[ 'parent_unit_id' ],
			'unit_order' => $unit[ 'unit_order' ],
			'path_id' => $unit[ 'path_id' ],
			'unit_title' => $unit[ 'unit_title' ],
			'status' => $unit[ 'status' ],
			'visible' => $unit[ 'visible' ],
			'allowed' => $unit[ 'allowed' ],
			), array(
			'%d',
			'%s',
			'%d',
			'%d',
			'%d',
			'%s',
			'%s',
			'%d',
			'%d',
			) );
		return $inserted;
	}

	public function update( $unit ) {

		$unit = $this->sanitize( $unit );

		if ( ! $unit ) {
			return false;
		}

		global $wpdb;

		$updated = $wpdb->update(
			$this->tablename, array(
			'parent_unit_id' => $unit[ 'parent_unit_id' ],
			'unit_order' => $unit[ 'unit_order' ],
			'unit_title' => $unit[ 'unit_title' ],
			'status' => $unit[ 'status' ],
			'visible' => $unit[ 'visible' ],
			'allowed' => $unit[ 'allowed' ],
			), array(
			'unit_id' => $unit[ 'unit_id' ],
			'unit_type' => $unit[ 'unit_type' ],
			'path_id' => $unit[ 'path_id' ],
			), array(
			'%d',
			'%d',
			'%d',
			'%s',
			'%s',
			'%d',
			'%d',
			) );
		return $updated;
	}

	public function delete( $unit ) {

		$unit = $this->sanitize( $unit );

		if ( ! $unit ) {
			return false;
		}

		global $wpdb;

		// Using where formatting.
		$deleted = $wpdb->delete( $this->tablename, array(
			'unit_id' => $unit[ 'unit_id' ],
			'path_id' => $unit[ 'path_id' ],
			), array(
			'%d',
			'%d',
			)
		);

		return $deleted;
	}

	public function sanitize( $unit ) {

		if ( ! isset( $unit[ 'unit_id' ] ) ) {
			return false;
		}

		if ( ! isset( $unit[ 'parent_unit_id' ] ) ) {
			$unit[ 'parent_unit_id' ] = 0;
		}

		if ( ! isset( $unit[ 'path_id' ] ) ) {
			$unit[ 'path_id' ] = $this->path_id;
		}

		if ( ! isset( $unit[ 'unit_type' ] ) ) {
			$unit[ 'unit_type' ] = get_post_type( $unit[ 'unit_id' ] );
		}

		if ( ! isset( $unit[ 'status' ] ) ) {
			$unit[ 'status' ] = 'registered';
		}

		if ( ! isset( $unit[ 'visible' ] ) ) {
			$unit[ 'visible' ] = false;
		}

		if ( ! isset( $unit[ 'allowed' ] ) ) {
			$unit[ 'allowed' ] = false;
		}

		return $unit;
	}

	public static function get_all( $unit_id ) {
		if ( ! $unit_id ) {
			return false;
		}

		global $wpdb;

		// join both the tables to get it
		$units = $wpdb->get_results(
			"SELECT * from $this->tablename"
			. " WHERE unit_id = '$unit_id'", ARRAY_A
		);

		return $units;
	}

	public static function delete_all( $unit_id ) {
		if ( ! $unit_id ) {
			return false;
		}

		global $wpdb;

		// Using where formatting.
		$deleted = $wpdb->delete( $this->tablename, array(
			'unit_id' => $unit_id,
			), array(
			'%d',
			)
		);

		return $deleted;
	}

	public static function update_all( $unit_id, $unit_title = '' ) {
		if ( ! $unit_id ) {
			return false;
		}

		global $wpdb;

		$updated = $wpdb->update(
			$this->tablename, array(
			'unit_title' => $unit_title,
			), array(
			'unit_id' => $unit_id,
			), array(
			'%s',
			) );
		return $updated;
	}

}
