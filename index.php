
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>SocialHubster - Create A Free Social Media Hub</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
        
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="/bootstrap/img/favicon.ico">
        <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" type="text/css" rel="stylesheet">
        <link rel="stylsheet" href="assets/css/additional_styles.css">
        
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/social.js"></script>


        <!-- CSS code from Bootply.com editor -->
        
        <style type="text/css">
            /* -- custom css for Bootstrap 3.x --*/

/* move special fonts to HTML head for better performance */
@import url('http://fonts.googleapis.com/css?family=Open+Sans:200,300,400,600');

html,
body {
  height: 100%;
  width: 100%;
  font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;
}

/* fix bs3 horizontal scrollbar bug */
.row { margin: 0; padding: 0 }

h1 {
  font-size:50px; 
}

img.grayscale {
    filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */
}

.icon-bar {
   background-color:#fff;
}

.scroll-down a, .scroll-top a {
   color:#ffffff;
}

.scroll-down {
   position:fixed;
   bottom:20%;
   right:0%;
   color:#f9f9f9;
}

.scroll-top {
  background-color:#33ee67;
}

.header .btn-lg {
   font-size:28px;
   border-color:#eeeeee;
   padding:15px;
   background-color:transparent;
   color:#ffffff;
}

.header .btn-lg:hover {
   background-color:#eeeeee;
   color:#777777;
}

.navbar a {
  color:#fff;
}

.navbar-bold.affix {
  background-color:#33ee67;
}

.navbar-bold {
  background-color:#11cc45;
  font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
}

.navbar-bold li a:hover {
  background-color:#00bb34;
}

.navbar-bold li.active {
  background-color:#00bb34;
}

.vert {
  vertical-align: middle;
  width:100%;
  padding-top:4%;
}

.header h1 {
  font-size:110px;
  -webkit-text-stroke: 1px rgba(0,0,0,0.1);
  color:#431a6d;
  color:#fff;
  margin-left:-5px;
  margin-bottom:5px;
  font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
}

.header .lead {
  color:#eeeeee;
  font-size:25px;
}

.header {
  height: 80%;
  background: #11cc45 url('http://www.bootply.com/assets/example/pt_squares_lg.png') repeat center center fixed;
}

.blurb {
  padding: 120px 0;
  background-color:#fefefe;
}

.blurb .panel {
  background-color:transparent;
}

.bright {
  background: #7fbbda url('http://www.bootply.com/assets/example/bg_suburb.jpg') no-repeat center center fixed; 
  color:#fff;
}

.featurette {
  background: #fff;
  padding: 50px 0;
  color: #000;
}

.featurette-item {
  margin-bottom: 15px;
}

.featurette-item > i {
  border: 3px solid #ffffff;
  border-radius: 50%;
  display: inline-block;
  font-size: 56px;
  width: 140px;
  height: 140px;
  line-height: 136px;
  vertical-align: middle; 
  text-align: center;
}

.featurette-item > i:hover {
  font-size: 68px;
}

