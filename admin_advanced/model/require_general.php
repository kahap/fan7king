<?php
function __autoload($ClassName){
	include_once('cls/'.$ClassName.".cls.php");
}



require_once('cfg/cfg.inc.php');

require_once('lib/function.php');

?>