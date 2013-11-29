<?php
session_start();
// Start counting time for the page load
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

// Include SimplePie
// Located in the parent directory
require_once('autoloader.php');
require_once('idn/idna_convert.class.php');
require_once('lib/twitteroauth.php');
require_once('config.php');
require_once('lib/rss.php');
require_once('lib/db.php');

// Create a new instance of the SimplePie object
$feed = new SimplePie();

// ****************************************************************************************************
// Initialize Twitter API
/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, '462452177-B0KI4oy4tjw3H0dYk28tE3T6HDfAr9EpZtAdViqb', 'CbyZ9FQJV2UAnanJk8n4yx9FNNCNije3kCxQYP1Ow4R1w');
/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');
// ****************************************************************************************************

// Initialize the whole SimplePie object.  Read the feed, process it, parse it, cache it, and
// all that other good stuff.  The feed's information will not be available to SimplePie before
// this is called.
$success = $feed->init();

// We'll make sure that the right content type and character encoding gets set automatically.
// This function will grab the proper character encoding, as well as set the content type to text/html.
$feed->handle_content_type();

// When we end our PHP block, we want to make sure our DOCTYPE is on the top line to make
// sure that the browser snaps into Standards Mode.

$service = $db->real_escape_string($_GET['service']);

/* retrieve all rows from myCity */
$query = "SELECT * FROM service LEFT JOIN feed ON service.service_id = feed.service_id WHERE service.service_name='$service'";

if ($result = $db->query($query)) {
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$rows[] = $row;
	}
    /* free result set */
    $result->close();
}




$f1 = new RSS();
$sorted_items = array();
$sorted_items2 = array();

function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    arsort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}


foreach($rows as $r) {
	
	if($r['type'] == 'twitter') {
		$twitter_content = $connection->get('statuses/user_timeline', array('screen_name' => $r['account']));
		
		foreach($twitter_content as $row) {
			$post = array();
			$post['author'] = $row->user;		
			$post['title'] = '@' . $post['author']->screen_name;
			$post['time'] = (int) strtotime( $row->created_at );
			$post['type'] = 'twitter';
			$post['text'] = $row->text;
			$post['author_link'] = NULL;
			$post['video_thumbnail'] == NULL;
			if( $post['time'] > (time() - 20*24*60*60) ) {
				array_push($sorted_items, $post);
				aasort($sorted_items,"time");
			}
		}
	} // ***END if{twitter}
	if($r['type'] == 'facebook') {
		$rss_url = 'https://www.facebook.com/feeds/page.php?id=' . $r['account'] . '&format=rss20';
		
		$i = $f1->get_feed($rss_url);
		
		foreach($i as $k => $v) {
			$post = array();
			$post['title'] = $v["title"];
			$post['time'] = (int) $v['unixtime'];
			$post['link'] = $v['link'];
			$post['text'] = $v['text'];
			$post['type'] = $v['type'];
			
			foreach($v as $key => $value) {
				if($key == 'author') {
					$post['author'] = $value->email;
					$post['author_link'] = NULL;
					$post['video_thumbnail'] == NULL;
				}
			}
			
			if( $post['time'] > (time() - 20*24*60*60) ) {
				array_push($sorted_items, $post);
				aasort($sorted_items,"time");
			}
		}
	} // ***END if{facebook}
	if($r['type'] == 'youtube') {
		$rss_url = 'http://gdata.youtube.com/feeds/base/users/' . $r['account'] . '/uploads?orderby=published&max-results=10';
		$i = $f1->get_feed($rss_url);
		
		foreach($i as $row) {
			$post = array();
			foreach($row as $k => $v) {
				if($k == 'title') {
					$post['title'] = $v;
				}
				if($k == 'type') {
					$post['type'] = $v;
				}			
				if($k == 'unixtime') {
					$post['time'] = $v;
				}
				if($k == 'author') {
					foreach($v as $key => $value) {
						if($key == 'author') {
							$post['author'] = $value->name;
						}
						if($key == 'link') {
							$post['author_link'] = $value->link;
						} 
					}
				}
				if($k == 'link') {
					$post['link'] = $v;
				}
				if($k == 'type') {
					$post['type'] = $v;
				}
				if($k == 'video_thumbnail') {
					$post['video_thumbnail'] = $v;
				}
			}
			if( $post['time'] > (time() - 20*24*60*60) ) {
				array_push($sorted_items, $post);
				aasort($sorted_items,"time");
			}
			array_push($sorted_items2, $post);
		}
	} // ***END if{youtube}
	
	
	
	
} // ***END {foreach}

