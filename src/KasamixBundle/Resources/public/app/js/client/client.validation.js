$(document).ready(function () {
	var user_balance = 0;
	$.each(accoPrices,function(i,room)
	{
		var room_id = room.room_id;
		if(room.surfaces.length > 0)
		{
			var room_price = 0;
			$.each(room.surfaces,function(j,surface)
			{
				var surface_id = surface.id;
				var surface_price = surface.price;
				room_price += surface_price;
				user_balance += surface_price;
			});
			$('.client-room-container[data-roomid="' + room_id +'"] .client-room-price-value').html(Math.round(room_price*100)/100);
		}
	});
	if(user_balance == 0)
	{
		$('.client-container-validation-price-wrapper').hide();
	}else
	{
		$('.client-balance').html(Math.round(user_balance*100)/100);
	}
	console.log(Math.round(user_balance*100)/100);
});