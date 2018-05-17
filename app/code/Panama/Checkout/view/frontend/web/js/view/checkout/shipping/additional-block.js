define([
	'jquery',
	'ko',
    'uiComponent'

], function ($, ko, Component) {
    'use strict';

	var dateTimeObservableArray = ko.observableArray();
    return Component.extend({
        defaults: {
            template: 'Panama_Checkout/checkout/shipping/additional-block'
        },
		
		selectButton: function () {
            var city = $("[name='city']").val();
			var address = $("[name='street[0]']").val();
			var dateTime_data = {city: city, address: address};
			var checkout = window.checkout;
			$.ajax({
				url: checkout.baseUrl+'checkout/calculate/delivery',
				dataType: 'json',
				showLoader: true,
				type: 'post',
				data: dateTime_data,
				success: function (data) {
					var result_id = data[0].ResultId;
					var result_message = data[0].ResultMessage;
					var delivery_array = data[0].DeliveryDateRangeList;
					if(result_id == 1) {
						for (var i=0;i<delivery_array.length;++i) {
							dateTimeObservableArray.push.apply(dateTimeObservableArray, [{ delivery_time: delivery_array[i]._jornada, start_time: delivery_array[i]._horaInicioEntrega }]);
						}
					} else {
						$(".time_div").html("something went wrong!");
					}
				}
			});
            $(".time_date_div").toggle();
        },
		
		time: function () {
			var time = $('input[name=time]:checked').val();			
			var date = $(".date_div p").html();
			$("#delivery_date_time").val(date+' '+time);			
			$("[name='custom_attributes[delivery_date_time]']").val(date+' '+time);
        },
		getDateTimeObservableArray: function () {
                return dateTimeObservableArray;
            }
    });
});
