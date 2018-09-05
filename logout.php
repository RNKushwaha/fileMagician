<?php define('_ACCESS_OK', true);
require_once 'init.php';

session_destroy();

header('Location: login.php');
exit();