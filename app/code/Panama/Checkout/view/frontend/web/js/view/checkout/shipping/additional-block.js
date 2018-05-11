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
            $(".time_date_div").toggle();
        },
		
		time: function () {
			var time = $('input[name=time]:checked').val();			
			var date = $(".date_div p").html();
			$("#delivery_date_time").val(date+' '+time);			
			$("[name='custom_attributes[delivery_date_time]']").val(date+' '+time);
        }
		
    });
});
