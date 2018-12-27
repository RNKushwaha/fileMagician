<?php 
/**
 * Handle the requests and serve the response for fileMagician
 *
 * @category  PHP
 * @package   Core
 * @author    RN Kushwaha <Rn.kushwaha022@gmail.com>
 * @copyright 2018 Cruzer Softwares
 * @version   GIT: 1.0.0
 */

ob_start();
if (!session_id()) {
    session_start();
}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT_DIR', dirname(__FILE__));
define('ROOT', basename(dirname(__FILE__)));
define('APP_DIR', ROOT_DIR.DS.'app');
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/'.ROOT.'/');
define('THEME_PATH', APP_DIR.DS.'Views'.DS.'Themes'.DS);
date_default_timezone_set('Asia/Calcutta');
ini_set('error_log', APP_DIR.DS.'errors.log');
/*set_exception_handler(function($exception) {
   error_log($exception);
});*/

define('ENVIRONMENT', 'development');

switch (ENVIRONMENT) {
    case 'development':
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        break;
    case 'testing':
        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('display_errors', 'On');
        break;
    case 'production':
        ini_set('display_errors', 'Off');
        break;
    default:
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        break;
}

require_once 'vendor/autoload.php';
require_once APP_DIR.DS."Routes".DS."routes.php";
$routes->serve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

