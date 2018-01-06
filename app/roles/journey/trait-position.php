<?php

namespace BWP_LMS\App\Roles\Journey;

trait Position {

	public $previous = false;
	public $current = false;
	public $next = false;
	
	public $is_first_in_path = false;
	public $is_last_in_path = false;
	
	public $is_first_child = false;
	public $is_last_child = false;
	
	public $is_root = false;
	public $is_leaf = false;
	public $is_node = true;
	
	public $parent_tree = array();

	public function set_position() {

		$this->set_position_in_path();
		$this->set_position_in_tree();
		$this->set_position_under_parent();
		$this->set_parent_tree();
	}

	public function set_position_in_path() {
		
		global $post;

		$this->current = $this->route[$post->ID];

		$first_step = reset( $this->route );

		if ( $first_step[ 'unit_id' ] === $this->current['unit_id'] ) {
			$this->is_first_in_path = $this->is_first_child = true;
			return;
		}

		// sets the pointer to the current step on map
		while ( key( $this->route ) !== $this->current['unit_id'] ) {
			next( $this->route );
		}

		// one step earlier is the previous unit;
		$this->previous = prev( $this->route );

		// point back to current;
		next( $this->route );

		// one more step to next
		$this->next = next( $this->route );

		if ( ! $this->next ) {
			$this->is_last_in_path = $this->is_last_child = true;
		}
	}

	public function set_position_in_tree() {

		$this->is_root = (( int ) $this->current[ 'parent_unit_id' ] < 1);
		$this->is_leaf = ! isset( $this->current[ 'children' ] );

		$this->is_node = ( ! $this->is_root && ! $this->is_leaf);
	}

	public function set_position_under_parent() {

		if ( ! $this->is_last_in_path ) {
			$this->is_last_child = ($this->next[ 'parent_unit_id' ] !== $this->current[ 'parent_unit_id' ]);
		}

		if ( ! $this->is_first_in_path ) {
			$this->is_first_child = ($this->previous[ 'parent_unit_id' ] !== $this->current[ 'parent_unit_id' ]);
		}
	}

	public function set_parent_tree() {


		$this->parent_tree = $this->get_parent_tree( $this->map, $this->current[ 'unit_id' ], true );
	}

	public function get_parent_tree( $map, $unit_id, $firstLevel = true ) {

		$tree = array();

		foreach ( $map as $unit ) {
			if ( $unit[ 'unit_id' ] == $unit_id ) {
				return array( $unit[ 'unit_id' ] );
			}
			if ( ! isset( $unit[ 'children' ] ) ) {
				continue;
			}

			$tree = $this->get_parent_tree( $unit[ 'children' ], $unit_id, false );

			if ( ! $tree ) {
				continue;
			}

			$tree[] = $unit[ 'unit_id' ];

			if ( ! $firstLevel ) {
				return $tree;
			}
		}

		if ( count( $tree ) ) {
			return array_reverse( $tree );
		}

		return false;
	}

}
