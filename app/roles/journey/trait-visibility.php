<?php
namespace BWP_LMS\App\Roles\Journey;

trait Visibility{
	
	public function show( $unit, $visible = true ) {
		
		if(!$unit){
			$unit = $this->current;
		}

		$this->route[ $unit['unit_id'] ][ 'visible' ] = $visible;
	}

	public function hide($unit) {
		$this->show( $unit, false );
	}

	public function allow( $unit, $allowed = true ) {

		if(!$unit){
			$unit = $this->current;
		}
		
		$this->show( $unit, $allowed );
		
		$this->route[ $unit['unit_id'] ][ 'allowed' ] = $allowed;
	}

	public function disallow($unit) {
		$this->allow( $unit, false );
	}
	
}
