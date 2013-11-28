<?php
// Start counting time for the page load
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

// Include SimplePie
// Located in the parent directory
require_once('../autoloader.php');
require_once('../idn/idna_convert.class.php');
require_once('lib/twitteroauth.php');
require_once('config.php');

// Create a new instance of the SimplePie object
$feed = new SimplePie();

//$feed->force_fsockopen(true);

if (isset($_GET['js']))
{
	SimplePie_Misc::output_javascript();
	die();
}

// Make sure that page is getting passed a URL
if (isset($_GET['feed']) && $_GET['feed'] !== '')
{
	// Strip slashes if magic quotes is enabled (which automatically escapes certain characters)
	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
	{
		$_GET['feed'] = stripslashes($_GET['feed']);
	}

	// Use the URL that was passed to the page in SimplePie
	$feed->set_feed_url($_GET['feed']);
}

// Allow us to change the input encoding from the URL string if we want to. (optional)
if (!empty($_GET['input']))
{
	$feed->set_input_encoding($_GET['input']);
}

// Allow us to choose to not re-order the items by date. (optional)
if (!empty($_GET['orderbydate']) && $_GET['orderbydate'] == 'false')
{
	$feed->enable_order_by_date(false);
}

// Trigger force-feed
if (!empty($_GET['force']) && $_GET['force'] == 'true')
{
	$feed->force_feed(true);
}

// Initialize the whole SimplePie object.  Read the feed, process it, parse it, cache it, and
// all that other good stuff.  The feed's information will not be available to SimplePie before
// this is called.
$success = $feed->init();

// We'll make sure that the right content type and character encoding gets set automatically.
// This function will grab the proper character encoding, as well as set the content type to text/html.
$feed->handle_content_type();

// When we end our PHP block, we want to make sure our DOCTYPE is on the top line to make
// sure that the browser snaps into Standards Mode.
class RSS {
      
      const default_cache_dir = "./cache";
      const default_cache_duration = 360;