aasort($sorted_items,"time");

/*
echo 'Now: ' . time() . '(' . date("m.d.y", time()) . ')';
echo '<br />';
echo 'Last week:' . (time() - 10*24*60*60) . '(' . date("m.d.y", (time() - 10*24*60*60)) . ')'; 

die(); */
//print_r($sorted_items2); die();

/*foreach($items as $k => $v) {
//	var_dump($v);
//	echo "<br /><br /><br />" . $row['text'];
//	echo "<br /><br /><br />";
	$post['title'] = $v["title"];
	$post['time'] = (int) $v['unixtime'];
	$post['link'] = $v['link'];
	$post['text'] = $v['text'];
	$post['type'] = $v['type'];
	
	foreach($v as $key => $value) {
		if($key == 'author') {
			$post['author'] = $value->email;
		}
	}

	if($post['title'] != '') {
		echo '<div class="span3 well" style="height: 240px;">';
			echo '<img class="social-thumbnail" src="http://blogs.pcmag.com/securitywatch/assets_c/2010/02/facebook-logo-thumb-75x75-9031.jpg" />';
			echo '<a href="' . '#' . '">';
//				echo '<h6 class="post-title">' . strip_tags($post['title']) . '</h6>';
				echo '<h4 class="post-title">' . $post['author'] . "'s Wall" . '</h4>';
			echo '</a>';
			echo '<div class="clearfix"></div><hr />';
			echo '<p>' . strip_tags($post['text'])  . '</p>';
			echo date("F j, Y, g:i a", $post['time']);
		echo '</div>';
//		echo "<br /><br /><br />";
	}
//	die();
}
echo "<br /><br /><br />";
echo date("F j, Y, g:i a", 1384534260);
*/



?><!DOCTYPE html>
<html lang="en-US">
<head>
<title>SimplePie: Demo</title>


<link rel="stylesheet" href="./assets/css/social_aggregator.css" type="text/css" />
<script type="text/javascript" src="./for_the_demo/sifr.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script> -->

<style type="text/css">

.social-thumbnail {
	float: left;
	margin-right: 5px;
	width: 40px;
	height: 40px;
}
.post-title {
	float: left;
	width: 78%;
}


</style>
<script type="text/javascript">
function loadPage(pg) {
	$('.page').hide();
	$('ul#pagination li a').removeClass('current');
	$('#link-' + pg + ' a').addClass('current');
	$('#p' + pg).fadeIn();
}
</script>
</head>

<body>
<!--
foreach($items as $k => $v) {
//	var_dump($v);
//	echo "<br /><br /><br />" . $row['text'];
//	echo "<br /><br /><br />";
	$post['title'] = $v["title"];
	$post['time'] = (int) $v['unixtime'];
	$post['link'] = $v['link'];
	$post['text'] = $v['text'];
	$post['type'] = $v['type'];
	
	foreach($v as $key => $value) {
		if($key == 'author') {
			$post['author'] = $value->email;
		}
	}

	if($post['title'] != '') {
		echo '<div class="span3 well" style="height: 240px;">';
			echo '<img class="social-thumbnail" src="http://blogs.pcmag.com/securitywatch/assets_c/2010/02/facebook-logo-thumb-75x75-9031.jpg" />';
			echo '<a href="' . '#' . '">';
//				echo '<h6 class="post-title">' . strip_tags($post['title']) . '</h6>';
				echo '<h4 class="post-title">' . $post['author'] . "'s Wall" . '</h4>';
			echo '</a>';
			echo '<div class="clearfix"></div><hr />';
			echo '<p>' . strip_tags($post['text'])  . '</p>';
			echo date("F j, Y, g:i a", $post['time']);
		echo '</div>';
//		echo "<br /><br /><br />";
	}
//	die();
}
-->
<div id="container">
<div id="feed_container">
<div id="feeds">
<div id="viewer">
<?php $i = 0; $p = 1; ?>
<?php foreach($sorted_items as $post): ?>

