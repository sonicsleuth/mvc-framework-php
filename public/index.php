<?php
/*
 * ---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 * ---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
define('ENVIRONMENT', 'development');

/*
 * ---------------------------------------------------------------
 * DEFAULT TIME ZONE
 * ---------------------------------------------------------------
 */
date_default_timezone_set('America/New_York');

/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined('ENVIRONMENT'))
{
    switch (ENVIRONMENT)
    {
        case 'development':
            error_reporting(E_ALL);
            break;

        case 'testing':
        case 'production':
            error_reporting(0);
            break;

        default:
            exit('The application environment is not set correctly.');
    }
}

/*
 * ---------------------------------------------------------------
 * MVC FOLDER NAMES
 * ---------------------------------------------------------------
 *
 * These variables must contain the names of your MVC folders.
 * Include the path if the folder is not in the same directory
 * as this file. Include a trailing forward slash "/".
 *
 */
$web_root           = '/';                      // where public files are served from relative to the web site.
$base_path          = __DIR__;                  // where public files are served from relative to the server root.
$application_path   = '../app/';                // default: '../app/'
$controllers_path   = '../app/controllers/';    // default: '../app/controllers/'
$models_path        = '../app/models/';         // default: '../app/models/'
$views_path         = '../app/views/';          // default: '../app/models/'
$helpers_path       = '../app/helpers/';        // default: '../app/helpers/'



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------


/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Paths to the system folders
define('WEB_ROOT', $web_root);
define('BASE_PATH', str_replace("\\", "/", $base_path));
define('APPLICATION_PATH', $application_path);
define('CONTROLLERS_PATH', $controllers_path);
define('MODELS_PATH', $models_path);
define('VIEWS_PATH', $views_path);
define('HELPERS_PATH', $helpers_path);

// Build front-loader
require_once '../app/config/config.php';
require_once '../app/config/database.php';
require_once '../app/config/routes.php';
require_once '../app/init.php';
$app = new App($config, $route);


