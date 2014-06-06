
var mid = 0;
var caFormDefaultValues = {};
jQuery(document).ready(function() {
	var form = jQuery('#demoSignUp');
	/* Global variables */
	var fullname = jQuery('#fullname');
	var emailBlankIsOk = false;
	var sitenameBlankIsOk = false;
	var registeredUser = false;
	var formCanSubmit = false;

	if(form)
	{
		
		fullname.validate = function(){
			var fullnameVal = jQuery('#fullname').val();
			//change regex to accept Full Name
			var regex = /^[a-z\sA-Z]*$/i;
			var match = regex.exec(fullnameVal);
            if (match == null || fullnameVal == caFormDefaultValues[jQuery('#fullname').attr('id')]) 
            {
            	jQuery('#fullnameHelp').html('Use only letters for your Full Name.');
				jQuery('#fullnameHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
				
            	fullname.removeClass('validationGreen').addClass('validationRed');
				fullnameIsOk=-1;
				return false;
			}
			else
			{
				var chkstring = fullnameVal.split(" ");	
				if(chkstring.length <= 1)
				{
					jQuery('#fullnameHelp').html('Minimum 2 words for Full Name.');
					jQuery('#fullnameHelp').css('display','block');
					jQuery('#hidden_error').css('visibility','visible');
					fullname.removeClass('validationGreen').addClass('validationRed');
					fullnameIsOk = 0;
					return false;
				}
				else
				{
					fullname.removeClass('validationRed').addClass('validationGreen');
					jQuery('#fullnameHelp').html('');
					jQuery('#fullnameHelp').css('display','none');
					jQuery('#hidden_error').css('visibility','hidden');
					fullnameIsOk=0;
					return true;	
				}
				
			}
        }
	    fullname.focus(function(){
		   if (fullname.val().trim() == caFormDefaultValues[this.id]) this.value = ''; 
	    });
	    fullname.blur(function(){
		   if (fullname.val().trim() =='') this.value = caFormDefaultValues[this.id];
		   fullname.validate();
	    });
		
		var sitename = jQuery('#sitename');
		sitename.validate = function(){
			
			var sitenameVal = jQuery('#sitename').val();
			if (sitenameVal == caFormDefaultValues[jQuery('#sitename').attr('id')])
			{
				sitename.removeClass('validationGreen').addClass('validationRed');
				jQuery('#hidden_error').css('visibility','visible');
				return false;
			} 
			var regex = /^[a-z0-9][a-z0-9\-]*[a-z0-9]$/i;
			var match = regex.exec(sitenameVal);
			if (match == null) {
				var sitenameValResult = jQuery('#sitename');
				jQuery('#sitenameHelp').html('Please delete spaces/dots in your domain name.');
				jQuery('#sitenameHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
				sitename.removeClass('validationGreen').addClass('validationRed');
				sitenameBlankIsOk = false;
			}   
			else{
                var sitenameValResult = jQuery('#sitename');
                jQuery.ajax({
                    url: 'index.php?option=com_demoregister&task=checkDomain&format=json',
                    data: {domain: sitenameVal},
                    success: function (response) {
                        if (response == 'true') {
                            jQuery('#sitenameHelp').html('domain already exists, please choose another one.');
                            jQuery('#sitenameHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            jQuery('#cursorBlink').html('');
                            sitename.removeClass('validationGreen').addClass('validationRed');
                            sitenameBlankIsOk = false;
                        } else if (response == 'false') {
                            sitename.removeClass('validationRed').addClass('validationGreen');
                            jQuery('#cursorBlink').html(jQuery('#sitename').val().trim());
                            jQuery('#sitenameHelp').html('');
                            jQuery('#sitenameHelp').css('display','none');
                            jQuery('#hidden_error').css('visibility','hidden');
                            sitenameBlankIsOk = true;
                        } else {
                            jQuery('#sitenameHelp').html(response);
                            jQuery('#cursorBlink').html('');
                            jQuery('#sitenameHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            sitename.removeClass('validationGreen').addClass('validationRed');
                            sitenameBlankIsOk = false;
                        }
                    },
                	error: function (){
                		jQuery('#sitenameHelp').html('Error to check domain existence, try again.');
                        jQuery('#sitenameHelp').css('display','block');
                        jQuery('#hidden_error').css('visibility','visible');
                        jQuery('#cursorBlink').html('');
                        sitename.removeClass('validationGreen').addClass('validationRed');
                        sitenameBlankIsOk = false;
                	}
                });
			}
						
			return sitenameBlankIsOk;
		}
		sitename.focus(function(){
		   if (sitename.val().trim()==caFormDefaultValues[this.id]) this.value = ''; 
	    });
		sitename.blur(function(){
			if (sitename.val().trim()=='') this.value = caFormDefaultValues[this.id];
		});
		sitename.change(function(){
			sitename.validate()
		});
		
		/* E-mail Validation */
		var email = jQuery('#email');
		email.validate = function(){
			var emailVal = email.val();
			var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
			var match = regex.exec(emailVal);

			if (match == null) {
				var emailValResult = jQuery('#email');
				email.removeClass('validationGreen').addClass('validationRed');
				emailBlankIsOk = false;
				jQuery('#emailHelp').html('Invalid email provided.');
				jQuery('#emailHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
				email.toggleValidation(false);
			}   
			else{
                var emailValResult = jQuery('#email');
                jQuery.ajax({
                    url: 'index.php?option=com_demoregister&task=checkEmail&format=json',
                    data: {email: emailVal},
                    success: function (response) {
                    	registeredUser = response;
                        if (response == 'true') {
                            email.removeClass('validationRed').addClass('validationGreen');
                            jQuery('#emailHelp').html('');
                            jQuery('#emailHelp').css('display','none');
                            jQuery('#hidden_error').css('visibility','hidden');
                            emailBlankIsOk = true;
                            email.toggleValidation(true);
                        } else if (response == 'false') {
                            email.removeClass('validationRed').addClass('validationGreen');
                            jQuery('#emailHelp').html('');
                            jQuery('#emailHelp').css('display','none');
                            jQuery('#hidden_error').css('visibility','hidden');
                            emailBlankIsOk = true;
                        	email.toggleValidation(false);
                        }
                    },
                    error: function (){
                    	jQuery('#emailHelp').html('Our service cant validate email, please try again.');
                        jQuery('#emailHelp').css('display','block');
                        jQuery('#hidden_error').css('visibility','visible');
                        email.removeClass('validationGreen').addClass('validationRed');
                        emailBlankIsOk = false;
                    }
                });
			}
			return emailBlankIsOk;
		};
		email.focus(function(){
			if (email.val().trim()==caFormDefaultValues[this.id]) this.value='';
		})
		email.blur(function(){
			if (email.val().trim()=='') this.value=caFormDefaultValues[this.id];
		});
		email.change(function(){
			email.validate();
		});

		email.toggleValidation = function(validate) {
			if (validate) {
				fullname.parent().addClass('dn');
			} else {
				fullname.parent().removeClass('dn');
			}
			fullnameIsOk = validate;
			if (validate) {
				country.parent().addClass('dn');
			} else {
				country.parent().removeClass('dn');
			}
			countryIsOk = validate;
			
			if (phonenumber.length>0) {
				if (validate) {
					phonenumber.parent().addClass('dn');
				} else {
					phonenumber.parent().removeClass('dn');
				}
				phonenumberOk = validate;
			}
			if (city.length>0) { 
				if (validate) {
					city.parent().addClass('dn');
				} else {
					city.parent().removeClass('dn');
				}
			}
			if (state.length>0) {
				if (validate) {
					state.parent().addClass('dn');
				} else {
					state.parent().removeClass('dn');
				}
			}
			if (address.length>0) {
				if (validate) {
					address.parent().addClass('dn');
				} else {
					address.parent().removeClass('dn');
				}
				addressOk = validate;
			}
			if (address2.length>0) {
				if (validate) {
					address2.parent().addClass('dn');
				} else {
					address2.parent().removeClass('dn');
				}
			}
			if (postCode.length>0) {
				postcodeOk = validate;
				if (validate) {
					postCode.parent().addClass('dn');
				} else {
					postCode.parent().removeClass('dn');
				}
			}
		};
			
		/* Country Validation */
		var country = jQuery('#country');
		country.validate = function(){
			var countryVal = jQuery('#country').val();
			if (countryVal == null || countryVal == "empty" ) { 
	        	jQuery('#selectcountry').removeClass('validationGreen').addClass('select validationRed');
	        	jQuery('#countryHelp').html('Select a country.');
				jQuery('#countryHelp').css('display','block');
	        	jQuery('#hidden_error').css('visibility','visible');
				countryIsOk = -1;
				return false;
			}
			else
			{	
				jQuery('#selectcountry').removeClass('validationRed').addClass('select validationGreen');
				jQuery('#countryHelp').html('');
				jQuery('#countryHelp').css('display','none');
				jQuery('#hidden_error').css('visibility','hidden');
				countryIsOk = 0;
				return true;
			}			
		};
		country.blur(country.validate);

		/* Captchs Validation */
		var checkcaptcha = false
		if (typeof jQuery('#recaptcha_response_field') !== "undefined" && jQuery('#recaptcha_response_field') != null)
		{
			var recaptchaField = jQuery('#recaptcha_response_field');
			checkcaptcha = true

			recaptchaField.validate = function()
			{
				var captchaFieldVal = recaptchaField.value;
				
				if (captchaFieldVal == '')
				{
					recaptchaField.removeClass('validationGreen').addClass('validationRed');
					jQuery('#recpatchareponseHelp').html('Enter Captcha value!');
					jQuery('#recpatchareponseHelp').css('display','block');
					jQuery('#hidden_error').css('visibility','visible');
					return false;
				}
				else
				{
					recaptchaField.addClass('');
					jQuery('#recpatchareponseHelp').html('');
					jQuery('#recpatchareponseHelp').css('display','none');
					jQuery('#hidden_error').css('visibility','hidden');
					return true;
				}
			};

			recaptchaField.blur(recaptchaField.validate);
		}
		
		/* Phone Number Validation */
		var phonenumber = jQuery('#phonenumber');
		if (phonenumber.length>0) {
			phonenumber.validate = function(){
				var phonenumberVal = jQuery('#phonenumber').val().trim();
				var regex = /(^[0-9\+\(\)\+\-\s]{6,25}$)/;
				var match = regex.exec(phonenumberVal);
	
				if (phonenumberVal != caFormDefaultValues[jQuery('#phonenumber').attr('id')]) 
				{
					console.log(match);
					if (match == null) 
					{
						var phonenumberValResult = jQuery('#phonenumberValResult');
						phonenumber.removeClass('validationGreen').addClass('validationRed');
						jQuery('#phonenumberHelp').html('6 chars minimum, 25 maximum. Only numbers');
						jQuery('#phonenumberHelp').css('display','block');
						jQuery('#hidden_error').css('visibility','visible');
						return false;
					}   
					else
					{
						phonenumber.removeClass('validationRed').addClass('validationGreen');
						jQuery('#phonenumberHelp').html('');
						jQuery('#phonenumberHelp').css('display','none');
						jQuery('#hidden_error').css('visibility','hidden');
						return true;
					}
				}
			};
		
			phonenumber.focus(function(){
				if (phonenumber.val().trim()==caFormDefaultValues[this.id]) this.value='';
			});
			phonenumber.blur(function(){
				if (phonenumber.val().trim()=='') this.value = caFormDefaultValues[this.id];
				phonenumber.validate();
			});
		}
		
		city = jQuery('#city');
		if (city.length>0) {
			city.focus(function(){
				if (city.val().trim()==caFormDefaultValues[this.id]) this.value='';
			});
			city.blur(function(){
				if (city.val().trim()=='') this.value = caFormDefaultValues[this.id];
			});
		}
		state = jQuery('#state');
		if (state.length>0) {
			state.focus(function(){
				if (state.val().trim()==caFormDefaultValues[this.id]) this.value='';
			});
			state.blur(function(){
				if (state.val().trim()=='') this.value = caFormDefaultValues[this.id];
			});
		}
		address = jQuery('#address');
		if (address.length>0) {
			address.validate = function(){
				var adrVal = jQuery('#address').val().trim();
				var regex = /^[a-z\sA-Z\/,0-9]*$/;
				var match = regex.exec(adrVal);
	            if (match == null || match == caFormDefaultValues[jQuery('#address').attr('id')] ) 
	            {
	            	jQuery('#addressHelp').html('Use only letters for your Address.');
					jQuery('#addressHelp').css('display','block');
					jQuery('#hidden_error').css('visibility','visible');
					
	            	address.removeClass('validationGreen').addClass('validationRed');
					addressOk=false;
					return false;
				}
				else
				{
					var chkstring = adrVal.split(" ");	
					if(chkstring.length <= 1)
					{
						jQuery('#addressHelp').html('Minimum 2 words for Address');
						jQuery('#addressHelp').css('display','block');
						jQuery('#hidden_error').css('visibility','visible');
						address.removeClass('validationGreen').addClass('validationRed');
						addressOk = 0;
						return false;
					}
					else
					{
						address.removeClass('validationRed').addClass('validationGreen');
						jQuery('#addressHelp').html('');
						jQuery('#addressHelp').css('display','none');
						jQuery('#hidden_error').css('visibility','hidden');
						addressOk=true;
						return true;	
					}
					
				}
			
	        }
        
			address.focus(function(){
				if (address.val().trim()==caFormDefaultValues[this.id]) this.value='';
			});
			address.blur(function(){
				if (address.val().trim()=='') this.value = caFormDefaultValues[this.id];
				address.validate();
			});
		}
		address2 = jQuery('#address2');
		if (address2.length>0) {
		address2.focus(function(){
			if (address2.val().trim()==caFormDefaultValues[this.id]) this.value='';
		});
		address2.blur(function(){
			if (address2.val().trim()=='') this.value = caFormDefaultValues[this.id];
		});
		}
		postCode = jQuery('#postcode');
		if (postCode.length>0) {
			postCode.validate = function(){
				var postCodeVal = jQuery('#postcode').val().trim();
				var regex = /(^[0-9]{6,25}$)/;
				var match = regex.exec(postCodeVal);
	
				if (postCodeVal != caFormDefaultValues[jQuery('#postcode').attr('id')]) 
				{
					if (match == null) 
					{
						postCode.removeClass('validationGreen').addClass('validationRed');
						jQuery('#postcodeHelp').html('6 chars minimum, 25 maximum. Only numbers');
						jQuery('#postcodeHelp').css('display','block');
						jQuery('#hidden_error').css('visibility','visible');
						return false;
					}   
					else
					{
						phonenumber.removeClass('validationRed').addClass('validationGreen');
						jQuery('#postcodeHelp').html('');
						jQuery('#postcodeHelp').css('display','none');
						jQuery('#hidden_error').css('visibility','hidden');
						return true;
					}
				}
			};
		
			postCode.focus(function(){
				if (this.value==caFormDefaultValues[this.id]) this.value='';
			});
			postCode.blur(function(){
				if (this.value=='') this.value = caFormDefaultValues[this.id];
				postCode.validate()
			});
		}

        // add on change to application field
        jQuery('#application').change(function() {
            var family = jQuery('#application').val();
            var app = family.split('-');
            jQuery.ajax({
                dataType: 'json',
                url: 'index.php?option=com_demoregister&format=json&task=listdatasets&application='+app[0]+'&family='+family+'&mid='+mid,
                beforeSend: function( xhr, settings ) {
                    //jQuery('#selectdataset').addClass('disabled').text('Loading...');
                    jQuery('#dataset').attr('disabled','disabled').find('option')
                        .remove()
                        .end()
                        .append('<option value="">Loading...</option>')
                        .val('');
                    // check if chosen are in use
                    if ((jQuery().chosen != undefined)) {
                    	jQuery('#dataset').chosen({disable_search_threshold: 10}).trigger("chosen:updated");
                    }
                },
                success: function (response) {
                    //jQuery('#selectdataset').removeClass('disabled').text('Default Installation');
                    jQuery('#dataset').find('option')
                        .remove()
                        .end()
                        .append('<option value="">Default Installation</option>')
                        .val('').removeAttr('disabled');
                    if (response.length) {
                        jQuery.each(response,function(){
                            value = this.value;
                            text = this.text;
                            jQuery('#dataset').append('<option value="'+value+'">'+text+'</option>');
                        });
                    }
                    // check if chosen are in use
                    if ((jQuery().chosen != undefined)) {
                    	jQuery('#dataset').chosen({disable_search_threshold: 10}).trigger("chosen:updated");
                    }
                }
            });
        });

        jQuery('#dataset').change(function(){
            jQuery('#selectdataset').text(jQuery(this).find("option:selected").text());
        });

        jQuery('#application').trigger('change');
		
		function caCheckFormBeforeSubmit() {
			var nextStep = true;
			var fullnameOk = fullname.validate();
			var countryOK = country.validate();
			if (phonenumber.length>0) {
				var phonenumberOk = phonenumber.validate();
			} else {
				var phonenumberOk = true;
			}
			if (address.length>0) {
				var addressOk = address.validate();
			} else {
				var addressOk = true;
			}
			if (postCode.length>0) {
				var postcodeOk = postCode.validate();
			} else {
				var postcodeOk = true;
			}

			// 1 validate domain
			var regex = /^[a-z0-9][a-z0-9\-]*[a-z0-9]$/i;
			var match = regex.exec(sitename.val().trim());
			// avoid requests according with validation
			if (sitename.val().trim() == '' || match == null || sitename.val().trim() == caFormDefaultValues[jQuery('#sitename').attr('id')]) {
				var sitenameValResult = jQuery('#sitename');
				jQuery('#sitenameHelp').html('Please delete spaces/dots in your domain name.');
				jQuery('#sitenameHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
				sitename.removeClass('validationGreen').addClass('validationRed');
				sitenameBlankIsOk = false;
				nextStep = false;
			}
			// 2 - validate email
            var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
			var match = regex.exec(email.val().trim());
			if (email.val().trim() == '' || match == null || email.val().trim() == caFormDefaultValues[jQuery('#email').attr('id')]) {
				var emailValResult = jQuery('#email');
				email.removeClass('validationGreen').addClass('validationRed');
				emailBlankIsOk = false;
				jQuery('#emailHelp').html('Invalid email provided.');
				jQuery('#emailHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
				email.toggleValidation(false);
				nextStep = false;
			}

			if (!nextStep) return false;

			jQuery.ajax({
	            url: 'index.php?option=com_demoregister&task=checkDomain&format=json',
	            data: {"domain": sitename.val().trim()},
	            success: function (domainAvailable) {
	                if (domainAvailable == 'false') {
	                    // 2 - validate email
	                    var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
						var match = regex.exec(email.val().trim());
						if (email.val().trim() == '' || match == null || email.val().trim() == caFormDefaultValues[jQuery('#email').attr('id')]) {
							return false;
						}
						var emailBlankIsOk = true;
						jQuery.ajax({
		                    url: 'index.php?option=com_demoregister&task=checkEmail&format=json',
		                    data: {"email": email.val().trim()},
		                    success: function (registeredUser) {

		                        if (registeredUser == 'true') {
		                        	console.log('passa validacao');
									postcodeOk = true;
									fullnameOk = true;
									phonenumberOk = true;
									countryOK = true;
									addressOk = true;
								}

		                        // check captcha
		                        if (checkcaptcha === true)
								{
									demosecureOK = recaptchaField.validate();	
								}

								// check tos
								if(!jQuery('#tos').is(':checked'))
								{
									jQuery('#hidden_error').css('visibility','visible');
									jQuery('#termsOfServicesHelp').css('display','block');
									jQuery('#termsOfServicesHelp').html('You must agree to Terms of Service.');
									tosOK = false;
								}
								else
								{
									jQuery('#termsOfServicesHelp').html('');
									jQuery('#hidden_error').css('visibility','hidden');
									tosOK = true;
								}

								cantSubmit = (fullnameOk == false || (postcodeOk == false) || emailBlankIsOk == false || sitenameBlankIsOk == false || addressOk == false ||  (phonenumberOk == false) || countryOK == false || demosecureOK == false || tosOK == false);
								
								// check required fields
								if (cantSubmit)
								{	
									jQuery('#demoSubmit').addClass('launchBtn');
									jQuery('#demoSubmit').attr('type', 'submit');
									jQuery('#hidden_error').css('visibility','visible');
									return false;
								}

								jQuery('#hidden_error').css('visibility','hidden');
								jQuery('#demoSubmit').removeClass('launchBtn');
								jQuery('#demoSubmit').addClass('launchBtnHold');
								jQuery('#demoSubmit').attr('type', 'button');
								jQuery('#demoSubmit').attr('disabled','disabled');

								formCanSubmit = true;

								//submit
							 	document.getElementById("demoSignUp").submit();
		                    },
		                    error: function (){
		                    	jQuery('#emailHelp').html('Our service cant validate email, please try again.');
		                        jQuery('#emailHelp').css('display','block');
		                        jQuery('#hidden_error').css('visibility','visible');
		                        email.removeClass('validationGreen').addClass('validationRed');
		                    }
		                });
	                } else {
	                	jQuery('#sitenameHelp').html('domain already exists, please choose another one.');
                        jQuery('#sitenameHelp').css('display','block');
                        jQuery('#hidden_error').css('visibility','visible');
                        jQuery('#cursorBlink').html('');
                        sitename.removeClass('validationGreen').addClass('validationRed');
                        sitenameBlankIsOk = false;
	                }
	            },
	        	error: function (){
	        		jQuery('#sitenameHelp').html('Error to check domain existence, try again.');
                    jQuery('#sitenameHelp').css('display','block');
                    jQuery('#hidden_error').css('visibility','visible');
                    jQuery('#cursorBlink').html('');
                    sitename.removeClass('validationGreen').addClass('validationRed');
	        	}
	        });
			return false;
		};

		jQuery('#demoSubmit').click(function(){
        	caCheckFormBeforeSubmit();
        });
	}
});