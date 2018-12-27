<?php
// @ini_set('open_basedir', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
error_reporting(E_ALL | E_STRICT);
ini_set('error_log', 'errors.log');
define('ROOT',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
date_default_timezone_set('Asia/Kolkata');

if( !defined('_ACCESS_OK') ){
  header("HTTP/1.0 404 Not Found");
  die('Page not found!');
}

if(!session_id()){
	session_start();
}

if(!isset($_SESSION['auth'])){
	header('Location: login.php');
	exit();
}

require_once 'vendor/autoload.php';