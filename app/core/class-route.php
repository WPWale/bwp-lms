<?php

/**
 * Contains routing class
 */
namespace BWP_LMS\App\Core;

/**
 * Routes LMS content
 */
class Route {
	
	/**
	 * LMS content types
	 * 
	 * @var array 
	 */
	private $types;

	public function __construct( $types ) {
		$this->types = $types;
	}

	/**
	 * Initialise
	 */
	function init() {

		// rewrite
		add_action( 'init', array( $this, 'rewrite' ) );

		// modify query before WP does it
		add_filter( 'query_vars', array( $this, 'lms_vars' ) );

		// modify query after WP does it
		add_filter( 'request', array( $this, 'setup_post_vars' ) );

		// modify default permalinks
		add_filter( 'post_type_link', array( $this, 'permalink' ), 10, 4 );
	}

	/**
	 * Rewrite the urls for LMS content
	 * 
	 * The LMS urls would be like
	 *  * Courses /course/course-name/
	 *  * Modules /course/course-name/module/module-name/child-module-name
	 *  * Units /course/course-name/lesson/lesson-name
	 */
	function rewrite() {

		// for each path (course)
		foreach ( $this->types[ 'paths' ] as $path ) {
			
			// Permalink structure course/%coursename%
			$path_structure = "{$path}/%{$path}name%";

			// add permalink structure to wp rewrite rules
			add_permastruct( $path, $path_structure, false );
			
			// add the regex to match %coursename% & placing it in ?coursename=
			add_rewrite_tag( "%{$path}name%", '([^/]+)', "{$path}name=" );
			
			// create rewrite rules for modules
			$this->rewrite_modules( $path_structure );
			
			// create rewrite rules for units
			$this->rewrite_units( $path_structure );
		}
	}

	/**
	 * Rewrite module urls
	 * 
	 * @param string $path_structure The path permalink structure
	 */
	function rewrite_modules( $path_structure ) {
		
		// for each module
		foreach ( $this->types[ 'modules' ] as $module ) {
			
			// permalink structure /course/%coursename%/module/%modulename%/
			$module_structure = $path_structure . "/{$module}/%{$module}name%";

			// add permalink structure to wp rewrite
			add_permastruct( $module, $module_structure, false );
			
			// add regex to match %modulename% & placing it in ?modulename=
			add_rewrite_tag( "%{$module}name%", '(.+?)', "{$module}name=" );
		}
	}

	/**
	 * Rewrire unit urls
	 * 
	 * @param type $path_structure
	 */
	function rewrite_units( $path_structure ) {
		
		// for each module
		foreach ( $this->types[ 'units' ] as $unit ) {

			// permalink structure /course/%coursename%/lesson/%lessonname%
			$unit_structure = $path_structure . "/{$unit}/%{$unit}name%";

			// add permalink structure to wp rewrite
			add_permastruct( $unit, $unit_structure, false );
			
			// add regex to match %unitname% & placing it in ?unitname=
			add_rewrite_tag( "%{$unit}name%", '([^/]+)', "{$unit}name=" );
		}
	}

	/**
	 * Adds LMS content type keys to the default query_vars
	 * 
	 * This way, WordPress (class WP) recognises them when matching the url against
	 *  regexes and rules registered earlier
	 * 
	 * @param array $query_vars
	 * @return array
	 */
	function lms_vars( $query_vars ) {

		foreach ( $this->types[ 'all' ] as $type ) {
			
			// just add our variable to the default query_vars keys
			array_push( $query_vars, "{$type}name" );
		}

		return $query_vars;
	}

	/**
	 * Sets up WP post vars from LMS rules for WP_Query
	 * 
	 * @param array $query_vars
	 * @return array
	 */
	function setup_post_vars( $query_vars ) {

		// for each type
		foreach ( $this->types[ 'all' ] as $type ) {

			// if the type is not in the query_vars
			if ( ! isset( $query_vars[ "{$type}name" ] ) ) {

				// move on to the next type
				continue;
			}

			/* otherwise, type is set in query vars
			 * set it up as the current post type
			 * since all LMS types are actually post types
			 */
			$query_vars[ 'post_type' ] = $type;

			// if not a module, their is no heirarchy
			if ( ! in_array( $type, $this->type[ 'modules' ] ) ) {

				// the type slug is the post name for wp_query
				$query_vars[ 'name' ] = $query_vars[ "{$type}name" ];
				continue;
			}

			// else it's a module which can be heirachical
			// explode into an array and reverse to get the last part (the current module)

			$module_tree = array_reverse( explode( '/', $query_vars[ "{$type}name" ] ) );

			$query_vars[ 'name' ] = $module_tree[ 0 ];
		}

		/* now even with our custom URLS, WP_Query will be setup correctly
		 * which means that the proper theme templates will be used
		 *  and the correct $post will be setup
		 */
		return $query_vars;
	}

	/**
	 * Filters the url to create a nice looking permalink
	 * 
	 * Converts http://site.com/%listid%/%listtitle%/ to
	 * 
	 * http://site.com/1234/post-title
	 * 
	 * @param string $permalink The original permalink
	 * @param object $post The current custom post
	 * @return string
	 */
	/**
	 * Filter the permalinks for LMS content types
	 * 
	 * @global string $coursename The current coursename
	 * @global string $typename The current typename
	 * @param string $permalink The original WP generated permalink
	 * @param object $post The current post
	 * @return string
	 */
	function permalink( $permalink, $post ) {

		// keep wp generated permalink for other post types and unpublished statuses
		if ( ( ! in_array( $post->post_type, $this->types )) || '' === $permalink || in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft' ) ) ) {
			return $permalink;
		}

		// WP_Query globalises all query vars including the ones that we added
		global $coursename;

		/*
		 * When on a module or a unit, since modules and therefore units
		 *  can be shared between courses, we need the current course in the permalink
		 * 
		 * If it isn't set, then we're viewing a module/unit outside a course
		 * 
		 * The default link according to rules we've added would be
		 *  * /course/%coursename%
		 *  * /course/%coursename%/module/%modulename%
		 *  * /course/%coursename%/lesson/%lessonname%
		 */
		if ( isset( $coursename ) ) {

			// for each of our types
			foreach ( $this->types[ 'all' ] as $type ) {
				
				// foreg, $modulename
				$typename = "{$type}name";
				
				// get it from global
				global ${$typename};
				
				// if say, the $modulename is not set, we set it to current post's name 
				if ( ! isset( ${$typename} ) ) {
					${$typename} = $post->post_name;
				}
				
				// the registered rewrite placeholder
				$rewritecode[] = "%{$typename}%";
				
				// what the placeholder will be replaced with
				$rewritereplace[] = ${$typename};
				
			}

			unset( $type );

			// the actual replacement
			$permalink = str_replace( $rewritecode, $rewritereplace, $permalink );

			return $permalink;
		}
		
		// fall back to ugly permalink
		return $this->ugly_permalink($post);

	}
	
	/**
	 * Generate an ugly permalink
	 * 
	 * @param object $post Current post object
	 * @return string
	 */
	function ugly_permalink($post){
		/* if there is no gloabl course, we're outside a course,
		 *  so our custom rules wouldn't work.
		 * However, we still want to be able to open the unit/module/etc
		 * So, we create an ugly permalink like
		 *  * ?post_type=module&p=23
		 *  * ?post_type=lesson&p=24
		 * etc
		 */
		$ugly_args = add_query_arg( array( 'post_type' => $post->post_type, 'p' => $post->ID ), '' );
		
		$ugly_link = home_url( $ugly_args );
		
		return $ugly_link;
	}

}
