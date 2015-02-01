<?php

	include_once 'Locator.php';

	if(empty($_REQUEST['request_token']) || $_REQUEST['request_token'] != Config::REQUEST_TOKEN) {
		header('HTTP/1.1 401 Unauthorized', true, 401);
		die();
	}

	if(empty($_REQUEST['username'])) {
		header('HTTP/1.1 400 Bad Request', true, 400);
		die();
	} else {
		$username = $_REQUEST['username'];
	}

	if(empty($_REQUEST['location']))
		$location = null;
	else
		$location = $_REQUEST['location'];

	try {

		$carLocator = new Locator($username);

		if($location != null)
			$carLocator->setLocation($location);
		else
			$carLocator->retrieveLocation();

	} catch (Exception $e) {
			
		echo $e->getMessage();

	}

?>