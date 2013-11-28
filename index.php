<?php

include_once('autoloader.php');


$News = new RSS();


$rss_url = 'https://www.facebook.com/feeds/page.php?id=7901103&format=rss20';
echo $rss_url; die();
$items = $News->get_feed($rss_url);


var_dump($items);
?>