<?php

namespace BWP_LMS\App\Roles\Tasks\Questions;

trait Hotspot {

	public $hotspots;

	public function parse_stem() {

		$hotspots = array();

		$stem_as_array = explode( ' ', $this->stem );

		foreach ( $stem_as_array as $index => $word ) {
			if ( ! strstr( '{%', $word ) ) {
				continue;
			}
			$hotspots[ $index ] = array(
				'tag' => $word,
				'text' => str_replace( '%}', '', str_replace( '{%', '', $word ) ),
				'key' => false,
			);
		}
	}

}
