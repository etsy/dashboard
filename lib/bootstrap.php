<?php

ini_set('display_errors', true);

/**
 * Load any required class files. This can absolutely be replaced by
 * a PHP Auto Load routine (php.net/autoload). 
 */

require_once dirname(__FILE__) . '/Config.php';
require_once dirname(__FILE__) . '/Dashboard.php';
require_once dirname(__FILE__) . '/Graphite.php';
require_once dirname(__FILE__) . '/Ganglia.php';
require_once dirname(__FILE__) . '/Cacti.php';
require_once dirname(__FILE__) . '/Tsd.php';

