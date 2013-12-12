<?php 

require_once('../lib/twitteroauth.php');
require_once('../lib/config.php');
require_once('../lib/db.php');


$query = "SELECT * FROM feed WHERE type='twitter'";

if ($result = $db->query($query)) {
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$rows[] = $row;
	}
    /* free result set */
    $result->close();
}




// ****************************************************************************************************
// Initialize Twitter API
/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, '462452177-B0KI4oy4tjw3H0dYk28tE3T6HDfAr9EpZtAdViqb', 'CbyZ9FQJV2UAnanJk8n4yx9FNNCNije3kCxQYP1Ow4R1w');
/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');




//var_dump($rows); die();



?>
<!doctype>
<html>
<head>
	<title>Twitter Analysis</title>
	<script type="text/javascript">
	    var Keen=Keen||{configure:function(e){this._cf=e},addEvent:function(e,t,n,i){this._eq=this._eq||[],this._eq.push([e,t,n,i])},setGlobalProperties:function(e){this._gp=e},onChartsReady:function(e){this._ocrq=this._ocrq||[],this._ocrq.push(e)}};(function(){var e=document.createElement("script");e.type="text/javascript",e.async=!0,e.src=("https:"==document.location.protocol?"https://":"http://")+"dc8na2hxrj29i.cloudfront.net/code/keen-2.1.0-min.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
	
	    // Configure the Keen object with your Project ID and (optional) access keys.
	    Keen.configure({
	        projectId: "529a05ecd97b855e49000005",
	        writeKey: "136c8c0fab850f6dbdb0cac625d3adc8c8341866c5a61f4d78ec19da7735ea6528957cec58b70d5750d637369e4466b596515c3c0d79b24b73e2f2b441b1a1519d1acfb8187511d46b9093218525d985f1de89e92f93792fc187ca3a91ea248cc78a89267a64cc092bbdbad99cafba1c",
	        readKey: "ee4070d68abc9c2d96fe2a99cfecf76b9b056ae884ecc91c0fe50cf77c12c4ae1501af9ebe474abfeba588cb9e11185716e10378c4d34134e4e73b5c4515bdb59721513d0931b5321eefbfc578aa6b2357a24cda4b36275c029f0a1de42671558bc8cc83566dc9c89edda886e72ee566"
	    });
	    

	</script>
</head>
<body>
	<?php $i = 1; ?>
	<?php foreach($rows as $r): ?>
	<div id="chart<?= $i; ?>"></div>
	
	<script type="text/javascript">
		Keen.onChartsReady(function() {
		  var series = new Keen.Series("twitter" + <?= $r['service_id']; ?>, {
		    analysisType: "sum",
		    timeframe: "this_7_days",
		    interval: "daily",
		    targetProperty: "followers",
		    filters: [{"property_name":"account","operator":"eq","property_value":"<?= $r['account']; ?>"}],
		  });
		  
		  //Create a LineChart visualization for that Series.
		    var myLineChart = new Keen.LineChart(series, {
		        height: "300",
		        width: "800",
		        label: "Followers - <?= $r['account']; ?>",
		        title: "Follower Count For Last 7 Days"
		    });
		    //Draw the visualization into a div
		    myLineChart.draw(document.getElementById("chart<?= $i; ?>"));
		  
		});
	</script>
	
	
	
	<?php $i += 1; ?>
	<?php endforeach; ?>

	<div id="visualization-test">
		
	</div>

</body>
</html>