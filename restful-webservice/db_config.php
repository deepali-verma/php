<?php
	define ('DB_USER', "root");
	define ('DB_PASSWORD', "toor");
	define ('DB_DATABASE', "rest_api");
	define ('DB_HOST', "localhost");
	GLOBAL $mysqli;
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);