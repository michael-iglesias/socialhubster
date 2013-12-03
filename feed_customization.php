<?php
session_start();

require_once('./lib/db.php');

$type = $db->real_escape_string($_POST['type']);
$account = $db->real_escape_string($_POST['account']);

$service_id = $db->real_escape_string($_SESSION['service_id']);
$serviceTitle = $db->real_escape_string($_POST['serviceTitle']);
$fontColor = $db->real_escape_string($_POST['fontColor']);
$titleFontColor = $db->real_escape_string($_POST['titleFontColor']);
$backgroundColor = $db->real_escape_string($_POST['backgroundColor']);
$tileBackgroundColor = $db->real_escape_string($_POST['tileBackgroundColor']);
$contentBackgroundColor = $db->real_escape_string($_POST['contentBackgroundColor']);
$dottedBorderColor = $db->real_escape_string($_POST['dottedBorderColor']);
$contentBorderColor = $db->real_escape_string($_POST['contentBorderColor']);


$stmt = $db->prepare("UPDATE service SET service_title=?, font_color=?, title_font_color=?, background_color=?, tile_background_color=?, content_background_color=?, dotted_border_color=?, content_border_color=? WHERE service_id=?");
$stmt->bind_param('ssssssssi', $serviceTitle, $fontColor, $titleFontColor, $backgroundColor, $tileBackgroundColor, $contentBackgroundColor, $dottedBorderColor, $contentBorderColor, $service_id);


if($stmt->execute()) {
	echo 1;
} else {
	echo 0;
}