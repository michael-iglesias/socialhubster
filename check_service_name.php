<?php
session_start();

require_once('./lib/db.php');

$serviceName = $db->real_escape_string($_POST['serviceName']);

$query = "SELECT * FROM service LEFT JOIN feed ON service.service_id = feed.service_id WHERE service.service_name='$serviceName'";

if ($result = $db->query($query)) {
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$rows[] = $row;
	}
    /* free result set */
    $result->close();
}