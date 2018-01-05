<?php

namespace BWP_LMS\App\Roles;

trait Content {

	public $type;
	public $id;

	public function crud_hooks() {
		add_action( "save_post_{$this->type}", array( $this, 'add' ), 10, 3 );
		add_filter( 'the_posts', array( $this, 'get' ), 10, 2 );
		add_action( 'delete_post', array( $this, 'delete' ), 10, 1 );
	}

	public function add( $unit_id, $unit_post, $update ) {

		if ( $update === true ) {
			return $this->update( $unit_id, $unit_post );
		}

		return $this->_add( $unit_id, $unit_post );
	}

	abstract public function _add( $unit_id, $unit_post );

	abstract public function update( $unit_id, $unit_post );

	abstract public function get( $unit_post, $wp_query );

	abstract public function delete( $unit_id );
}
