define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'mage/url',
        'Magento_Checkout/js/model/full-screen-loader',
        'jquery',
        'Magento_Checkout/js/model/step-navigator'
    ],
    function (
        ko,
        Component,
        _,
        urlBuilder,
        fullScreenLoader,
        $,
        stepNavigator
    ) {
        'use strict';
        var checkoutConfig = window.checkoutConfig;
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
            isVisible: ko.observable(false),
			
            /**
			*
			* @returns {*}
			*/
            initialize: function () {
                this._super();
                // register your step
                stepNavigator.registerStep(
                    //step code will be used as step content id in the component template
                    'portin',
                    //step alias
                    null,
                    //step title value
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

                return this;
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