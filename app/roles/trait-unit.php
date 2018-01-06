<?php
namespace BWP_LMS\App\Roles;

use BWP_LMS\App\Roles as Roles;

trait Unit {
	
	public $ID;
	public $type;
	
	use Roles\Unit\Meta;
	
	
	public function init($unit = false){

		if ( ! $unit ) {
			global $post;

			$unit_id = $post->ID;
			$type = $post->type;
		}

		$unit_id = $unit[ 'unit_id' ];
		$type = $unit[ 'unit_type' ];

		$this->ID = $unit_id;
		$this->type = $type;

	}
	
}
