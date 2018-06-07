define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'mage/url',
        'Magento_Checkout/js/model/full-screen-loader',
        'jquery',
		'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/step-navigator'
    ],
    function (
        ko,
        Component,
        _,
        urlBuilder,
        fullScreenLoader,
        $,
		quote,
        stepNavigator
    ) {
        'use strict';
        var checkoutConfig = window.checkoutConfig;
		var portin_product = '';
		var current_service = '';
        /**
        *
        * mystep - is the name of the component's .html template,
        * <Vendor>_<Module>  - is the name of the your module directory.
        *
        */
        return Component.extend({
            defaults: {
                template: 'Digicel_Portin/portin-form',
            },            
            //add here your logic to display step,
            isVisible: ko.observable(quote.isVirtual()),
			
            /**
			*
			* @returns {*}
			*/
            initialize: function () {
                this._super();
				
				
				var urlPost = urlBuilder.build('portin/Terms/Checkportin');
				$.ajax({
					dataType: 'json',
					url: urlPost,
					async: false,
					type: 'post',
					success: function(result)
					{
													
						portin_product = result;
						current_service = result.currentService;
						return true;
					},
						error: function(){
						return false;
					}
			});
			if(portin_product.portin == 1){
				// register your step
                stepNavigator.registerStep(
                    'portin',
                    //step alias
                    null,
                    'Port In',
                    //observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                    * sort order value
                    * 'sort order value' < 10: step displays before shipping step;
                    * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                    * 'sort order value' > 20 : step displays after payment step
                    */
                    12
                );
				}

                return this;
            },
			
			/**
			 * Load data from server for Porting step
			 */
			navigate: function () {
				//load data from server for Porting step
				var self = this;
				if (isCustomerLoggedIn == false){
					self.visible(false);
				}
			},
			
			service: function(){
				return current_service;
			},
			
			getNip:function(){				
				$('.portin-first').hide();			
				fullScreenLoader.startLoader();
				var current_operater = $('#CurrentOperator').val();
				var current_number = $('#currentNumber').val();
				var urlPost = urlBuilder.build('portin/Index/Getnip');
				$.ajax({
                            dataType: 'json',
                            url: urlPost,
							async: false,
                            type: 'post',
							data:{'CurrentOperater':current_operater,'current_number':current_number},
                            success: function(result)
                            {	
							    //alert('got success');
							    $('.portin-first').hide();
							    $('.portin-second').show();
								fullScreenLoader.stopLoader();
								$('.portin-block').html(result.html);
								$('.Saveportin').attr('action', '#');
								return true;
                            },
                            error: function(){
								fullScreenLoader.stopLoader();
                                return false;
                            }
                        }); 
				
			},
			saveNip:function(){				
				fullScreenLoader.startLoader();
				var nipData = {},
                formDataArray = $('#form-portin').serializeArray();
                // formDataArray.forEach(function (entry) {
                    // if(nipData[entry.name] == 'nipNumber'){
						// nipData[entry.name] = entry.value;
					// }else{
						// nipData[entry.name] = entry.value;
					// }
                    
                // });
				
				var urlPost = urlBuilder.build('portin/Index/saveNip');
				$.ajax({
                            dataType: 'json',
                            url: urlPost,
							async: false,
                            type: 'post',
							data:formDataArray,
                            success: function(result)
                            {	
							   if(result.valid == 1){
									$('.portin-error').hide();
									stepNavigator.next();
								}else{
									$('.portin-error').show();
									$('.portin-error').html(result.message);
								}
								fullScreenLoader.stopLoader();								
                            },
                            error: function(){
								fullScreenLoader.stopLoader();
                                return false;
                            }
                        }); 
				
			},

            downloadPortin:function(){               
                fullScreenLoader.startLoader();
                var urlPost = urlBuilder.build('portin/Terms/Downloadportin');                
                window.location.href = urlPost;
                fullScreenLoader.stopLoader();
            },
			PortinFile:function(){
				var urlPost = urlBuilder.build('portin/Terms/ViewFile');
				var fname = null;
				$.ajax({
					url: urlPost,
					async: false,
					type: 'post',
					data: {"type": "portin"},
					success: function(result)
					{	
						fname = urlBuilder.build(result);
					}
				}); 
				return fname;
            },
			
            /**
			* The navigate() method is responsible for navigation between checkout step
			* during checkout. You can add custom logic, for example some conditions
			* for switching to your custom step
			*/
            navigate: function () {

            },

            /**
			* @returns void
			*/
            navigateToNextStep: function () {
                stepNavigator.next();
            }
        });
		
		
    
	
		
		
    }
);