.callout {
  color: #ffffff;
  padding-top:7%;
  height: 100%;
  width: 100%;
  background: url('http://www.bootply.com/assets/example/bg_suburb.jpg') no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

.gallery {
  padding: 50px 0;
}

.call-to-action {
  background: #eeeeee;
  padding: 50px 0;
}

.call-to-action .btn {
  margin: 10px;
}

footer {
  padding: 100px 0;
}
.short-input {
width: 355px;
}
.social-input, .social-icon {
  float: left;
}
.social-icon {
  margin-right: 5px;
}
.social-control {
  margin-top: 10px;
}
.social-icon-add {margin-right: 5px;}
.social-icon-add:hover {cursor: pointer;}
.feed-added {border: 2px solid green; background-color: white;};
.hide{display: none;}

/* -- end custom css for Bootstrap 3.x --*/

        </style>
    </head>
    
    <!-- HTML code from Bootply.com editor -->
    
    <body  >
        
        
<div class="navbar navbar-fixed-top navbar-bold" data-spy="affix" data-offset-top="1000">
  <div class="container">
    <div class="navbar-header">
      <a href="#" class="navbar-brand">Home</a>
      <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
      <ul class="nav navbar-nav">
        <li><a href="#features">Features</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#get-started">Get Started</a></li>
        <li><a href="#contact">Contact Us</a></li>
      </ul>
		<form method="post" action="manage/login.php" class="navbar-form navbar-right">
			<div class="form-group">
			  <input type="text" class="form-control" placeholder="Email">
			</div>
			<div class="form-group">
			  <input type="password" class="form-control" placeholder="Password">
			</div>
			<button class="btn btn-success" type="submit">Sign in</button>
		</form>
    </div>
   </div>
</div>

<div class="header vert">
  <div class="container" style="text-align: center;">
    
    <h1>Social Hubster</h1>
      <p class="lead">Aggregate All Of Your Social Media Streams Into One!</p>
      <div>&nbsp;</div>
      <a href="#" class="btn btn-default btn-lg">Get Started It's Free!</a>
  </div>
</div>


<div class="featurette" id="features">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h1>Amazing Features</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2 col-md-offset-2 text-center">
        <div class="featurette-item">
          <i class="icon-desktop"></i>
          <h4>One Hub</h4>
          <p>All of your social media streams into one aggregated Social Hub</p>
        </div>
      </div>
      <div class="col-md-2 text-center">
        <div class="featurette-item">
          <i class="icon-mobile-phone"></i>
          <h4>Mobile Compatible</h4>
          <p>Your audience can view your Social Hub on any device.</p>
        </div>
      </div>
      <div class="col-md-2 text-center">
        <div class="featurette-item">
          <i class="icon-play"></i>
          <h4>Media Displays</h4>
          <p>Display your Social Hub on large screen displays at expos, conferences, etc.</p>
        </div>
      </div>
      <div class="col-md-2 text-center">
        <div class="featurette-item">
          <i class="icon-dashboard"></i>
          <h4>Analytics</h4>
          <p>Measure key metrics to analyze your social hub's success</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="about" class="blurb bright" style="padding: 45px 0px;">
  
  <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
        <h3>About Social Hubster</h3>
        <br>
      </div>
  </div>
  
  <div class="row">
    <div class="col-sm-4 col-sm-offset-2">
         <div class="panel panel-default">
         <div class="panel-heading text-center"><h2>Share To Unlock</h2></div>
         <div class="panel-body text-center">
         	Creating your Social Hub is absolutely free! If you want access to key analytics that provide valuable insight as to the performance of your Social Hub, you can simply share our site and spread the word through social media to unlock access to your Analytical Dashboard.	
         </div>
         </div>
 	</div>
    <div class="col-sm-4">
         <div class="panel panel-default">
         <div class="panel-heading text-center"><h2>Gallery</h2></div>
         <div class="panel-body text-center"> 
	         Not sure how you can use Social Hubster... Don't worry, check out what some of clients are up to :)<br /><br />
	         <a href="#about">Gallery</a>
	         <br /><br />
         </div>
         </div>
 	</div>
  </div>
</div>



<div class="blurb" id="get-started">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
			<div class="col-md-6">
			    <h2>Social Hub Info:</h2>
			    <div class="form-group">
			      <label class="control-label" style="font-size: 15px;">Social Hub Name (all lower case no spaces, underscores(_) are allowed):</label>
			      <div class="controls">
			        <input type="text" id="service-name" name="service-name" class="form-control short-input" placeholder="e.x. - michaels_blog" required>
			        <span style="margin-bottom: 0px; padding-bottom: 0px; display: none; color: red; font-size: 12px; margin-top: 0px;" id="service-name-taken">Name Already Taken! Please choose another.</span>
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="control-label" style="font-size: 15px;">Email:</label>
			      <div class="controls">
			        <input type="email" id="service-email" name="service-email" class="form-control short-input" required>
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="control-label" style="font-size: 15px;">Password:</label>
			      <div class="controls">
			        <input type="password" id="service-password" name="service-password" class="form-control short-input" required>
			      </div>
			    </div>
			    <p><a id="submitbtn" onclick="createSocialHub();" class="btn btn-primary btn-lg">Next: Add Social Feeds &rarr;</a></p>
			  </div>
			  <div class="col-md-5" id="social-feeds-div" style="display: none; text-align: center;">
			    <h2 style="margin-bottom: 25px;">Social Media Accounts:</h2> 
			    
			    <div id="insertAdditionalFeed"></div>
			    <!-- Add social feed selection -->
			    
			    <br />
			    <div style="text-align: center; margin: 0 auto;">
			    	<center>
			        	<img onclick="addFeed('facebook');" class="social-icon-add" src="./img/32/02_facebook.png" />
			        	<img onclick="addFeed('youtube');" class="social-icon-add" src="./img/32/03_youtube.png" />
			        	<img onclick="addFeed('tumblr');" class="social-icon-add" src="./img/32/15_tumblr.png" />
			        	<img onclick="addFeed('pinterest');" class="social-icon-add" src="./img/32/13_pinterest.png" />
			        	<img onclick="addFeed('googleplus');" class="social-icon-add" src="./img/32/14_google+.png" />
			        	<img onclick="addFeed('instagram');" class="social-icon-add" src="./img/32/10_instagram.png" />
			        	<img onclick="addFeed('vimeo');" class="social-icon-add" src="./img/32/09_vimeo.png" />
			        	<div class="clearfix"></div>
			        	<p>Add Social Feed</p>
			        	<div id="finalize-social-feed" style="display: none;">
			        	
			        	<p style="font-size: 14px;">OR</p>
			        	<input type="hidden" id="additionalFeed" value="0" />
			        	<p><a id="createHub" onclick="processFeedCreation();" class="btn btn-primary btn-lg">Finish: Create Social Hub &rarr;</a></p>
						</div>
			    	</center>
			    </div>
			
			    
			    <!-- ***END additional social feed selection -->
			    
			     
			  </div>
			  <div class="col-md-5" id="social-feed-img" style="text-align: center;">
				     <h2 style="margin-bottom: 25px;">Social Media Accounts:</h2>
				     <img src="img/faded_social_media.png" />
			  </div>
			  <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>


<div id="contact" class="blurb">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
        <h2>Contact Us</h2>
        <p>
        	If you have any questions or concerns regarding our services offered do not hesitate in contacting us.
        </p>
        <p class="lead">
        	Phone: <strong>(954)937-9046</strong>
        	<br>
        	<small>Email: <a href="mailto:mci12@my.fsu.edu"><strong>Shoot us an email</strong></a></small>
        </p>
      </div>
    </div>
  </div>
</div>


<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
        <ul class="list-inline">
          <li><i class="icon-facebook icon-2x"></i></li>
          <li><i class="icon-twitter icon-2x"></i></li>
          <li><i class="icon-google-plus icon-2x"></i></li>
          <li><i class="icon-pinterest icon-2x"></i></li>
        </ul>
        <hr>
        <p>Built with <i class="icon-heart-empty"></i> at <a href="http://socialhubster.com">Social Hubster</a>.<br>SocialHubster.com ©2013</p>
      </div>
    </div>
  </div>
</footer>

<ul class="nav pull-right scroll-down">
  <li><a href="#" title="Scroll down"><i class="icon-chevron-down icon-3x"></i></a></li>
</ul>
<ul class="nav pull-right scroll-top">
  <li><a href="#" title="Scroll to top"><i class="icon-chevron-up icon-3x"></i></a></li>
</ul>

        
        <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


        <script type='text/javascript' src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>



        
        <!-- JavaScript jQuery code from Bootply.com editor -->
        
        <script type='text/javascript'>
        
        $(document).ready(function() {
        
            /* smooth scrolling for scroll to top */
$('.scroll-top').click(function(){
  $('body,html').animate({scrollTop:0},1000);
})
/* smooth scrolling for scroll down */
$('.scroll-down').click(function(){
  $('body,html').animate({scrollTop:$(window).scrollTop()+800},1000);
})

/* highlight the top nav as scrolling occurs */
$('body').scrollspy({ target: '#navbar' })

        
        });
function requestFullScreen() {
    if ((document.fullScreenElement && document.fullScreenElement !== null) ||    
       (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if (document.documentElement.requestFullScreen) {  
      document.documentElement.requestFullScreen();  
    } else if (document.documentElement.mozRequestFullScreen) {  
      document.documentElement.mozRequestFullScreen();  
    } else if (document.documentElement.webkitRequestFullScreen) {  
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);  
    }  
  } else {  
    if (document.cancelFullScreen) {  
      document.cancelFullScreen();  
    } else if (document.mozCancelFullScreen) {  
      document.mozCancelFullScreen();  
    } else if (document.webkitCancelFullScreen) {  
      document.webkitCancelFullScreen();  
    }  
  }  
} 


        </script>
        
<script>
    	var additionalFeed = 0;
    	function addFeed(social) {
		    additionalFeed += 1;
    		if(social == 'tumblr') {
		    	$('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/15_tumblr.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Twitter Handle"></div><div class="clearfix"></div>');
		    } else if(social == 'facebook') {
		    	$('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/02_facebook.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Facebook Page ID"></div><div class="clearfix"></div><p style="margin-left: 47px; font-size: 12px;"><a target="_blank" href="http://findmyfacebookid.com/">Find Out Your Facebook Page ID</a></p>');
		    } else if(social == 'youtube') {
		    	$('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/03_youtube.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Youtube Username"></div><div class="clearfix"></div>');
		    } else if(social == 'pinterest') {
			    $('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/13_pinterest.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Youtube Username"></div><div class="clearfix"></div>');
		    } else if(social == 'vimeo') {
			    $('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/09_vimeo.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Youtube Username"></div><div class="clearfix"></div>');
		    } else if(social == 'instagram') {
			    $('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/10_instagram.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Youtube Username"></div><div class="clearfix"></div>');
		    } else if(social == 'googleplus') {
			    $('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/14_google+.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Youtube Username"></div><div class="clearfix"></div>');
		    }
		    
		    $('#insertAdditionalFeed').before('<input type="hidden" id="feedtype' + additionalFeed + '" value="' + social + '" />');

	    	$('#additionalFeed').val(additionalFeed);
	    	$('#finalize-social-feed').show();

    	} // ***END addFeed()
    	
			function processFeedCreation() {
				for(var processFeed = 1; processFeed <= additionalFeed; processFeed++) {
					var type = $('#feedtype' + processFeed).val();
					var account = $('#feed-' + processFeed).val();
					
					processFeedPost(processFeed, type, account);
				} // ***END for{}
				
				$('#createHub').addClass('btn-disabled');
				$('#createHub').attr('disabled', 'disabled');				
				setTimeout(function () {
					window.location.href = "manage/index.php"; //will redirect to your blog page (an ex: blog.html)
				}, 2000); //will call the function after 2 secs.
			} // ***END processFeedCreation
			
			function processFeedPost(processFeed, type, account) {
				$.ajax({
					type: "POST",
					url: 'http://socialhubster.com/process_feed.php',
					data: {type: type, account: account},
					success: function(data) {
						if(data == 1) {
							$('#feed-' + (processFeed)).addClass('feed-added');
							$('#feed-' + (processFeed)).attr("disabled", "disabled");
						}
					}, 
					error: function() {
						alert('System Error! Please try again.');
					},
					complete: function() {
						console.log('completed')
					}
				}); // ***END $.ajax call
			}
    	
    	
    </script>
<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0019/9437.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>
        
        
    </body>
</html>