<?php

function bwp_lms_progress_bar(){
	global $lms;
	$html = '<aside class="bwp-lms-progress-bar">';
	$html .= $lms->journey->html(false);
	$html .= '</aside>';
	
	echo $html;
	
}

function bwp_lms_map_nav(){
	global $lms;
	$html = '<nav class="bwp-lms-path-nav">';
	$html .= $lms->journey->html(false);
	$html .= '</nav>';
	
	echo $html;
}

function bwp_lms_unit_nav(){
	global $lms;
	
	$lms->journey->unit_nav();
	
}

function bwp_lms_unit_map(){
	
}

