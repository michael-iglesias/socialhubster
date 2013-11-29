function createSocialHub() {
	var serviceTitle = $('#service-title').val();
	var serviceName = $('#service-name').val();
	var serviceEmail = $('#service-email').val();
	var servicePassword = $('#service-password').val();
	var validationError = true;
	
	if(serviceTitle == '') {
		$('#service-title').css('border', '1px solid red');
		validationError = true;
	} else {
		$('#service-title').css('border', '1px solid #CCCCCC');
		validationError = false;
	}
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
	
	if(validationError == false) {
		$.ajax({
			type: "POST",
			url: 'http://socialhubster.com/demo/process_service.php',
			data: {serviceTitle: serviceTitle, serviceName: serviceName, serviceEmail: serviceEmail, servicePassword: servicePassword},
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
					$('#service-title').attr('disabled', 'disabled');
					$('#service-name').attr('disabled', 'disabled');
					$('#service-email').attr('disabled', 'disabled');
					$('#service-password').attr('disabled', 'disabled');
					$('#service-title').css('background-color', 'white');
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