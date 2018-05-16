define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'jquery',
		'mage/storage',
        'mage/translate',
		'mage/url',		
		'Magento_Checkout/js/model/full-screen-loader',
		'Magento_Customer/js/model/customer',
		'Magento_Checkout/js/model/authentication-messages',
        'mage/validation'		
    ],
    function (ko,
              Component,
              _,
              stepNavigator,
              $,
			  storage,
			  $t,
			  urlBuilder,
			  fullScreenLoader,
			  customer,
			  messageContainer
    ) {
        'use strict';
		//var visiblestep ='';
		var checkoutConfig = window.checkoutConfig;
		
        /**
        * check-login - is the name of the component's .html template
        */
        return Component.extend({
            defaults: {
                template: 'Digicel_Login/login-form',
                isLoading: ko.observable(false)
            },			
			isCustomerLoggedIn: customer.isLoggedIn,
			forgotPasswordUrl: checkoutConfig.forgotPasswordUrl,
            //add here your logic to display step,			
			isVisible: (isCustomerLoggedIn == true) ? ko.observable(false) : ko.observable(true),
			
            //step code will be used as step content id in the component template
            stepCode: 'login',
            //step title value
            stepTitle: 'Login / Register',		

            /**
            *
            * @returns {*}
            */
            initialize: function () {
                this._super();				
			    //if (isCustomerLoggedIn == false){
					stepNavigator.registerStep(
						this.stepCode,
						//step alias
						null,
						this.stepTitle,
						//observable property with logic when display step or hide step
						this.isVisible,

						_.bind(this.navigate, this),

						/**
						* sort order value
						* 'sort order value' < 10: step displays before shipping step;
						* 10 < 'sort order value' < 20 : step displays between shipping and payment step
						* 'sort order value' > 20 : step displays after payment step
						*/
						5
					);
			    //}
				//this.createaccount();
				
                return this;
            },

            /**
            * The navigate() method is responsible for navigation between checkout step
            * during checkout. You can add custom logic, for example some conditions
            * for switching to your custom step
            */
			//submit form with all validation check
			 onSubmit: function (loginForm) {
				 $('.login-error').hide();
				var loginData = {},
                formDataArray = $(loginForm).serializeArray();
                formDataArray.forEach(function (entry) {
                    loginData[entry.name] = entry.value;
                });
	            $('.login-error').hide();
                if ($(loginForm).validation() &&
                    $(loginForm).validation('isValid')
                ) {
					fullScreenLoader.startLoader();
                    $.ajax({
                        url: urlBuilder.build('logincheckout/login/loginPost'),
                        data: loginData,                       
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (!response.errors) {
                                $('.login-error').hide();                                	
                                fullScreenLoader.stopLoader();
                                window.location.reload();
                            }else{
                                $('.login-error').show();
                                fullScreenLoader.stopLoader();
                            }
                        }
                    }); 
					//this.navigateToNextStep()
				  }
               
            },			
			loginaccount:function(){				
				//alert('San Login');
				$('.checkout-register').hide();			
				fullScreenLoader.startLoader();
				$('.login-block').show();
				fullScreenLoader.stopLoader();	
			},
			getLogoutUrl:function(){
			   var urlPost = urlBuilder.build('customer/account/logout');
			   return urlPost;
			},
			createaccount:function(){				
				$('.login-block').hide();			
				fullScreenLoader.startLoader();
				var urlPost = urlBuilder.build('logincheckout/login/login');
				$.ajax({
                            dataType: 'json',
                            url: urlPost,
							async: false,
                            type: 'post',
                            success: function(result)
                            {	
							    //alert('got success');
							    $('.login-block').hide();
							    $('.checkout-register').show();
								fullScreenLoader.stopLoader();
								$('.register-block').html(result.html);
								$('#signUp').html(result.html);
								$('.form-create-account').attr('action', '#');
								return true;
                            },
                            error: function(){
								fullScreenLoader.stopLoader();
                                return false;
                            }
                        }); 
				
			},
			createpost:function(registerForm){
				//alert('data');
				var registerData = {},
                formDataArray = $('#form-validate').serializeArray();
                formDataArray.forEach(function (entry) {
                    registerData[entry.name] = entry.value;
                });
	
                if ($('#form-validate').validation() &&
                    $('#form-validate').validation('isValid')
                ) {
					fullScreenLoader.startLoader();
					var urlPost = urlBuilder.build('logincheckout/login/createpost');
					$.ajax({
                            dataType: 'json',
                            url: urlPost,
							async: false,
                            type: 'post',
							data:registerData,
                            success: function(result){
								fullScreenLoader.stopLoader();
								if(result.valid == 1){
									$('.register-error').hide();
									location.reload();
								}else{
									$('.register-error').show();
									$('.register-error').html(result.message);
								}												
							    return true;
                            },
                            error: function(){
								fullScreenLoader.stopLoader();
                                return false;
                            }
                        }); 
				
				  }
			},
			
            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {
                var self = this;
                self.isVisible(true);
            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {			
                stepNavigator.next();
            },
			
			getCustomerPrefix:function(){
				if(customer.customerData.prefix != null){
					$("#customer-prefix").prop("readonly", true);
				}
				return customer.customerData.prefix;
			},
			getCustomerFirstname:function(){
				if(customer.customerData.firstname != null){
					$("#customer-firstname").prop("readonly", true);
				}
				return customer.customerData.firstname;
			},
			getCustomerLastname:function(){
				if(customer.customerData.lastname != null){
					$("#customer-lastname").prop("readonly", true);
				}
				return customer.customerData.lastname;
			},
			getCustomerEmail:function(){
				if(customer.customerData.email != null){
					$("#customer-email").prop("readonly", true);
				}
				return customer.customerData.email;
			},
            
        });
    }
);