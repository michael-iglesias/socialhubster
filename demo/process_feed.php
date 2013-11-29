<?php
session_start();

require_once('./lib/db.php');

$type = $db->real_escape_string($_POST['type']);
$account = $db->real_escape_string($_POST['account']);

$stmt = $db->prepare("INSERT INTO feed (service_id, type, account) VALUES (?, ?, ?)");
$stmt->bind_param('iss', $_SESSION['service_id'], $type, $account);



if($stmt->execute()) {
	echo 1;
} else {
	echo 0;
}