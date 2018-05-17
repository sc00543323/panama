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
			var dateTimeObservableArray = ko.observableArray();
            var city = $("[name='city']").val();
			var address = $("[name='street[0]']").val();
			var checkout = window.checkout;
			dateTimeObservableArray.push.apply(dateTimeObservableArray, [{ delivery_time: "12", start_time: "23" }]);
			$.ajax({
				url: checkout.baseUrl+'checkout/calculate/delivery',
				dataType: 'json',
				showLoader: true,
				type: 'post',
				data: city,
				success: function (data) {
					var result_id = data[0].ResultId;
					var result_message = data[0].ResultMessage;
					var delivery_array = data[0].DeliveryDateRangeList;
					if(result_id == 1) {
						var append_html ='';
						for (var i=0;i<delivery_array.length;++i) {
							dateTimeObservableArray.push.apply(dateTimeObservableArray, [{ delivery_time: delivery_array[i]._jornada, start_time: delivery_array[i]._horaInicioEntrega }]);
							append_html += '<input type="radio" data-bind="click: time" name="time" value="'+delivery_array[i]._jornada+'">'+delivery_array[i]._jornada+'<br>';
						}
						/*alert('The length of the array is ' + dateTimeObservableArray().length);
						alert('The first element is ' + dateTimeObservableArray()[0]['delivery_time']);
						alert('The first element is ' + dateTimeObservableArray()[1]['delivery_time']);*/
						$(".time_div").html(append_html);
					} else {
						$(".time_div").html("somthing went wrong!");
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
        }		
    });
});
