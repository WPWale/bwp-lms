<?php

/**
 * Contains template loading class
 */

namespace BWP_LMS\App\Core;

/**
 * Loads appropriate templates
 */
class Template {

	/**
	 * 
	 */
	public function init() {
		add_filter( "singular_template", array( $this, 'singular' ), 10, 1 );
		add_filter( "archive_template", array( $this, 'archive' ), 10, 1 );
	}

	/**
	 * 
	 * @param string $template Absolute file path of template
	 * @return string
	 */
	public function singular( $template ) {

		if ( ! is_singular( bwp_lms()->content_types[ 'all' ] ) ) {
			return $template;
		}

		global $lms_unit, $wp_query;

		$type = $lms_unit->type;

		if ( isset( $wp_query->query_vars[ 'dashboard' ] ) && is_singular( bwp_lms()->content_types[ 'paths' ] ) ) {
			$fallback = \BWP_LMS\TEMPLATE_PATH . "dashboard-{$type}.php";
			$template = locate_template( "dashboard-{$type}.php" );

			if ( ! $template ) {
				return $fallback;
			}
		}

		$template_name = "single-{$type}.php";

		if ( $this->theme_has_template( $template, $template_name ) ) {
			return $template;
		}

		return \BWP_LMS\TEMPLATE_PATH . $template_name;
	}

	/**
	 * 
	 * @global type $step
	 * @param type $template
	 * @return type
	 */
	public function archive( $template ) {

		if ( ! is_post_type_archive( bwp_lms()->content_types[ 'paths' ] ) ) {
			return $template;
		}

		global $lms_unit;

		$type = $lms_unit->type;

		$template_name = "archive-{$type}.php";

		if ( $this->theme_has_template( $template, $template_name ) ) {
			return $template;
		}

		return \BWP_LMS\TEMPLATE_PATH . $template_name;
	}

	/**
	 * 
	 * @param type $template_path
	 * @param type $template_name
	 * @return type
	 */
	private function theme_has_template( $template_path, $template_name ) {

		$len = strlen( $template_name );
		$path_end = substr( $template_path, strlen( $template_name ) - $len );

		return $path_end == $template_name;
	}

}
