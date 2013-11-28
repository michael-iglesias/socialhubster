<?

include_once('SimplePie.php');

class RSS {
      
      const default_cache_dir = "../cache";
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

?>