<?php
$i += 1;
$remainder = $i % 6;

if ($remainder == 1 || $i == 1) {
	if($p == 1) {
		echo "<div class='page active' id='p$p'>";
	} else {
		echo "<div class='page' id='p$p'>";
	}
	$p += 1;
}
?>

	<?php if(($post['title'] != '' && $post['type'] == 'facebook') || $post['type'] == 'facebook'): ?>
		<div class="entry facebook">
			<div class="type">
				<a href="<?= $post['link']; ?>">
					<img alt="twitter" src="http://social.wm.edu/images/facebook_icon.png">
				</a>
			</div>
			
			<div class="title">
				<a href="<?= $post['link']; ?>">
					<span>
						<?php if( strlen($post['author']) <= 17 ): ?>
							<?= $post['author'] . "'s Wall"; ?>
						<?php else: ?>
							<?php echo substr($post['author'], 0, 20) . '...'; ?>
						<?php endif; ?>
					</span>
				</a>
				<div class="date">
					<div class="day"><?= date("m.d.y", $post['time']); ?></div>
					<div class="time"><?= date("g:ia", $post['time']); ?></div>
				</div>
			</div>
			<div class="entryContent">
				<p><?= strip_tags($post['text']); ?></p>
				<p style="margin-top: 8px;">
					<a href="<?= $post['link']; ?>">View Comments</a>
				</p>
			</div>
		</div>
	<?php elseif($post['type'] == 'youtube'): ?>
		<div class="entry youtube">
			<div class="type">
				<a href="<?= $post['link']; ?>">
					<img alt="youtube" src="http://social.wm.edu/images/youtube_icon.png">
				</a>
			</div>
			
			<div class="title">			
				<a href="<?= $post['link']; ?>">
					<span>
						<?php if( strlen($post['title']) <= 17 ): ?>
							<?= $post['title']; ?>
						<?php else: ?>
							<?php echo substr($post['title'], 0, 20) . '...'; ?>
						<?php endif; ?>
					</span>
				</a>
				<div class="date">
					<div class="day"><?= date("m.d.y", $post['time']); ?></div>
					<div class="time"><?= date("g:ia", $post['time']); ?></div>
				</div>
			</div>
			<div class="entryContent">
				<div class="highlight"></div>
				<a href="<?= $post['link']; ?>">
					<img class="preview" src="<?= $post['video_thumbnail']; ?>" />
				</a>
				<p style="margin-top: 8px;">
					
				</p>
			</div>
		</div>
	<?php elseif($post['type'] == 'twitter'): ?>
		<div class="entry twitter">
			<div class="type">
				<a href="http://twitter.com/<?= $post['title']; ?>">
					<img alt="twitter" src="http://social.wm.edu/images/twitter_icon.png">
				</a>
			</div>
			
			<div class="title">
				<a href="http://twitter.com/<?= $post['title']; ?>">
					<span><?= $post['title']; ?></span>
				</a>
				<div class="date">
					<div class="day"><?= date("m.d.y", $post['time']); ?></div>
					<div class="time"><?= date("g:ia", $post['time']); ?></div>
				</div>
			</div>
			<div class="entryContent">
				<p><?= $post['text']; ?></p>
			</div>
		</div>
	<?php endif; ?>
<?php 
if($remainder == 0 || $i == count($sorted_items)) {
	echo '</div>';
}
?>

<?php endforeach; ?>


</div><!-- ***END viewer -->

	<ul id="pagination">
	<?php for($pg = 1; $pg < $p; $pg++): ?>
		<li id="link-<?= $pg; ?>"><a onclick="loadPage(<?= $pg; ?>);" href="#">+<?= $pg; ?></a></li>
	<?php endfor; ?>
	</ul>
	
	<center style="color: black; font-weight:bold">
		Powered by <a href="#" style="color: black; font-weight: bold;">SocialHubster</a>
	</center>

</div><!-- ***END feeds -->
</div><!-- ***END feed_container -->
</div><!-- ***END container -->
</body>
</html>
