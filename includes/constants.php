<?php
/**
 * URL to the plugin directory.
 *
 * @since 1.0.0
 */
define( 'BWP_LMS\URL', trailingslashit( plugin_dir_url( $file ) ) );


/**
 * Current Plugin Version.
 *
 * @since 1.0.0
 */
define( 'BWP_LMS\VERSION', '1.0.0' );

if ( ! defined( 'BWP_LMS\SCHEMA_PATH' ) ) {
	/**
	 * Path to schema files
	 */
	define( 'BWP_LMS\SCHEMA_PATH', \BWP_LMS\PATH . 'app/schema/' );
}

if ( ! defined( 'BWP_LMS\TEMPLATE_PATH' ) ) {
	/**
	 * Path to templates
	 */
	define( 'BWP_LMS\TEMPLATE_PATH', \BWP_LMS\PATH . 'templates/' );
}

/**
 * Prefix to use in keys and other identifiers
 */
define( 'BWP_LMS\PREFIX', 'bwp-lms-' );

/**
 * Underscored prefix to use in identifiers
 */
define( 'BWP_LMS\_PREFIX', 'bwp_lms_' );

global $wpdb;

/**
 * Prefix for plugin's tables
 */
define( 'BWP_LMS\TABLE_PREFIX', $wpdb->prefix . \BWP_LMS\_PREFIX );
