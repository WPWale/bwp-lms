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
	'units' => array( 'lesson', 'test', 'activity' ),
	
	/* Start thinking of learning objectives
	 * 
	 * Start thinking about tests and activities as made up of tasks
	 * Think of tasks as pass/fail or just complete
	 * 1 task = 1 or many objectives
	 * 
	 * Tasks can have scores, or achievements (or badges) associated with them 
	 * 
	 * Even units can have scores, achievements (or badges) associated with them
	 *  this is because completing a unit is a default task
	 *  
	 * 
	 * Tasks can be of different types defined into namespaces like blocks
	 * Think of internal types as tasks and questions
	 * 
	 * All tasks (in a unit) can be completed automatically
	 *  without any explicit user action,
	 *  when they navigate to the next course element
	 * 
	 * Tasks can be completed explicitly by interacting with a UI (wp block)
	 * Tasks can be completed via a code API
	 * Tasks can be completed via a webhook
	 * 
	 * Multiple tasks can be added within any unit.
	 * 
	 * Similar behaviour for objectives except
	 *  an objective can exist directly on a unit
	 *  without a corresponding task
	 * 
	 * So progress on a path involves completing any tasks added in a unit,
	 * 
	 * When all the tasks (or questions) that require explicit completion are completed,
	 *  the unit (lesson, activity, test) is completed,
	 *	when a unit is completed,
	 *	 remaining (non-explicit) tasks get completed,
	 *	 all the objectives associated with the unit get completed,
	 * When all the units in a module are completed,
	 *  the module is completed,
	 * When all submodules of a module are completed,
	 *  the module is completed,
	 * When all the modules on a path are completed,
	 *  the path is completed.
	 * 
	 */
);