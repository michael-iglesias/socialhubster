<?php
session_start();


// Include SimplePie
// Located in the parent directory
require_once('lib/twitteroauth.php');
require_once('lib/config.php');
require_once('lib/db.php');



// ****************************************************************************************************
// Initialize Twitter API
/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, '462452177-B0KI4oy4tjw3H0dYk28tE3T6HDfAr9EpZtAdViqb', 'CbyZ9FQJV2UAnanJk8n4yx9FNNCNije3kCxQYP1Ow4R1w');
/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');
// ****************************************************************************************************


$service = $db->real_escape_string($_GET['service']);


// retrieve all social feeds for hub 
$query = "SELECT * FROM service LEFT JOIN feed ON service.service_id = feed.service_id WHERE service.service_name='$service'";

if ($result = $db->query($query)) {
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$rows[] = $row;
	}
    /* free result set */
    $result->close();
}

?>
<!doctype>
<html>
<head>
<link href="assets/css/jquery.socialist.css" rel="stylesheet" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src='assets/js/jquery.isotope.min.js'></script>
<script src='assets/js/jquery.socialist.js'></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#content').socialist({
    	getSortData : {
	    	date : function ($elem) {
		    	return $elem.find('.date').text();
	    	}
    	},
        networks: [
        	<?php foreach($rows as $r): ?>
	            {name:'<?= $r['type']; ?>',id:'<?= $r['account']; ?>'},        	
        	<?php endforeach; ?>
        	
        	/*
            {name:'facebook',id:'fsuit'},
            {name:'facebook',id:'starsfsu'},
            {name:'tumblr',id:'mugenstyle'},
            {name:'youtube',id:'gofsucci'},
            {name:'linkedin',id:'797453'},
            {name:'vimeo',id:'founding'},
            {name:'googleplus',id:'105588557807820541973/posts'},
            {name:'linkedin',id:'buddy-media'},
            {name:'rss',id:' http://feeds.feedburner.com/good/lbvp'},
            {name:'rss',id:'http://www.makebetterwebsites.com/feed/'}, */
//            {name:'pinterest',id:'ccifsu/pins/'}


           ],
        isotope:true,
        random:true,
        sortBy:'date',
		headingLength:20,
		textLength:200,
        fields:['source','heading','text','date','image','followers','likes']
    });
    

    
    
    
});
</script>

</head>
<body>

	<div id="wrapper">
		<div id="sidebar" style="width: 20%; float: left;">
<script id="invitebox-script" type="text/javascript">(function() {    var ib = document.createElement('script');    ib.type = 'text/javascript';    ib.async = true;    ib.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'invitebox.com/invitation-camp/6259/invitebox.js?key=77dfb0a94b9d3c012ccc6577942c4cd9&jquery='+(typeof(jQuery)=='undefined');    var s = document.getElementsByTagName('script')[0];    s.parentNode.insertBefore(ib, s);})();</script><a id="invitebox-href" href="http://invitebox.com/widget/6259/share">referral program</a>
		</div>
	    <div id="content" class="isotope" style="float: left; width: 60%;"></div>	
	</div>
    
    
    <script type="text/javascript">
$(function(){
      
      var $container = $('#container');
      
      $container.isotope({
        itemSelector : '.socialist',
        getSortData : {
	        date: function ($elem) {
	            return $elem.find('.date').text();
	        }
        }
      });
      
      $container.isotope( { sortBy : 'date' } );

      
});
    </script>
    
    

    
</body>
</html>