<?php
session_start();

require_once('./lib/db.php');


$serviceTitle = $db->real_escape_string($_POST['serviceTitle']);
$serviceName = $db->real_escape_string($_POST['serviceName']);
$serviceEmail = $db->real_escape_string($_POST['serviceEmail']);
$servicePassword = hash( 'sha384', $db->real_escape_string($_POST['servicePassword']) );


$stmt = $db->prepare("INSERT INTO service (service_title, service_name, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $serviceTitle, $serviceName, $serviceEmail, $servicePassword);



if($stmt->execute()) {
	$_SESSION['service_id'] = $stmt->insert_id;
	echo $stmt->insert_id;
} else {
	echo 0;
}

?>