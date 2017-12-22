<?php

/**
 * 
 */

namespace BWP_LMS\App\Core;

/**
 * 
 */
class Path {
	/*
	 * It is essential that the user launches a course
	 * 
	 * That means a user should not be able to land directly onto a module, lesson or test.
	 * 
	 * Otherwise, you won't be able to share units (lessons, tests) and modules between courses.
	 * 
	 * So, when a user lands on a unit or a module when their is no current course set,
	 * it is like the detatched HEAD state of git.
	 * 
	 * This is similar to the condition of logging in.
	 * 
	 * In the end we'd like to make units and modules inaccessible publicly via WP templating,
	 * and load them in a js app on the course template.
	 */

	private $path;
	/**
	 * LMS content types
	 * 
	 * @var array
	 */
	private $types;
	
	/**
	 * Whether we're on a course
	 * 
	 * @var boolean
	 */
	public $on_path = false;


	/**
	 * The current course's post object
	 * @var object 
	 */
	public $post;

	/**
	 * The current course's pathway map
	 * @var array 
	 */
	public $map;

	/**
	 * 
	 */
	public function __construct( $path, $types ) {

		$this->on_path = $this->on_path($path);

		// we're not a course, nothing to do, bail
		if ( ! $this->on_path ) {
			return;
		}

		// else, we're on a course, proceed
		// setup registered types
		$this->types = $types;

		// initialise course
		$this->setup_path_post();

		// initialise map
		$this->setup_path_map();
	}

	public function on_path($path) {
		// by now the global $coursname should've been set by WP_Query
		$typename= "{$type}name";
		
		global ${$typename};

		if ( ! isset( ${$typename} ) ) {
			return false;
		}

		if ( empty( ${$typename} ) ) {
			return false;
		}

		return true;
	}


	public function setup_path_post() {

		global $coursename;

		$course = get_posts(
			array(
				'post_type' => 'course',
				'name' => $coursename,
			) );
		$this->post = is_array( $course ) ? $course[ 0 ] : false;
		$this->course_id = $this->post->ID;
	}

	public function setup_course_map() {

		$map = new \BWP_LMS\App\Data\Course_Map( $this->course_id );
		$this->map = $map->get( $this->course_id );
		$this->map[ $this->unit_id ][ 'current' ] = true;
	}

}
