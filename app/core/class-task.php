<?php
namespace BWP_LMS\App\Core;
class Task{
	public $type;
	public $stem;
	public $key = false;
	public $status;
	public $score;
	public $objective;
	public $object;
	
	public $stati;
	
	
	public function __construct($type, $object){
		
		$this->type = $type;
		$this->object = $object;
		
		$this->stati = bwp_lms()->stati;
	}
	
	public function complete( $success = NULL, $score = NULL ) {

		switch ( $success ) {
			case NULL:
				$this->set_status( 'completed', $this->object );
				break;
			case false:
				$this->fail( $score );
				break;
			case true:
				$this->pass( $score );
				break;
		}
		
		do_action( "bwp_lms/{$this->type}/complete", $this->object, $score );
	}

	public function pass( $score ) {
		$this->set_status( 'passed', $score );
		do_action( "bwp_lms/{$this->type}/pass", $this->object, $score );
	}

	public function fail( $score ) {
		$this->set_status( 'failed', $score );
		do_action( "bwp_lms/{$this->type}/fail", $this->object, $score );
	}

	public function set_status( $status, $score ) {

		if ( ! in_array( $status, $this->stati ) ) {
			return false;
		}

		$old_status = $this->object[ 'status' ];

		$did_transition = $do_transition = apply_filters( "bwp_lms/{$this->type}/do_transition", true, $old_status, $status, $this->object, $score );

		if ( $do_transition ) {
			$this->object[ 'status' ] = $status;
		}

		if ( ! empty( $old_status ) ) {

			do_action( "bwp_lms/{$this->type}/transition/from_{$old_status}_to_{$status}", $this->object, $did_transition, $score );

			do_action( "bwp_lms/{$this->type}/transition/from_{$old_status}", $this->object, $did_transition, $score );
		}
		
		do_action( "bwp_lms/{$this->type}/transition", $this->object, $did_transition, $score );

		do_action( "bwp_lms/{$this->type}/transition/to_{$status}", $did_transition, $this->object, $score );
	}
}

