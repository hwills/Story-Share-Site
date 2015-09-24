<?php

$mysqli = new mysqli('localhost', 'phpuser', 'phpass', 'sqlphp');

if ($mysqli->connect_errno){
	printf("Connection to database failed: %s\n", $mysqli->connect_error);
	exit;
}

?>
