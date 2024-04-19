<?php
	$host = '127.0.0.1';
	$user = 'root';
	$password = '';
	$database = 'login';

	$results = array();
	$results['error'] = false;
	$results['message'] = "";

	$connection = mysqli_connect($host, $user, $password, $database);

	if (!$connection) {
		$results['error'] = true;
		$results['message'] = "Error: no connection to database!";
	} else {
		$results['error'] = false;
		$results['message'] = "Successfully connected!";
	};
?>