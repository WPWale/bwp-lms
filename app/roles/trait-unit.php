<?php
namespace BWP_LMS\App\Roles;

use BWP_LMS\App\Roles as Roles;

trait Unit {
	
	public $ID;
	public $type;
	public $stati;
	
	use Roles\Unit\Position;
	use Roles\Unit\Visibility;
	use Roles\Unit\Meta;
	use Roles\Unit\Progress;
	
	
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

		$this->stati = bwp_lms()->stati;
	}
	
}
