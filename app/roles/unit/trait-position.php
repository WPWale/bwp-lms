<?php

namespace BWP_LMS\App\Roles\Unit;

trait Position{
	
	public $is_first_in_path = false;
	public $is_last_in_path = false;
	
	public $is_first_child = false;
	public $is_last_child = false;
	
	public $is_root = false;
	public $is_leaf = false;
	
	public $siblings = array();
	public $parent_tree = array();
	public $children = array();
	
	public function set_position_in_path() {

		global $lms;

		$route = $lms->journey->route;

		$first_step = reset( $route );

		if ( $first_step[ 'unit_id' ] === $this->ID ) {
			$this->is_first_in_path = true;
			return;
		}

		// sets the pointer to the current step on map
		while ( key( $route ) !== $this->ID ) {
			next( $route );
		}

		// one step earlier is the previous unit;
		$this->previous = prev( $route );

		// point back to current;
		$this->current = next( $route );

		// one more step to next
		$this->next = next( $route );

		if ( ! $this->next ) {
			$this->is_last_in_path = true;
		}
	}

	public function set_position_under_parent() {

		global $lms;
		$route = $lms->journey->route;

		$immediate_parent = $route[ $this->ID ][ 'parent_unit_id' ];

		foreach ( $route as $unit ) {
			if ( $unit[ 'unit_id' ] === $this->ID ) {
				$this->is_root = ( int ) $unit[ 'parent_unit_id' ] < 1;

				$this->is_leaf = ! isset( $unit[ 'children' ] );
			}

			if ( $unit[ 'parent_unit_id' ] === $this->ID ) {
				$this->children[ $unit[ 'unit_id' ] ] = $unit;
			}

			if ( $unit[ 'parent_unit_id' ] === $immediate_parent ) {
				$this->siblings[ $unit[ 'unit_id' ] ] = $unit;
			}
		}
	}
	
	public function set_parent_tree(){
		
		global $lms;
		
		$map = $lms->journey->map;

		$this->parent_tree = $this->get_parent_tree( $map, $this->ID, true );

	}

	public function set_position_in_siblings() {

		$first_step = reset( $this->siblings );

		if ( $first_step[ 'unit_id' ] === $this->ID ) {
			$this->is_first_child = true;
			return;
		}

		// sets the pointer to the current step on map
		while ( key( $this->siblings ) !== $this->ID ) {
			next( $this->siblings );
		}

		// one more step to next
		$this->next = next( $this->siblings );

		if ( ! $this->next ) {
			$this->is_last_child = true;
		}
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
