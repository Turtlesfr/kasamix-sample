$(document).ready(function () {
	$('.client-button-accept').attr('disabled','disabled');
	$(':checkbox').change(function() {
		console.log('form keyup');
		var numberOfChecked = $('input:checkbox:checked').length;
		var totalCheckboxes = $('input:checkbox').length;
		if(numberOfChecked == totalCheckboxes)
		{
			$('.client-button-accept').removeAttr('disabled');
			$('.client-button-refuse').attr('disabled','disabled');
		}
		else
		{
			$('.client-button-refuse').removeAttr('disabled');
			$('.client-button-accept').attr('disabled','disabled');
		}
	});
	$('.client-button-refuse').click(function()
	{
		$('.form-wrong').show();
	});
	$('.client-button-accept').click(function()
	{
		$.ajax({
            url: validateAccommodationInformationsUrl,
            method: 'POST',
            dataType: "json",
            data: {
                    'userId': userId
                },
            beforeSend: function () {
                console.log('saving user accommodation validation');
            }
        }).done(function (data) {
        	var counter = 10;
			setInterval(function() {
			    counter--;
			    if(counter < 0) {
			        window.location = dashboardUrl;
			    } else {
			        document.getElementById("count").innerHTML = counter;
			         }
			}, 1000);​
			$('.Center-Content h2').html(modalTitleValid);
			$('.Center-Content p:first').html(modalContentValid);
			$(".Modal-Background").toggleClass("is-Hidden");
			console.log(data);
            //user.updateSurfacePrice(surface_id);
        }).fail(function() {
    		alert( "error" );
  		}).always(function () {
        });
        return false;
	});

	$('#form').submit(function(e) {
	    e.preventDefault();
	    console.log($('#message').val());
	    var message = $('#message').val();
	    message += '\n User id : '+$('input[name=userId').val();
	    message += '\n User email : '+$('input[name=userEmail').val();
	    $.ajax({
            type: "POST",
            url: formUrl,
            data: 
            {
            	'message' : message
            },
            success: function(data){
            	$.ajax({
		            url: setAccommodationStatusUrl,
		            method: 'POST',
		            dataType: "json",
		            data: {
		                    'accommodationId': accommodationId, 'statusId': 3
		                },
		            beforeSend: function () {
		                console.log('saving acco status');
		            }
		        }).done(function (data) {
		        	var counter = 5;
					setInterval(function() {
					    counter--;
					    if(counter < 0) {
					        window.location = dashboardUrl;
					    } else {
					        document.getElementById("count").innerHTML = counter;
					         }
					}, 1000);​
	                $('.Center-Content h2').html(modalTitleWrong);
					$('.Center-Content p:first').html(modalContentWrong);
					$(".Modal-Background").toggleClass("is-Hidden");
		        }).fail(function() {
		    		alert( "error" );
		  		}).always(function () {
		        });
		        return false;
            },
            error: function(){
                console.log('failure');
            }
        });
	});

	$("input[type=submit]").attr('disabled','disabled');
});