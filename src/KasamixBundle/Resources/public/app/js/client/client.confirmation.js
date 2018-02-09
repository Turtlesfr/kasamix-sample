$(document).ready(function () {
	$('.client-button-validate').click(function()
	{
		$.ajax({
            url: setAccommodationStatusUrl,
            method: 'POST',
            dataType: "json",
            data: {
                    'accommodationId': accommodationId, 'statusId': 2
                },
            beforeSend: function () {
                console.log('saving acco status');
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
			$(".Modal-Background").toggleClass("is-Hidden");
			console.log(data);
            //user.updateSurfacePrice(surface_id);
        }).fail(function() {
    		alert( "error" );
  		}).always(function () {
        });
        return false;
		
	});
});