<?php

/**
 * 
 */

namespace BWP_LMS\App\LMS\Core;

/**
 * 
 */
class User {

	public $ID = 0;
	public $role = 'public';
	public $persona = 'public';
	private $registered_roles;

	function __construct($path_id, $user_id = false) {

		$this->registered_roles = bwp_lms()->roles;
		
		if(!$user_id){
			$user_id  = get_current_user_id();
		}
		
		$this->ID = $user_id;

		$this->role = $this->set_role($path_id);

		$this->persona = $this->set_persona();
	}

	/**
	 * 
	 * @return type
	 */
	public function set_role( $path_id = false ) {

		// default $role is public;
		if ( ! is_user_logged_in() ) {
			return 'public';
		}

		/*
		 * look for path level role
		 * 
		 * if not, get their default/ global role
		 * 
		 * then, if they aren't learner, they are allowed to have any persona
		 */

		$role = $this->get_role_for_path( $path_id );


		if ( empty( $role ) ) {

			// global role

			$role = get_user_meta( $this->ID, '_bwp_lms_global_role', true );
			
			// there should be a way to check for course-category level persona as well
		}

		if ( is_role( $role ) ) {
			return $role;
		}

		return 'public';
	}

	public function get_role_for_path( $path_id = 'false' ) {

		if ( ! $path_id ) {
			global $lms;
			$path_id = $lms->path->ID;
		}

		global $wpdb;

		$table_name = \BWP_LMS\TABLE_PREFIX . 'path_roles';

		$role = $wpdb->get_var( "SELECT role from $table_name WHERE user_id = '$this->ID' AND path_id =' $path_id'" );

		return $role;
	}

	public function set_persona( $persona = false ) {

		// if no persona is passed, see if there's one in the get request
		if ( $persona === false ) {
			$persona = filter_input( \INPUT_GET, 'lms-persona', FILTER_CALLBACK, array( 'options' => array( $this, 'filter_persona' ) ) );
		}

		// if there's nothing in the get request, look for a cookie
		if ( $persona === false ) {
			$persona = filter_input( \INPUT_COOKIE, 'lms-persona', FILTER_CALLBACK, array( 'options' => array( $this, 'filter_persona' ) ) );
		} else {
			// otherwise, take the persona from get and set as a cookie for future
			setcookie( 'lms-persona', $persona, 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
			return $persona;
		}

		// not in get request, not in cookie, use the default role as persona
		if ( $persona === false ) {

			return $this->role;
		}
	}

	function filter_persona( $persona ) {

		if ( empty( $persona ) ) {
			return false;
		}

		if ( ! $this->is_role( $persona ) ) {
			return false;
		}

		if ( ! $this->is_persona_allowed_for_role( $persona ) ) {
			return false;
		}

		return $persona;
	}

	function is_role( $role ) {
		if ( empty( $role ) ) {
			return false;
		}
		if ( in_array( $role, $this->registered_roles ) ) {
			return true;
		}

		return false;
	}

	function is_persona_allowed_for_role( $persona ) {

		if ( $this->get_level( $persona ) <= $this->get_level( $this->role ) ) {
			return true;
		}

		return false;
	}

	function get_level( $role ) {
		return ( int ) array_search( $role, $this->registered_roles );
	}

}
