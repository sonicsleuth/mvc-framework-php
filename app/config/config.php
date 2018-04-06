<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');
/*
* --------------------------------------------------------------------------
* Base Site URL - SSL HTTPS Management
* --------------------------------------------------------------------------
* URL to your MVC root. Typically this will be your base URL,
* WITH a trailing slash:
*
*	http://example.com/
*
* If this is not set then the we will guess the protocol, domain and
* path to your installation.
*
* The following method does better at managing HTTP and HTTPS protocol changes.
* However... if you have problems with $_SERVER['HTTPS'], especially if it returns
* no values at all you should check the results of phpinfo(). It might not be listed
* at all. Here is a solution to check and change, if necessary, to ssl/https that
* will work in all cases: if($_SERVER['SERVER_PORT']!=443) then set "https://" protocol.
*/
$config['base_url'] = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

/*
* --------------------------------------------------------------------------
* URI PROTOCOL
* --------------------------------------------------------------------------
* This item determines which server global should be used to retrieve the
* URI string.  The default setting of 'AUTO' works for most servers.
* If your links do not seem to work, try one of the other delicious flavors:
*
* 'DEFAULT'			Used the $_GET['url']
* 'PATH_INFO'		Uses the $_SERVER['PATH_INFO']
* 'QUERY_STRING'	Uses the $_SERVER['QUERY_STRING']
* 'REQUEST_URI'		Uses the $_SERVER['REQUEST_URI']
*/
$config['uri_protocol'] = 'DEFAULT';

/*
* --------------------------------------------------------------------------
* Default Character Set
* --------------------------------------------------------------------------
* This determines which character set is used by default in various methods
* that require a character set to be provided.
*/
$config['charset'] = 'UTF-8';

/*
* --------------------------------------------------------------------------
* Default Controller
* --------------------------------------------------------------------------
* Sets the default Controller to call when no controller is found in the URL.
*/
$config['default_controller'] = 'Home';

/*
* --------------------------------------------------------------------------
* Default Method
* --------------------------------------------------------------------------
* Sets the default Method to call when no Method is found in the URL.
*/
$config['default_method'] = 'index';

