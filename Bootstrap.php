<?php

require_once('AutoLoader.php');
// Register the directory to the include files
$GLOBALS['appRoot'] = dirname(__FILE__) . '/';
AutoLoader::registerDirectory($GLOBALS['appRoot'] . 'Models');
