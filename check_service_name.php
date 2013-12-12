<?php
session_start();

require_once('./lib/db.php');

$serviceName = $db->real_escape_string($_POST['serviceName']);

$query = "SELECT * FROM service WHERE service_name='$serviceName'";

if ($result = $db->query($query)) {
	$row_count = $result->num_rows;
	
	if($row_count > 0) {
		echo 1;
	} else {
		echo 2
	}
}