<?php
include_once('autoload/autoload.php');
    session_start();
	$route = new App();
	$route->run();

?>