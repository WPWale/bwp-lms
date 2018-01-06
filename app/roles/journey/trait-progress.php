<?php

/**
 * 
 */

namespace BWP_LMS\App\Roles\Journey;

trait Progress {

	public function register( $unit ) {
		$this->allow( $unit );
		$this->set_status( 'registered', $unit );
	}

	public function start( $unit ) {
		$this->initialize( $unit );
	}

	public function initialize( $unit ) {
		$this->allow( $unit );
		$this->set_status( 'initialized', $unit );
	}

	public function request_attention( $unit, $question ) {
		$this->set_status( 'requested-attention', $unit );
		//$this->add_meta( 'attention', $question );
	}

	public function pause( $unit ) {
		$this->suspend( $unit );
	}

	public function suspend( $unit ) {
		$this->set_status( 'suspended', $unit );
	}

	public function resume( $unit ) {
		$this->set_status( 'resumed', $unit );
	}

	public function complete( $unit = false, $success = NULL ) {

		switch ( $success ) {
			case NULL:
				$this->set_status( 'completed', $unit );
				break;
			case false:
				$this->fail( $unit );
				break;
			case true:
				$this->pass( $unit );
				break;
		}
	}

	public function pass( $unit ) {
		$this->set_status( 'passed', $unit );
	}

	public function fail( $unit ) {
		$this->set_status( 'failed', $unit );
	}

	public function set_status( $status, $unit = false ) {

		if ( ! in_array( $status, $this->stati ) ) {
			return false;
		}

		if ( ! $unit ) {
			$unit = $this->current;
			$unit_id = $this->current[ 'unit_id' ];
		}

		$old_status = $this->route[ $unit_id ][ 'status' ];

		$set_status = apply_filters( "bwp_lms_transition_status", true, $old_status, $status, $unit_id );

		if ( $set_status ) {
			$this->route[ $unit_id ][ 'status' ] = $status;
		}

		if ( ! empty( $old_status ) ) {

			do_action( "bwp_lms_transition_status_from_{$old_status}_to_{$status}", $unit_id );

			do_action( "bwp_lms_transition_status_from_{$old_status}", $unit_id );
		}

		do_action( "bwp_lms_transition_status_to_{$status}", $unit_id );
	}

}
