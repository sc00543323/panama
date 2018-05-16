define([
	'jquery',
	'ko',
    'uiComponent'

], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Panama_Checkout/checkout/shipping/additional-block'
        },
		
		selectButton: function () {
            var city = $("[name='city']").val();
			var address = $("[name='street[0]']").val();
			//alert(city);
			//alert(address);
			var checkout = window.checkout;
			$.ajax({
				url: checkout.baseUrl+'checkout/calculate/delivery',
				dataType: 'json',
				showLoader: true,
				type: 'post',
				data: city,
				success: function (data) {
					if(data == true) {
						//alert("succsesss");
					} else {
						//alert("faiii");
					}
				}
			});
            //$(".time_date_div").toggle();
        },
		
		time: function () {
			var time = $('input[name=time]:checked').val();			
			var date = $(".date_div p").html();
			$("#delivery_date_time").val(date+' '+time);			
			$("[name='custom_attributes[delivery_date_time]']").val(date+' '+time);
        }
		
    });
});
