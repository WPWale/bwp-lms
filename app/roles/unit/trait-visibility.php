<?php
namespace BWP_LMS\App\Roles\Unit;

trait Visibility{
	
	public function show( $visible = true ) {

		global $lms;

		$lms->journey->route[ $this->ID ][ 'visible' ] = $visible;
	}

	public function hide() {
		$this->show( false );
	}

	public function allow( $allowed = true ) {

		$this->show( $allowed );

		global $lms;

		$lms->journey->route[ $this->ID ][ 'allowed' ] = $allowed;
	}

	public function disallow() {
		$this->allow( false );
	}
	
}
