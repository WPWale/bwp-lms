<?php

namespace BWP_LMS\App\Data;

class Module {

	public function add( $post_data ) {
		
	}

	public function get( $module_id ) {

		return $this->get_module_heirarchy( $module_id );
	}

	/**
	 * Recursively get taxonomy and its children
	 *
	 * @param string $taxonomy
	 * @param int $parent - parent term id
	 * @return array
	 */
	function get_module_hierarchy( $parent = 0 ) {

		// get all direct decendants of the $parent
		$terms = get_terms( 'module', array( 'parent' => $parent ) );
		// prepare a new array.  these are the children of $parent
		// we'll ultimately copy all the $terms into this new array, but only after they
		// find their own children
		$children = array();
		// go through all the direct decendants of $parent, and gather their children
		foreach ( $terms as $term ) {
			$term->units = $this->get_units( $term->term_id );
			// recurse to get the direct decendants of "this" term
			$term->children = get_taxonomy_hierarchy( 'module', $term->term_id );
			// add the term to our new array
			$children[ $term->term_id ] = $term;
		}
		// send the results back to the caller
		return $children;
	}

	function get_units( $module_id ) {
		$post_args = array(
			'post_type' => array( 'test', 'lesson' ),
			'post_status' => 'any',
			'order' => 'ASC',
			'order_by' => 'menu_order',
			'tax_query' => array(
				array(
					'taxonomy' => 'module',
					'terms' => $module_id,
				),
			),
		);

		return $units = get_posts( $post_args );
	}

	public function update( $post_data ) {
		
	}

	public function delete( $post_id ) {
		
	}

}
