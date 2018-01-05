<?php

namespace BWP_LMS\App\Data;

interface Unit {

	public function add( $unit );

	public function update( $unit );

	public function delete( $unit );

	public function sanitize( $unit );

	public static function get_all( $unit_id );

	public static function delete_all( $unit_id );
}
