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

// assign current filename to variable to use in activation hook
$file = __FILE__;

/**
 * Path to the plugin directory.
 *
 * @since 1.0.0
 */
define( 'BWP_LMS\PATH', trailingslashit( plugin_dir_path( $file ) ) );

/**
 * Other application constants
 */
require_once \BWP_LMS\PATH . 'includes/constants.php';

/**
 * The autoloader
 */
require_once \BWP_LMS\PATH . 'includes/autoloader.php';

/**
 * The application core
 */
require_once \BWP_LMS\PATH . 'includes/lms-loader.php';

unset($file);