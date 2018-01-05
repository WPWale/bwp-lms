<?php

/**
 * 
 */

namespace BWP_LMS\App\Roles\Unit;

trait Progress {

	public function register() {
		$this->allow();
		$this->set_status( 'registered' );
	}

	public function start() {
		$this->initialize();
	}

	public function initialize() {
		$this->allow();
		$this->set_status( 'initialized' );
	}

	public function request_attention( $question ) {
		$this->set_status( 'requested-attention' );
		$this->add_meta( 'attention', $question );
	}

	public function pause() {
		$this->suspend();
	}

	public function suspend() {
		$this->set_status( 'suspended' );
	}

	public function resume() {
		$this->set_status( 'resumed' );
	}

	public function complete( $success = NULL ) {

		switch ( $success ) {
			case NULL:
				$this->set_status( 'completed' );
				break;
			case false:
				$this->fail();
				break;
			case true:
				$this->pass();
				break;
		}
	}

	public function pass() {
		$this->set_status( 'passed' );
	}

	public function fail() {
		$this->set_status( 'failed' );
	}

	public function set_status( $status, $unit_id = false ) {

		if ( ! in_array( $status, $this->stati ) ) {
			return false;
		}

		if ( ! $unit_id ) {
			$unit_id = $this->ID;
		}

		global $lms;

		$old_status = $lms->journey->route[ $unit_id ][ 'status' ];


		$set_status = apply_filters( "bwp_lms_transition_status", true, $old_status, $status, $unit_id );

		if ( $set_status ) {
			$lms->journey->route[ $unit_id ][ 'status' ] = $status;
		}

		if ( ! empty( $old_status ) ) {

			do_action( "bwp_lms_transition_status_from_{$old_status}_to_{$status}", $unit_id );

			do_action( "bwp_lms_transition_status_from_{$old_status}", $unit_id );
		}

		do_action( "bwp_lms_transition_status_to_{$status}", $unit_id );
	}

}
