<?php

defined('LIB_PATH') || define('LIB_PATH', realpath(dirname(__FILE__)) . "/lib/");
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)) . "/application");
set_include_path(implode(PATH_SEPARATOR, array( realpath(APPLICATION_PATH . '/../lib'), get_include_path(), )));

require_once(LIB_PATH . "Loader.php");

FrontController::run();
?>