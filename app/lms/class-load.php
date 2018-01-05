<?php

namespace BWP_LMS\App\LMS;

use BWP_LMS\App\LMS\Core as Core;
use BWP_LMS\App\LMS\Course_Objects as Course_Objects;

class Load {

	public $path;
	public $user;
	public $journey;
	public $step;

	/**
	 * Initialise core
	 * 
	 * @param string $plugin_file The main plugin file
	 */
	public function init() {

		// hook into init for single site, priority 0 = highest priority
		add_action( 'init', array( $this, 'init_lms' ), 0 );
	}

	/**
	 * 
	 */
	public function init_lms() {
		
		$path = new Core\Path();
		
		if(!$path->ID){
			return;
		}
		
		$this->path = $path;
		
		$this->user = new Core\User($this->path->ID);

		if ( ! is_admin() ) {
			return;
		}

		$this->journey = new Core\Journey($this->path->ID, $this->user);

		if ( empty( $this->journey->route ) ) {
			$this->registration_redirect();
		}

		$GLOBALS['lms'] = $this;
		
		$GLOBALS['lms_unit'] = new Course_Objects\Content_Unit();
	}

	
	public function registration_redirect() {
		// redirect to course page, if not already on it
		global $post_id;

		if ( $post_id === $this->path->ID ) {
			return;
		}

		wp_safe_redirect( get_permalink( $this->path->ID ) );
		exit();

		// in another place, redirect to the last open, allowed unit
	}

	
}
