<?php
session_start();
?>
<!doctype html>
<html>
  
  <head>
    <title>Social Hubster</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylsheet" href="assets/css/bootstrap.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/social.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <style type="text/css">
      body {
        padding-top: 50px;
        padding-bottom: 20px;
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
    </style>
  </head>
  
  <body>
    <div class="navbar navbar-fixed-top navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Social Hubster</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active">
              <a href="#">Home</a>
            </li>
            <li>
              <a href="gallery.php">Gallery</a>
            </li>
            <li>
              <a href="#contact">Contact</a>
            </li>
            
          </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div>
        <!--/.navbar-collapse -->
      </div>
    </div>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="col-md-6">
        <h2>Social Hub Info:</h2>
        <form>
          <div class="form-group">
            <label class="control-label" style="font-size: 15px;">Service Title (e.x. "Florida State University"):</label>
            <div class="controls">
              <input type="text" id="service-title" name="service-title" class="form-control short-input" required>
            </div>
          </div>
        </form>
        <div class="form-group">
          <label class="control-label" style="font-size: 15px;">Service Name (e.x. "florida_state_university"):</label>
          <div class="controls">
            <input type="text" id="service-name" name="service-name" class="form-control short-input" required>
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
	        	<img onclick="addFeed('twitter');" class="social-icon-add" src="./img/32/01_twitter.png" />
	        	<img onclick="addFeed('facebook');" class="social-icon-add" src="./img/32/02_facebook.png" />
	        	<img onclick="addFeed('youtube');" class="social-icon-add" src="./img/32/03_youtube.png" />
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
    <div class="clearfix"></div>
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-lg-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.</p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
        <div class="col-lg-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.</p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
        <div class="col-lg-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
      </div>
      <hr>
      <footer>
        <p>&copy; Company 2013</p>
      </footer>
    </div>
    <!-- /container -->
    
    
    <script>
    	var additionalFeed = 0;
    	function addFeed(social) {
		    additionalFeed += 1;
    		if(social == 'twitter') {
		    	$('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/01_twitter.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Twitter Handle"></div><div class="clearfix"></div>');
		    } else if(social == 'facebook') {
		    	$('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/02_facebook.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Facebook Page ID"></div><div class="clearfix"></div><p style="margin-left: 47px; font-size: 12px;"><a target="_blank" href="http://findmyfacebookid.com/">Find Out Your Facebook Page ID</a></p>');
		    } else if(social == 'youtube') {
		    	$('#insertAdditionalFeed').before('<div class="controls social-control"><img class="social-icon" src="./img/32/03_youtube.png" /><input type="text" id="feed-' + additionalFeed + '" class="form-control short-input social-input social" placeholder="Youtube Username"></div><div class="clearfix"></div>');
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
					window.location.href = "feed.php?service=" + $('#service-name').val(); //will redirect to your blog page (an ex: blog.html)
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
    
  </body>

</html>