<?php
namespace BWP_LMS\App\LMS\Core;

class Path{
	
	public $ID = false;
	
	public $type = false;
	
	public $name = '';
	
	public $post;
	
	public function __construct() {
		$types = bwp_lms()->content_types;


		foreach ( $types[ 'paths' ] as $path ) {
			$pathname = "{$path}name";
			global ${$pathname};

			// we're not a course, nothing to do, bail
			if ( ! $this->on_path( ${$pathname} ) ) {
				continue;
			}

			$this->type = $path;
			$this->name = ${$pathname};
			break;
		}

		unset( $path );

		if ( empty( $this->name ) ) {
			return;
		}

		$this->post = $this->setup_path_post();


		if ( empty( $this->post ) ) {
			return;
		}

		$this->ID = $this->post->ID;

	}
	
	public function on_path( $pathname ) {

		if ( ! isset( $pathname ) ) {
			return false;
		}

		if ( empty( $pathname ) ) {
			return false;
		}

		return true;
	}

	public function setup_path_post() {


		$path = get_posts(
			array(
				'post_type' => $this->type,
				'name' => $this->name,
			) );
		return is_array( $path ) ? $path[ 0 ] : false;
	}

}