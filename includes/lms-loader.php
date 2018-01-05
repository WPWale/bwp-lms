<?php
/**
 * Returns Singleton Instance of Application
 * 
 * @return object
 */
function bwp_lms() {
	return \BWP_LMS\App\Load::get_instance();
}

// load plugin
bwp_lms()->init( $file );

/**
 * Include template functions
 */
include_once \BWP_LMS\PATH . 'functions/functions.php';

use BWP_LMS\App\Core as Core;
use BWP_LMS\App\LMS as LMS;

// set up custom routes using Rewrite API
$route = new Core\Route();
$route->init();

// load LMS Functionality
$lms = new LMS\Load();
$lms->init();

// load template system
$template = new Core\Template();
$template->init();

unset($route,$lms,$template);