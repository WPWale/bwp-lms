<?php

return $content_types = array(
	/* the pathways, the default is course
	 * but could be a workbook, or something else
	 */

	'paths' => array( 'course' ),
	/* the modules that are used to organise units
	 *  there could be chapters, sections, etc 
	 */
	'modules' => array( 'module', 'activity' ),
	/* the units that contain the learning content
	 *  default is lesson and test but could be notes, exercises, etc
	 */
	'units' => array( 'lesson', 'test', 'task' ),
);