      // via http://www.snipe.net/2009/09/php-twitter-clickable-links/
      private static function twitterify($ret) {
	    $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" >\\2</a>", $ret);
	    $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" >\\2</a>", $ret);
	    $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" >@\\1</a>", $ret);
	    $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" >#\\1</a>", $ret);
	    return $ret;
      }
      
      /**
      * From the original MIT-Mobile-Web rss_services.php with one addition for handling line breaks
      * 
      * Copyright (c) 2008 Massachusetts Institute of Technology
      * 
      * Licensed under the MIT License
      * Redistributions of files must retain the above copyright notice.
      * 
      */
      public static function cleanText($html) {
	    //replace line breaks with a single space
	    $html = str_replace("\n\r", " ", $html);
	    $html = str_replace("\r", " ", $html);
	    
	    //replace <br>'s with line breaks
	    $html = preg_replace('/<br\s*?\/?>/', "\n", $html);
	    
	    //replace <p>'s with line breaks
	    $html = preg_replace('/<\/?p>/', "\n", $html);
	    $html = preg_replace('/<p\s+?.*?>/', "\n", $html);
	    
	    //remove all other mark-ups
	    $html = preg_replace('/<.+?>/', '', $html);
	    
	    //replace all the non-breaking spaces
	    $html = str_replace("&nbsp;", " ", $html);
	    	    
	    return trim(htmlspecialchars_decode($html, ENT_QUOTES));
      }
	    
      private static function removeUrls($text) {
	    $string = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $text);
	    return $string;
      }
      
      /* Parse the description component of a Facebook page RSS feed item and return and array of the components
      "description" => status update text
      "link_title" => link title
      "link_source" => link source website
      "link_caption" => link caption
      "link_thumbnail" => link thumbnail
      "link_url" => link URL
      */
      private static function cleanFacebookDescription($html, $title) {
	    // break up the description, Facebook uses <br>s
	    $descAndLink = preg_replace('/<br\s*?\/?>/', "~", $html);
	    
	    // clean out all the defaulted formatting, save the image tags
	    $descAndLink = strip_tags($descAndLink,'<img><a>');
	    
	    // remove repeated title from description
	    $linkTitle = "";
	    $linkSource = "";
	    $linkCaption = "";
	    $linkThumbnail = "";
	    $bits = explode("~", $descAndLink);
	    $numBits = count($bits);
	    $status = $title;
	    
	    // if this is more than just a simple status update (ex. has a link)...
	    if ($numBits > 1) {
		  $urlMatches = array();
		  $status = $bits[0];
		  for($i = 0; $i < $numBits; $i++) {
			$bit = trim(htmlspecialchars_decode($bits[$i], ENT_QUOTES));
			// find the link image. if it exists and clean out Facebook's extra cruft in the link tag
			if (($imgPos = strpos($bit, "img class")) != FALSE) {
			      $endHref = strpos($bit, '"', strpos($bit,'href="')+6);
			      $linkThumbnail = substr($bit, 0, $endHref).'">'.substr($bit,$imgPos-1);
			      continue;
			}
			// find the link source url, if it exists
			if (preg_match('/(?:[a-z\-]+\.)+[a-z]{2,6}/', $bit, $urlMatches) > 0) {
			      if (strlen($urlMatches[0]) == strlen($bit)) {
				  $linkSource = $bit;
				  continue;
			      }
			}
			// if we've gotten here the string is either the title, if the link source hasn't been set
			if (strlen($bit) > 0 && empty($linkSource)) {
			      $linkTitle = $bit;
			} else { // or it's the caption for the link if the source has been set
			      $linkCaption = $bit;
			}
		  }
	    } elseif (strlen($html) > strlen($title)) {
		  $status = $html;
	    }
	    // pull out the link URL
	    $count = preg_match('/href=(["\'])(.*?)\1/', $linkTitle, $match);
	    if ($count > 0)
		  $linkUrl = $match[2];
	    $linkTitle = strip_tags($linkTitle);
	    // Save all info into array
	    $descInfo = array(
		  "text"		=> $status,
		  "link_title" 		=> $linkTitle,
		  "link_source" 	=> $linkSource,
		  "link_caption" 	=> $linkCaption,
		  "link_thumbnail" 	=> $linkThumbnail,
		  "link_url"        	=> $linkUrl
	    );
	    return $descInfo;
      }
      
      /* Parse the description component of a YouTube channel RSS feed item and return:
      "video_title" => Title of the video
      "video_desc" => Video description
      "video_length" => Video length
      "video_thumbnail" => Video thumbnail
      "video_views" => Number of views
      */
      private static function cleanYouTubeDescription($html, $vlink) {
	    $vidTitle = "";
	    $vidDesc = "";
	    $vidLength = "";
	    $vidThumbnail = "";
	    $vidViews = "";
	    $desc = strip_tags($html,'<img><a><span><div>');
	    $doc = new DOMDocument();
	    $doc->loadHTML($desc);
	    
	    // pull the video ID out of the html
	    parse_str( parse_url( $vlink, PHP_URL_QUERY ) );
	    $vidThumbnail = "http://img.youtube.com/vi/".$v."/0.jpg";
	    
	    // parse the links to get the title
	    $links = $doc->getElementsByTagName('a');
	    $vidTitle = $links->item(1)->nodeValue;
	    $i = 0;
	    // parse the spans and divs to get the other info
	    $spans = $doc->getElementsByTagName('span');
	    $vidDesc = $spans->item(0)->nodeValue;
	    $vidLength = $spans->item(5)->nodeValue;
	    $divs = $doc->getElementsByTagName('div');
	    $vidViews = $divs->item(5)->nodeValue;
	    $descInfo = array(
		  "video_title"		=> $vidTitle,
		  "video_desc"		=> $vidDesc,
		  "video_length"	=> $vidLength,
		  "video_thumbnail"	=> $vidThumbnail,
		  "video_views"		=> $vidViews
	    );
	    return $descInfo;
      }
      
      private static function initialize_SimplePie($rss_url, $cache_loc, $cache_dur) {
	    $feed = new SimplePie();
	    $feed->set_cache_location($cache_loc);
	    $feed->set_cache_duration($cache_dur);
	    $feed->set_feed_url($rss_url);
	    $feed->init();
	    $feed->handle_content_type();
	    return $feed;
      }
      
      /* Pull out all information from the Facebook status update, status, user,
      and link info if applicable */
      private static function get_facebook_item($item) {
	    $title = trim(strip_tags(self::cleanText(self::removeUrls($item->get_title()))));
	    $descInfo = self::cleanFacebookDescription($item->get_description(), $item->get_title());
	    $toret = array(
		  "title"    		=> $title,
		  "type"		=> "facebook",
		  "date"		=> getdate(strtotime($item->get_date('j M Y g:i a'))),
		  "unixtime"		=> strtotime($item->get_date()),
		  "author"		=> $item->get_author(),
		  "text"		=> $descInfo["text"],
		  "link" 		=> $item->get_link(),
		  "link_title" 		=> $descInfo["link_title"],
		  "link_source"		=> $descInfo["link_source"],
		  "link_caption" 	=> $descInfo["link_caption"],
		  "link_thumbnail" 	=> $descInfo["link_thumbnail"],
		  "link_url" 		=> htmlentities($descInfo["link_url"])
	    );
	    return $toret;
      }
      
      /* Pull out all information from the YouTube RSS item, including video
      title, description, link, length, thumbnail and number of views*/
      private static function get_youtube_item($item) {
	    $descInfo = self::cleanYouTubeDescription($item->get_description(), $item->get_link());
	    $title = $descInfo["video_title"];
	    $toret = array(
		  "title"      		=> $title,
		  "type"		=> "youtube",
		  "date"		=> getdate(strtotime($item->get_date('j M Y g:i a'))),
		  "unixtime"		=> strtotime($item->get_date()),
		  "author"		=> $item->get_author(),
		  "description"		=> htmlspecialchars($descInfo["video_desc"]),
		  "link" 		=> $item->get_link(),
		  "video_length" 	=> $descInfo["video_length"],
		  "video_thumbnail" 	=> $descInfo["video_thumbnail"],
		  "video_views"		=> $descInfo["video_views"]
	    );
	    return $toret;
      }
      
      /* Pull out all information from the Flickr RSS item, including title, upload date,
        thumbnail, small image, full image, and link */
      private static function get_flickr_item($item) {
	    $title = $item->get_title();
	    $encl = $item->get_enclosure();
	    $thumb = $encl->get_thumbnail();
	    $urlBits = explode('/', $thumb);
	    $p = explode('_',$urlBits[4]);
	    $toret = array(
		  "title"       	=> $title,
		  "type"		=> "flickr",
		  "date"		=> getdate(strtotime($item->get_date('j M Y g:i a'))),
		  "unixtime"		=> strtotime($item->get_date()),
		  "author"		=> $official_feed_info["flickr"]["id"],
		  "description"		=> self::cleanText($encl->get_description()),
		  "link"		=> $item->get_link(),
		  "photo_thumbnail"	=> $thumb,
		  "photo_small" 	=> str_replace("_s","_m", $thumb),
		  "photo_fullsize"	=> str_replace("_s","_z", $thumb),
		  "photo_id" 		=> $p[0]
	    );
	    
	    return $toret;
      }
      
      /* Get the Twitter status update, user, time and permalink */
      private static function get_twitter_item($item, $isSearch=false) {
	    $title = self::twitterify($item->get_title());
	    $link = $item->get_link();
	    
	    $bits = explode("/", $link);
	    if (count($bits) < 4) {
		return array();
	    }
	    $username = $bits[3];
	    // Pull out username if is a user's RSS feed
	    if (($isSearch == false) && (strpos($title, $username) == 0)) {
		  $title = substr($title, strpos($title, ":") + 2);
	    }
	    
	    $toret = array(
		  "title"       	=> $title,
		  "type"		=> "twitter",
		  "date"		=> getdate(strtotime($item->get_date('j M Y g:i a'))),
		  "unixtime"		=> strtotime($item->get_date()),
		  "author"		=> $username,
		  "description"		=> $title,
		  "link"		=> $link
	    );
	    return $toret;
      
      }
      
      private static function get_general_item($item) {
	    $title = $item->get_title();
	    $toret = array(
		  "title"		=> $title,
		  "type"		=> "story",
		  "date"		=> getdate(strtotime($item->get_date('j M Y g:i a'))),
		  "unixtime"		=> strtotime($item->get_date('j M Y g:i a')),
		  "author"		=> (is_null($item->get_author()))?"":$item->get_author()->get_name(),
		  "description"		=> $item->get_description(),
		  "text"     		=> self::cleanText($item->get_content()),
		  "link"		=> $item->get_link()
	    );
	    
	    return $toret;
      }
      
      public static function get_feed($rss_url, $cache_loc=self::default_cache_dir, $cache_dur=self::default_cache_duration) {
	    
	    // Treat as a simple and straightforward RSS feed
	    $feed = self::initialize_SimplePie($rss_url, $cache_loc, $cache_dur);
	    
	    $items = array();
	    if ($feed->error()) {
		  return $items;
	    }
	    
	    // If should be handled by a special parser, check that first
	    if (strpos($rss_url, "twitter.com") !== FALSE) {
		  foreach($feed->get_items() as $item) {
			$tmp = self::get_twitter_item($item);
			$items[$tmp["title"]] = $tmp;
		  }
	    } else if (strpos($rss_url, "flickr.com") !== FALSE) {
		  $ct = 0;
		  foreach($feed->get_items() as $item) {
			$title = $item->get_title();
			// Handle when photos have the same title
			if (isset($items[$title])) {
			      $title = $title.' ('.$ct.')';
			}
			$items[$title] = self::get_flickr_item($item);
			$ct++;
		  }
	    } else if (strpos($rss_url, "facebook.com") !== FALSE) {
		  foreach($feed->get_items() as $item) {
			$title = trim(strip_tags(self::cleanText(self::removeUrls($item->get_title()))));
			$tmp = self::get_facebook_item($item);
			$items[$tmp["text"]] = $tmp;
		  }
	    } else if (strpos($rss_url, "youtube.com") !== FALSE) {
		  foreach($feed->get_items() as $item) {
			$tmp = self::get_youtube_item($item);
			$items[$tmp["title"]] = $tmp;
		  }
	    } else {   
		  foreach($feed->get_items() as $item) {
			$tmp = self::get_general_item($item);
			$items[$tmp["title"]] = $tmp;
		  }
	    }
	    return $items;
      }

}

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




