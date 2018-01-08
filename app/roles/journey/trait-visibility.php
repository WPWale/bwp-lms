<?php
namespace BWP_LMS\App\Roles\Journey;

trait Visibility{
	
	public function show( $visible = true ) {
		$this->object[ 'visible' ] = $visible;
	}

	public function hide($unit) {
		$this->show( false );
	}

	public function allow( $allowed = true ) {
		$this->object[ 'allowed' ] = $allowed;
	}

	public function disallow($unit) {
		$this->allow( false );
	}
	
}
