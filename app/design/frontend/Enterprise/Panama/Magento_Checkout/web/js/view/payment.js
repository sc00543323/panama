/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/model/payment/method-converter',
    'Magento_Checkout/js/action/get-payment-information',
    'Magento_Checkout/js/model/checkout-data-resolver',
	'mage/url',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/translate'
], function (
    $,
    _,
    Component,
    ko,
    quote,
    stepNavigator,
    paymentService,
    methodConverter,
    getPaymentInformation,
    checkoutDataResolver,
	urlBuilder,
    fullScreenLoader,
    $t
) {
    'use strict';

    /** Set payment methods to collection */
    paymentService.setPaymentMethods(methodConverter(window.checkoutConfig.paymentMethods));

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/payment',
            activeMethod: ''
        },
        isVisible: ko.observable(quote.isVirtual()),
        quoteIsVirtual: quote.isVirtual(),
        isPaymentMethodsAvailable: ko.computed(function () {
            return paymentService.getAvailablePaymentMethods().length > 0;
        }),

        /** @inheritdoc */
        initialize: function () {
            this._super();
            checkoutDataResolver.resolvePaymentMethod();
            stepNavigator.registerStep(
                'payment',
                null,
                $t('Payments'),
                this.isVisible,
                _.bind(this.navigate, this),
                20
            );

            return this;
        },
		
		downloadbilling:function(){               
                fullScreenLoader.startLoader();
                var urlPost = urlBuilder.build('portin/Terms/Downloadbilling');                
                window.location.href = urlPost;
                fullScreenLoader.stopLoader();
            },
			
		downloadgeneral:function(){               
                fullScreenLoader.startLoader();
                var urlPost = urlBuilder.build('portin/Terms/Downloadgeneral');                
                window.location.href = urlPost;
                fullScreenLoader.stopLoader();
            },
			
		generalFile:function(){
			var urlPost = urlBuilder.build('portin/Terms/ViewFile');
			var fname = null;
			$.ajax({
				url: urlPost,
				async: false,
				type: 'post',
				data: {"type": "general"},
				success: function(result)
				{	
					fname = urlBuilder.build(result);
				}
			}); 
			return fname;
		},
		billingFile:function(){
			var urlPost = urlBuilder.build('portin/Terms/ViewFile');
			var fname = null;
			$.ajax({
				url: urlPost,
				async: false,
				type: 'post',
				data: {"type": "billing"},
				success: function(result)
				{	
					fname = urlBuilder.build(result);
				}
			}); 
			return fname;
		},

        /**
         * Navigate method.
         */
        navigate: function () {
            var self = this;

            getPaymentInformation().done(function () {
                self.isVisible(true);
            });
        },
		

        /**
         * @return {*}
         */
        getFormKey: function () {
            return window.checkoutConfig.formKey;
        }
    });
});