/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, '462452177-B0KI4oy4tjw3H0dYk28tE3T6HDfAr9EpZtAdViqb', 'CbyZ9FQJV2UAnanJk8n4yx9FNNCNije3kCxQYP1Ow4R1w');

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

/* Some example calls */
$twitter_content = $connection->get('statuses/user_timeline', array('screen_name' => 'justinbieber'));

//print_r($content); die();

// Twitter RSS FEED
//$rss_url = 'http://twitter.com/statuses/user_timeline/letusdorm.rss';

// Facebook RSS FEED
//TechNolegy
$rss_url = 'https://www.facebook.com/feeds/page.php?id=354906337970185&format=rss20';
//FSUIT
$rss_url2 = 'https://www.facebook.com/feeds/page.php?id=151175461574724&format=rss20';


$rss_url4 = 'https://www.facebook.com/feeds/page.php?id=139586386120138&format=rss20';

//$rss_url = 'https://www.facebook.com/feeds/page.php?id=151175461574724&format=rss20';
// YOUTUBE RSS FEED
$rss_url3 = 'http://gdata.youtube.com/feeds/base/users/gofsucci/uploads?orderby=published&max-results=10';


$f1 = new RSS();

$i = $f1->get_feed($rss_url);
$i2 = $f1->get_feed($rss_url2);
$i3 = $f1->get_feed($rss_url3);
$i4 = $f1->get_feed($rss_url4);
//array_push($items, $items2);


