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


