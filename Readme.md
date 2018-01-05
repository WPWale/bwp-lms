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

 * `/bwp-lms.php`
 * `/includes/constants.php`
 * `/includes/autoloader.php`
 * `/includes/lms-loader.php`
     * `/app/schema/[attributes, data, content]/*.php`
 * `/app/class-load.php`
 * `/functions/functions.php`
 * `/app/core/class-router.php`
 * `/app/lms/class-load.php`
     * `/app/lms/class-path.php`
     * `/app/lms/class-user.php`
     * `/app/lms/class-journey.php`
     * `/app/lms/content-types/**.php`
         * `/app/data/**.php`
 * `/app/core/class-template.php`
     * `/app/templates/**.php`
        OR `TEMPLATE_PATH/**.php`


