<?php

namespace BWP_LMS\App\LMS\Course_Objects;

use BWP_LMS\App\Roles as Roles;

class Content_Unit {

	use Roles\Unit;

	function __construct( $unit = false ) {

		$this->init($unit);
	}

	
}