<?php
	$conn = mysqli_connect('localhost', 'root', '', 'smc2') or die(mysqli_error());
	
	if(!$conn){
		die("Error: Failed to connect to database");
	}
?>