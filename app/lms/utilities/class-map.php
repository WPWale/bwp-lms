<?php

namespace BWP_LMS\App\LMS\Utilities;

class Map {

	private $default_step;

	public function __construct() {
		$this->default_step = include \BWP_LMS\SCHEMA_PATH . 'attributes/step.php';
	}

	public function map_from_route( $route ) {
		if ( ! $route ) {
			return false;
		}

		$treed_map = array();

		// tree it
		foreach ( $route as $unit ) {
			if ( $unit[ 'parent_unit_id' ] > 0 ) {
				$treed_map[ $unit[ 'parent_unit_id' ] ][ 'children' ][ $unit[ 'unit_id' ] ] = $unit;
			}
		}
		unset( $unit );

		$sorted_map = $this->sort_recursive( $treed_map );

		return $sorted_map;
	}

	public function sort_recursive( $route_or_map ) {
		$sorted = array();
		foreach ( $route_or_map as $key => $unit ) {
			$sorted[ $key ] = ( int ) $unit[ 'unit_order' ];
		}
		array_multisort( $sorted, SORT_ASC, SORT_NUMERIC, $route_or_map );

		if ( ! empty( $route_or_map[ 'children' ] ) ) {
			$route_or_map[ 'children' ] = $this->sort_recursive( $route_or_map[ 'children' ] );
		}

		return $route_or_map;
	}

	public function merge_recursive( $path, $journey ) {

		foreach ( $path as $unit_id => &$unit ) {
			$route = wp_parse_args( $unit, $this->default_step );
			$unit = wp_parse_args( $journey[ $unit_id ], $route );

			if ( empty( $unit[ 'children' ] ) ) {
				continue;
			}

			// to check whether the journey is a map
			$journey_step_children = $journey[ $unit_id ][ 'children' ];


			if ( ! isset( $journey_step_children ) || ! empty( $journey_step_children ) ) {
				$unit[ 'children' ] = $this->merge_recursive( $unit[ 'children' ], $journey );
				continue;
			}

			$unit[ 'children' ] = $this->merge_recursive( $unit[ 'children' ], $journey_step_children );
		}

		return $path;
	}

	public function order_recursive( $route_or_map, &$counter ) {
		foreach ( $route_or_map as &$unit ) {
			$unit[ 'unit_order' ] = $counter ++;
			if ( ! empty( $unit[ 'children' ] ) ) {
				$unit[ 'children' ] = $this->order_recursive( $unit[ 'children' ], &$counter );
			}
		}

		return $route_or_map;
	}

}
