var userChoice;
user = 
{
	saveSelection: function(event)
	{
		event.preventDefault();
		var form = $(event.target).closest('form');
		$(form).find('input').each(function(e)
		{
			var surface_id = $(this).attr('name').split('-')[1];
			var covering_id = $(this).val();
			console.log('surface_id : '+surface_id+', covering_id : '+covering_id);
			user.updateSurface(surface_id,covering_id);
		});
	},
	updateSurface: function(surface_id,covering_id)
	{
		$.ajax({
            url: updateSurfaceUrl,
            method: 'POST',
            dataType: "json",
            data: {
                    'surfaceId': surface_id, 'coveringId': covering_id
                },
            beforeSend: function () {
                console.log('launching action');
            }
        }).done(function (data) {
        	//console.log(data);
            console.log('action A ended');
            updateChoiceUser();
            $('.save-success').slideToggle();
            $('.save-success').delay(1500).slideToggle();
            //user.updateSurfacePrice(surface_id);
        }).fail(function() {
    		alert( "error" );
  		}).always(function () {
        });
        return false;
	},
    updateSurfacePrice: function(surface_id)
    {
        $.ajax({
            url: updateSurfacePriceUrl,
            method: 'POST',
            dataType: "json",
            data: {
                    'surfaceId': surface_id
                },
            beforeSend: function () {
                console.log('update surface price');
            }
        }).done(function (data) {
            console.log('surface price saved');
            console.log(data);
        }).fail(function() {
            alert( "error" );
        }).always(function () {
        });
        return false;
    }
}