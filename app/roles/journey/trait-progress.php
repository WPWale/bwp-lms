<?php

/**
 * 
 */

namespace BWP_LMS\App\Roles\Journey;

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
		$this->set_status( 'initialized');
	}

	/*
	 * Should come in mentor tools later
	public function request_attention( $question ) {
		$this->set_status( 'requested-attention' );
		$this->add_meta( 'attention', $question );
	}
	 */

	public function pause() {
		$this->suspend();
	}

	public function suspend() {
		$this->set_status( 'suspended');
	}

	public function resume() {
		$this->set_status( 'resumed');
	}

}
