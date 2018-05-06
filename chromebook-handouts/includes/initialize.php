<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null :
    //define('SITE_ROOT', DS.'home'.DS.'jimmahpr'.DS.'cnctid.io'.DS);
    define('SITE_ROOT', DS.'var'.DS.'www');

defined('LIB_PATH') ? null :
    define('LIB_PATH', SITE_ROOT.DS.'includes');

require_once(LIB_PATH.DS."config.php");

require_once(LIB_PATH.DS."functions.php");

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."database.php");

?>
