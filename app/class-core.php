<?php

/**
 * Contains Core Class
 */

namespace BWP_LMS\App;

/*
 * Basically turn modules into heirarchical post types
 * The queries would be simpler and everything becomes easier 
 * 	when you use parent child relationships between units and modules
 */

/**
 * Loads the LMS's core functionality
 */
class Core {

	/**
	 * Content Types for the LMS
	 * 
	 * @var array 
	 */
	private $content_types = array(
		/* the pathways, the default is course
		 * but could be a workbook, or something else
		 */
		'paths' => array( 'course' ),
		/* the modules that are used to organise units
		 *  there could be chapters, sections, etc 
		 */
		'modules' => array( 'module' ),
		/* the units that contain the learning content
		 *  default is lesson and test but could be notes, exercises, etc
		 */
		'units' => array( 'lesson', 'test' ),
	);

	/**
	 * Initialises the content types
	 */
	public function __construct() {
		/**
		 * 
		 */
		$this->content_types[ 'paths' ] = apply_filters( 'bwp_lms_path_types', $this->content_types[ 'path' ] );

		/**
		 * 
		 */
		$this->content_types[ 'modules' ] = apply_filters( 'bwp_lms_module_types', $this->content_types[ 'module' ] );

		/**
		 * 
		 */
		$this->content_types[ 'units' ] = apply_filters( 'bwp_lms_unit_types', $this->content_types[ 'unit' ] );

		/**
		 * 
		 */
		$this->content_types = apply_filters( 'bwp_lms_unit_types', $this->content_types );

		$this->content_types[ 'all' ] = $this->content_types[ 'paths' ] + $this->content_types[ 'modules' ] + $this->content_types[ 'units' ];
	}

	/**
	 * Initialise core
	 * 
	 * @param string $plugin_file The main plugin file
	 */
	public function init( $plugin_file ) {

		// install tables on activation
		register_activation_hook( $plugin_file, array( $this, 'install' ) );

		// hook into init for single site, priority 0 = highest priority
		add_action( 'init', array( $this, 'init_lms' ), 0 );

		// set up custom routes using Rewrite API
		$route = new \BWP_LMS\App\Core\Route( $this->content_types );

		// initialise routes
		$route->init();
	}

	/**
	 * 
	 */
	public function init_lms() {
		$this->register();

		global $lms_path;
		
		foreach($this->content_types['paths'] as $path){
			$lms_path[$path] = new \BWP_LMS\App\Core\Path( $path, $this->content_types );
		}
	}

	/**
	 * 
	 */
	public function register() {
		$this->post_types();
		$this->taxonomies();
		$this->meta();
	}

	/**
	 * 
	 * @global type $wpdb
	 */
	public function install() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$prefix = $wpdb->prefix . 'bwp_lms_';

		$table_name = $prefix . 'path_map';
		$install_queries[ 'path-map' ] = include_once(self::get_schema_path( 'path_map' ));


		$table_name = $prefix . 'user_path';
		$install_queries[ 'user-path' ] = include_once(self::get_schema_path( 'user_path' ));

		foreach ( $this->content_types[ 'all' ] as $type ) {
			$table_name = $prefix . $type . 'meta';
			$install_queries[ $type ] = include(self::get_schema_path( 'meta' ));
		}
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		foreach ( $install_queries as $install_query ) {
			dbDelta( $install_query );
		}

		flush_rewrite_rules();
	}

	/**
	 * 
	 */
	private function post_types() {

		$post_types = include_once(self::get_schema_path( __FUNCTION__ ));

		foreach ( $post_types as $post_type => $args ) {
			register_post_type( $post_type, $args );
		}
	}

	/**
	 * 
	 */
	private function taxonomies() {

		$taxonomies = include_once(self::get_schema_path( __FUNCTION__ ));

		foreach ( $taxonomies as $taxonomy => $args ) {
			register_taxonomy( $taxonomy, $this->content_types[ 'post_types' ], $args );
		}
	}

	/**
	 * 
	 */
	private function meta() {

		global $wpdb;

		$prefix = $wpdb->prefix . 'bwp-lms-';

		foreach ( $this->content_types[ 'types' ] as $type ) {
			$typename = $type . 'meta';

			$wpdb->$typename = $prefix . $typename;
			$wpdb->tables[] = $typename;
		}

		return;
	}

	/*
	 * Utilities 
	 */

	/**
	 * 
	 * @param type $method_name
	 */
	public static function get_schema_path( $schema_name ) {
		$schema_file_name = preg_replace( '/_/i', '-', $schema_name );
		$schema_file_path = \BWP_LMS\PATH . "app/schema/$schema_file_name.php";
		return $schema_file_path;
	}

}
