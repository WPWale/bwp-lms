<?php

namespace BWP_LMS\App\Data;

class Journey_Unit implements \BWP_LMS\App\Data\Unit {

	private $path_id;
	private $tablename;

	public function __construct( $path_id, $user_id ) {

		$this->path_id = $path_id;

		$this->user_id = $user_id;

		$this->tablename = \BWP_LMS\TABLE_PREFIX . 'journeys';
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
			'user_id' => $unit[ 'user_id' ],
			'path_id' => $unit[ 'path_id' ],
			'status' => $unit[ 'status' ],
			'visible' => $unit[ 'visible' ],
			'allowed' => $unit[ 'allowed' ],
			), array(
			'%d',
			'%d',
			'%d',
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
			'status' => $unit[ 'status' ],
			'visible' => $unit[ 'visible' ],
			'allowed' => $unit[ 'allowed' ],
			), array(
			'unit_id' => $unit[ 'unit_id' ],
			'user_id' => $unit[ 'user_id' ],
			'path_id' => $unit[ 'path_id' ],
			), array(
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
			'user_id' => $unit[ 'user_id' ],
			), array(
			'%d',
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

		if ( ! isset( $unit[ 'path_id' ] ) ) {
			$unit[ 'path_id' ] = $this->path_id;
		}

		if ( ! isset( $unit[ 'user_id' ] ) ) {
			$unit[ 'user_id' ] = $this->user_id;
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
			. " WHERE unit_id = '$unit_id'"
			. " AND user_id = '$this->user_id'", ARRAY_A
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

}