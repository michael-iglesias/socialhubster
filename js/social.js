$(document).ready(function(){

	$('#service-name').keyup(function() {
		var txt = $('#service-name').val();
		txt = txt.toLowerCase().replace(/ /g, '_');
		$('#service-name').val(txt);
	});
	$('#service-name').change(function() {
		var serviceName = $('#service-name').val();
		$.ajax({
			type: "POST",
			url: 'http://socialhubster.com/check_service_name.php',
			data: {serviceName: serviceName},
			success: function(data) {
				if(data == 1) {
					// Display Error - service name already taken
					$('#service-name').css('border', '1px solid red');
					$('#service-name-taken').show();
				} else {
					$('#service-name').css('border', '1px solid green');
					$('#service-name-taken').hide();
				}
			}, 
			error: function() {
				alert('System Error! Please try again.');
			},
			complete: function() {
				console.log('completed')
			}
		}); // ***END $.ajax call
	});


});

function createSocialHub() {
	var serviceName = $('#service-name').val();
	var serviceEmail = $('#service-email').val();
	var servicePassword = $('#service-password').val();
	var validationError = true;
	
	if(serviceName == '') {
		$('#service-name').css('border', '1px solid red');
		validationError = true;
	} else {
		$('#service-name').css('border', '1px solid #CCCCCC');
		validationError = false;
	}
	if(serviceEmail == '') {
		$('#service-email').css('border', '1px solid red');
		validationError = true;
	} else {
		$('#service-email').css('border', '1px solid #CCCCCC');
		validationError = false;
	}	
	if(servicePassword == '') {
		$('#service-password').css('border', '1px solid red');
		validationError = true;
	} else {
		$('#service-password').css('border', '1px solid #CCCCCC');
		validationError = false;
	}
	
	// CHECK SERVICE NAME AVAILABILITY
	$.ajax({
		type: "POST",
		url: 'http://socialhubster.com/check_service_name.php',
		data: {serviceName: serviceName},
		success: function(data) {
			if(data == 1) {
				validationError = true;
				$('#service-name-taken').show();
			} else {
				validationError = false;
				$('#service-name-taken').hide();
			}
		}, 
		error: function() {
			alert('System Error! Please try again.');
		},
		complete: function() {
			console.log('completed')
		}
	}); // ***END $.ajax call
	
	if(validationError == false) {
		$.ajax({
			type: "POST",
			url: 'http://socialhubster.com/process_hub.php',
			data: {serviceName: serviceName, serviceEmail: serviceEmail, servicePassword: servicePassword},
			success: function(data) {
				if(data > 0) {
					$('#social-feed-img').hide();
					$('#social-feeds-div').show();
					$('#submitbtn').hide();
					$('#submitbtn').html('Created!');
					$('#submitbtn').removeClass('btn-primary');
					$('#submitbtn').addClass('btn-success');
					$('#submitbtn').attr("disabled", "disabled");
					$('#submitbtn').fadeIn();
					$('#service-name').attr('disabled', 'disabled');
					$('#service-email').attr('disabled', 'disabled');
					$('#service-password').attr('disabled', 'disabled');
					$('#service-name').css('background-color', 'white');
					$('#service-email').css('background-color', 'white');
					$('#service-password').css('background-color', 'white');
	
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
} // ***END createSocialHub()


function processFeedCustomization() {
	var serviceTitle = $('#service-title').val();
	var fontColor = $('#font-color-select').val();
	var titleFontColor = $('#title-font-color').val();
	var backgroundColor = $('#bg-color').val();
	var tileBackgroundColor = $('#tile-bg-color').val();
	var contentBackgroundColor = $('#content-bg-color').val();
	var dottedBorderColor = $('#dotted-border-bg-color').val();
	var contentBorderColor = $('#content-border-bg-color').val();
	
	$.ajax({
		type: "POST",
		url: 'http://socialhubster.com/feed_customization.php',
		data: {serviceTitle: serviceTitle, fontColor: fontColor, titleFontColor: titleFontColor, backgroundColor: backgroundColor, tileBackgroundColor: tileBackgroundColor, contentBackgroundColor: contentBackgroundColor, dottedBorderColor: dottedBorderColor, contentBorderColor: contentBorderColor},
		success: function(data) {
			if(data == 1) {
				window.location.href = "/manage/index.php";
			} else {
				alert('Error! Please Try Submitting Again.');
			}
		}, 
		error: function() {
			alert('System Error! Please try again.');
		},
		complete: function() {
			console.log('completed')
		}
	}); // ***END $.ajax call
} // ***END processFeedCustomization()