//print_r($i3); die();


//pushElementsToArray($i);
//pushElementsToArray($i2);

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
		
		if( $post['time'] > (time() - 30*24*60*60) ) {
			array_push($sorted_items, $post);
			aasort($sorted_items,"time");
		}
	}
	foreach($i2 as $k => $v) {
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
		if( $post['time'] > (time() - 30*24*60*60) ) {
			array_push($sorted_items, $post);
			aasort($sorted_items,"time");
		}
	}
	foreach($i4 as $k => $v) {
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
		if( $post['time'] > (time() - 30*24*60*60) ) {
			array_push($sorted_items, $post);
			aasort($sorted_items,"time");
		}
	}
	if($i3) {
		foreach($i3 as $row) {
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
			if( $post['time'] > (time() - 30*24*60*60) ) {
				array_push($sorted_items, $post);
				aasort($sorted_items,"time");
			}
			array_push($sorted_items2, $post);
		}
	
	}
	foreach($twitter_content as $row) {
		$post = array();
		$post['author'] = $row->user;		
		$post['title'] = '@' . $post['author']->screen_name;
		$post['time'] = (int) strtotime( $row->created_at );
		$post['type'] = 'twitter';
		$post['text'] = $row->text;
		$post['author_link'] = NULL;
		$post['video_thumbnail'] == NULL;
		if( $post['time'] > (time() - 30*24*60*60) ) {
			array_push($sorted_items, $post);
			aasort($sorted_items,"time");
		}
	}

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
