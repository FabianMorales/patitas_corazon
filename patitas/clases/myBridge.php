<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(dirname(__DIR__)));

require_once (JPATH_BASE.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php' );
require_once (JPATH_BASE.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'framework.php' );
require_once (JPATH_ROOT.DIRECTORY_SEPARATOR."myCore".DIRECTORY_SEPARATOR."autoload.php");

$mainframe = JFactory::getApplication('site');
$mainframe->initialise();

?>