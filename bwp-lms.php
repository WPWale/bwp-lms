<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the
 * plugin admin area. This file also includes all of the dependencies used by
 * the plugin, registers the activation and deactivation functions, and defines
 * a function that starts the plugin.
 *
 * @since             1.0.0
 * @package           BWP_LMS
 *
 * @wordpress-plugin
 * 
 * Plugin Name: BaapWP LMS
 * Description: The LMS that BaapWP uses internally
 * Plugin URI: 
 * Version: 1.0.0
 * Author: Saurabh Shukla
 * Author URI: https://baapwp.me/
 * Text Domain: bwp-lms
 * Domain Path: /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'BWP_LMS\PATH' ) ) {
	/**
	 * Path to the plugin directory.
	 *
	 * @since 1.0.0
	 */
	define( 'BWP_LMS\PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'BWP_LMS\URL' ) ) {
	/**
	 * URL to the plugin directory.
	 *
	 * @since 1.0.0
	 */
	define( 'BWP_LMS\URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

if ( ! defined( 'BWP_LMS\VERSION' ) ) {
	/**
	 * Current Plugin Version.
	 *
	 * @since 1.0.0
	 */
	define( 'BWP_LMS\VERSION', '1.0.0' );
}

/**
 * The autoloader
 */
require_once \BWP_LMS\PATH . 'app/autoloader.php';

/**
 * Include template functions
 */
include_once \BWP_LMS\PATH . 'functions/functions.php';

// Instantiate the core class
$bwp = new \BWP_LMS\App\Core();
	
// initialise plugin
$bwp->init(__FILE__);