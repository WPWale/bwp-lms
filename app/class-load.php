<?php
/**
 * Contains Core Class
 */
namespace BWP_LMS\App;

/**
 * Loads the LMS's core functionality
 */
class Load {

	/**
	 * Refers to a single instance of this class.
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return Load A single instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Content Types for the LMS
	 * 
	 * @var array 
	 */
	public $content_types = array();
	
	/**
	 * Custom Table Names
	 * 
	 * @var array
	 */
	public $custom_table_names = array(
		'pathways',
		'journeys',
		'path_roles'
	);
	
	/**
	 * Progress Stati (statuses?)
	 * @var array
	 */
	public $stati;

	/**
	 * Initialises the content types
	 */
	private function __construct() {
		
		$attributes_path = \BWP_LMS\SCHEMA_PATH . 'attributes/';
		
		$content_types = include_once $attributes_path . 'content-types.php';
		/**
		 * 
		 */
		$this->content_types = apply_filters( \BWP_LMS\_PREFIX . 'content_types', $content_types );

		$this->content_types[ 'all' ] = $this->content_types[ 'paths' ] 
			+ $this->content_types[ 'modules' ] 
			+ $this->content_types[ 'units' ];

		$stati = include_once $attributes_path . 'stati.php';
		
		/**
		 * 
		 */
		$this->stati = apply_filters( \BWP_LMS\_PREFIX . 'stati', $stati );
		
		$roles = include_once $attributes_path . 'roles.php';
		
		/**
		 * 
		 */
		$this->roles = apply_filters( \BWP_LMS\_PREFIX. 'roles', $roles );
	}

	/**
	 * Register Data & Content Types
	 * 
	 * @param string $plugin_file The main plugin file
	 */
	public function init( $plugin_file ) {

		// install tables on activation
		register_activation_hook( $plugin_file, array( $this, 'install' ) );

		// hook into init for single site, priority 0 = highest priority
		add_action( 'init', array( $this, 'register' ), 0 );

	}

	/**
	 * Registers Content Types
	 */
	public function register() {
		$this->post_types();
		$this->taxonomies();
		$this->meta();
	}

	/**
	 * Install Custom Data Tables
	 * 
	 * @global \WPDB $wpdb
	 */
	public function install() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$install_queries = array();

		foreach ( $this->custom_table_names as $tablename ) {
			$table_name = \BWP_LMS\TABLE_PREFIX . $tablename;
			$install_queries[ $tablename ] = include_once(self::get_schema_path( $tablename ));
		}

		foreach ( $this->content_types[ 'all' ] as $type ) {
			$table_name = \BWP_LMS\TABLE_PREFIX . $type . 'meta';
			$install_queries[ $type ] = include(self::get_schema_path( 'meta' ));
		}
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		foreach ( $install_queries as $install_query ) {
			dbDelta( $install_query );
		}

		flush_rewrite_rules();
	}

	/**
	 * Registers Post Types from Schema
	 */
	private function post_types() {

		$post_types = include_once(self::get_schema_path( __FUNCTION__, 'content' ));

		foreach ( $post_types as $post_type => $args ) {
			register_post_type( $post_type, $args );
		}
	}

	/**
	 * Registers Taxonomies from Schema
	 */
	private function taxonomies() {

		$taxonomies = include_once(self::get_schema_path( __FUNCTION__, 'content' ));

		foreach ( $taxonomies as $taxonomy => $args ) {
			register_taxonomy( $taxonomy, $this->content_types[ 'post_types' ], $args );
		}
	}

	/**
	 * Registers Meta Tables with $wpdb
	 * 
	 * @global \WPDB $wpdb
	 */
	private function meta() {

		global $wpdb;

		foreach ( $this->content_types[ 'all' ] as $type ) {
			$typename = $type . 'meta';

			$wpdb->$typename = \BWP_LMS\TABLE_PREFIX . $typename;
			$wpdb->tables[] = $typename;
		}
	}

	
	/**
	 * Gets path to schema file from schema name
	 * 
	 * @param string $schema_name
	 */
	public static function get_schema_path( $schema_name, $prefix = 'data' ) {
		$schema_file_name = preg_replace( '/_/i', '-', $schema_name );
		$schema_file_path = \BWP_LMS\SCHEMA_PATH . "/$prefix/$schema_file_name.php";
		return $schema_file_path;
	}
}