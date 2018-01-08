# BaapWP LMS (bwp-lms)

LMS that BaapWP intends to use internally.

## Plugin Architecture

 * `/bwp-lms.php` (main file)
 * `/includes` (includes)
 * `/app` (functionality)
 * `/functions` (api functions)
 * `/templates` (templates)

### /app

 * `/class-load.php` (loads the plugin)
 * `/assets` (static assets)
 * `/core` (extend WP core for url routing & theme compatibilty)
 * `/schema` (schema for date)
     * `/content` (schema for custom content types)
     * `/data` (schema for custom data tables)
     * `/attributes` (schema for attributes & properties)
 * `/data` (application data)
 * `/roles` (object roles as traits)
     * `/unit` (traits of lms units)
	 * `/journey` (traits of journey)
     * (other traits)
 * `/lms` (main lms functionality)
     * `/core` (core functionality)
     * `/course-objects` (course object controllers)
     * `/utilities` (utilities)
     * `/class-load.php` (loads LMS)

## Plugin Flow

### Course Experience

 * `/bwp-lms.php`
 * `/includes/constants.php` (define all constants)
 * `/includes/autoloader.php` (setup autoloader for classes, etc)
 * `/includes/lms-loader.php` (load functionality)
     * `/app/class-load.php` (load main functionality, install & register data & content)
         * `/app/schema/[attributes, data, content]/*.php` (load lms level attributes)
     * `/functions/functions.php` (load api function definitions)
     * `/app/core/class-router.php` (setup url rewriting)
     * `/app/lms/class-load.php` (load LMS)
         * `/app/lms/class-path.php` (setup path)
         * `/app/lms/class-user.php` (setup lms user)
         * `/app/lms/class-journey.php` (setup journey [progress])
         * `/app/lms/content-types/**.php` (setup content type)
             * `/app/data/**.php` (load necessary data)
     * `/app/core/class-template.php`(modify template heirarchy for lms content types)
         * `/app/templates/**.php`
            OR `TEMPLATE_PATH/**.php`(load templates either from theme or plugin itself)

## Content Architecture

The LMS thinks of a **course** as made up of two related parts instead of one:
 1. a **Path** (defined by a mentor, or administrator), and
 1. a **Journey** (by the learner, along the path)

Other LMSes consider both the path & journey as one thing. Unless that makes sense to you and you need them to be different things, you may not appreciate the benefit of using this LMS.

### Path

    ...................................................................................................................................
    |        ....................................................  .................................................................  |
    |        |         .......................................  |  |         ....................................................  |  |
    | Course | Module1 | Lesson1 | Lesson2 | Activity | Test |  |  | Module2 | Lesson1 | Activity1 | Lesson2 | Activity2 | Test |  |  |
    |        |         .......................................  |  |         ....................................................  |  |
    |        ....................................................  .................................................................  |
    ...................................................................................................................................

 * A *path*, just like the name suggests, is a series of steps that someone can use to go on a journey.
 * The individual steps in a path are *units* like lessons, exercises and tests. 
 * This steps can be organised into hierarchical *modules* with any amount of nesting.
 * The course creator, creates the path with modules and units.

### Journey

 * A *journey* is made up of a *route* and a *map*

Even though the current version doesn't do it yet, you can easily imagine how different learners (or other stakeholders) could be taking different journeys, using the same path.
You could possibly imagine how by inserting some sort of control flow mechanism, paths could become very dynamic and create a personalised journey for each user.

**WP Custom Post Type Content**

https://github.com/BaapWP/bwp-lms/tree/master/app/schema/attributes/content-types.php

```
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
```

