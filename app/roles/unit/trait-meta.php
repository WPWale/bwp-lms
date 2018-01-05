<?php

namespace BWP_LMS\App\Roles\Unit;

trait Meta {
	
	/**
	 * 
	 * @param type $meta_key
	 * @param type $single
	 */
	public function get_meta( $meta_key, $single = true ) {
		get_metadata( $this->type, $this->id, $meta_key, $single );
	}

	/**
	 * 
	 * @param type $meta_key
	 * @param type $meta_value
	 * @param type $unique
	 */
	public function add_meta( $meta_key, $meta_value, $unique = true ) {
		add_metadata( $this->type, $this->id, $meta_key, $meta_value, $unique );
	}

	/**
	 * 
	 * @param type $meta_key
	 * @param type $meta_value
	 */
	public function update_meta( $meta_key, $meta_value ) {
		update_metadata( $this->type, $this->id, $meta_key, $meta_value );
	}

	/**
	 * 
	 * @param type $meta_key
	 */
	public function delete_meta( $meta_key ) {
		delete_metadata( $this->type, $this->id, $meta_key );
	}

}
