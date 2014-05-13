jQuery(document).ready(function() {
	var form = jQuery('#demoSignUp');
	/* Global variables */
	var fullname = jQuery('#fullname');
	var emailBlankIsOk = false;
	var sitenameBlankIsOk = false;

	if(form)
	{
		
		fullname.validate = function(){
			var fullnameVal = jQuery('#fullname').val();
			//change regex to accept Full Name
			var regex = /^[a-z\sA-Z]*$/i;
			var match = regex.exec(fullnameVal);
            if (match == null || fullnameVal == "Full Name" ) 
            {
            	jQuery('#fullnameHelp').html('Use only letters for your Full Name.');
				jQuery('#fullnameHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
				
            	fullname.addClass('validationRed');
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
					fullname.addClass('validationRed');
					fullnameIsOk = 0;
					return false;
				}
				else
				{
					fullname.removeClass('validationRed');
					fullname.addClass('validationGreen');
					jQuery('#fullnameHelp').html('');
					jQuery('#fullnameHelp').css('display','none');
					jQuery('#hidden_error').css('visibility','hidden');
					fullnameIsOk=0;
					return true;	
				}
				
			}
        }
	    fullname.focus(function(){
		   if (fullname.val().trim() =='Full Name') this.value = ''; 
	    });
	    fullname.blur(function(){
		   if (fullname.val().trim() =='') this.value = 'Full Name';
		   fullname.validate();
	    });
		
		var sitename = jQuery('#sitename');
		sitename.validate = function(){
			
			var sitenameVal = jQuery('#sitename').val();
			if (sitenameVal == "Domain Name")
			{
				sitename.addClass('validationRed');
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
				sitename.addClass('validationRed');
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
                            sitename.addClass('validationRed');
                            sitenameBlankIsOk = false;
                        } else if (response == 'false') {
                        	sitename.removeClass('validationRed');
                            sitename.addClass('validationGreen');
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
                            sitename.addClass('validationRed');
                            sitenameBlankIsOk = false;
                        }
                    },
                	error: function (){
                		jQuery('#sitenameHelp').html('Error to check domain existence, try again.');
                        jQuery('#sitenameHelp').css('display','block');
                        jQuery('#hidden_error').css('visibility','visible');
                        jQuery('#cursorBlink').html('');
                        sitename.addClass('validationRed');
                        sitenameBlankIsOk = false;
                	}
                });
			}
						
			return sitenameBlankIsOk;
		}
		sitename.focus(function(){
		   if (sitename.val().trim()=='Site Name') this.value = ''; 
	    });
		sitename.blur(function(){
			if (sitename.val().trim()=='') this.value = 'Site Name'; 
			sitename.validate()
		});


		sitename.blankvalidate = function(){
			var sitenameVal = jQuery('#sitename').val().trim();
			if (sitenameVal == "Domain Name")
			{
				sitename.addClass('validationRed');
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
				sitename.addClass('validationRed');
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
                            jQuery('#cursorBlink').html('');
                            jQuery('#sitenameHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            sitename.addClass('validationRed');
                            sitenameBlankIsOk = false;
                        } else if (response == 'false') {
                            sitename.addClass('validationGreen');
                            jQuery('#cursorBlink').html(sitenameVal);
                            jQuery('#sitenameHelp').html('');
                            jQuery('#sitenameHelp').css('display','none');
                            jQuery('#hidden_error').css('visibility','hidden');
                            sitenameBlankIsOk = true;
                        } else {
                            jQuery('#sitenameHelp').html(response);
                            jQuery('#cursorBlink').html('');
                            jQuery('#sitenameHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            sitename.addClass('validationRed');
                            sitenameBlankIsOk = false;
                        }
                    },
                    error: function (){
                    	jQuery('#sitenameHelp').html('Our service cant check domain, please try again.');
                        jQuery('#cursorBlink').html('');
                        jQuery('#sitenameHelp').css('display','block');
                        jQuery('#hidden_error').css('visibility','visible');
                        sitename.addClass('validationRed');
                        sitenameBlankIsOk = false;
                    }
                });
			}
		};
		
		/* E-mail Validation */
		var email = jQuery('#email');
		email.validate = function(){
			var emailVal = email.val();
			var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
			var match = regex.exec(emailVal);

			if (match == null) {
				var emailValResult = jQuery('#email');
				email.addClass('validationRed');
				emailBlankIsOk = false;
				jQuery('#emailHelp').html('Invalid email provided.');
				jQuery('#emailHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
			}   
			else{
                var emailValResult = jQuery('#email');
                jQuery.ajax({
                    url: 'index.php?option=com_demoregister&task=checkEmail&format=json',
                    data: {email: emailVal},
                    success: function (response) {
                        if (response == 'true') {
                            jQuery('#emailHelp').html('email already exists, please choose another one.');
                            jQuery('#emailHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            email.addClass('validationRed');
                            emailBlankIsOk = false;
                        } else if (response == 'false') {
                            email.addClass('validationGreen');
                            jQuery('#emailHelp').html('');
                            jQuery('#emailHelp').css('display','none');
                            jQuery('#hidden_error').css('visibility','hidden');
                            emailBlankIsOk = true;
                            console.log(emailBlankIsOk);
                        } else {
                            jQuery('#emailHelp').html(response);
                            jQuery('#emailHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            email.addClass('validationRed');
                            emailBlankIsOk = false;
                        }
                        console.log(emailBlankIsOk);
                    },
                    error: function (){
                    	jQuery('#emailHelp').html('Our service cant validate email, please try again.');
                        jQuery('#emailHelp').css('display','block');
                        jQuery('#hidden_error').css('visibility','visible');
                        email.addClass('validationRed');
                        emailBlankIsOk = false;
                    }
                });
			}
			return emailBlankIsOk;
		};
		email.focus(function(){
			if (email.val().trim()=='Email Address') this.value='';
		})
		email.blur(function(){
			if (email.val().trim()=='') this.value='Email Address';
			email.validate()
		});

		email.blankvalidate = function(){
			var emailVal = email.val().trim();
			var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
			var match = regex.exec(emailVal);

			if (match == null) {
				var emailValResult = jQuery('#email');
				email.addClass('validationRed');
				jQuery('emailHelp').html('Invalid email provided.');
				jQuery('emailHelp').css('display','block');
				jQuery('#hidden_error').css('visibility','visible');
				emailBlankIsOk = false;
			}   
			else{
                var emailValResult = jQuery('#email');
                jQuery.ajax({
                    url: 'index.php?option=com_demoregister&task=checkEmail&format=json',
                    data: {email: emailVal},
                    success: function (response) {
                        if (response == 'true') {
                            jQuery('#emailHelp').html('email already exists, please choose another one.');
                            jQuery('#emailHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            email.addClass('validationRed');
                            emailBlankIsOk = false;
                        } else if (response == 'false') {
                            email.addClass('validationGreen');
                            jQuery('#emailHelp').html('');
                            jQuery('#emailHelp').css('display','none');
                            jQuery('#hidden_error').css('visibility','hidden');
                            emailBlankIsOk = true;
                        } else {
                            jQuery('#emailHelp').html(response);
                            jQuery('#emailHelp').css('display','block');
                            jQuery('#hidden_error').css('visibility','visible');
                            email.addClass('validationRed');
                            emailBlankIsOk = false;
                        }
                    },
                    error: function (){
                    	jQuery('#emailHelp').html('Our service cant validate email, please try again.');
                        jQuery('#emailHelp').css('display','block');
                        jQuery('#hidden_error').css('visibility','visible');
                        email.addClass('validationRed');
                        emailBlankIsOk = false;
                    }
                });
			}
		};

			
		/* Country Validation */
		var country = jQuery('#country');
		country.validate = function(){
			var countryVal = country.val();
			if (countryVal == null || countryVal == "empty" ) { 
	        	jQuery('#selectcountry').addClass('select validationRed');
	        	jQuery('#countryHelp').html('Select a country.');
				jQuery('#countryHelp').css('display','block');
	        	jQuery('#hidden_error').css('visibility','visible');
				countryIsOk = -1;
				return false;
			}
			else
			{	
				jQuery('#selectcountry').addClass('select validationGreen');
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
					recaptchaField.addClass('validationRed');
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
				var phonenumberVal = phonenumber.value;
				var regex = /^$|(^[0-9\+\(\)\+\- ]{6,25}$)/;
				var match = regex.exec(phonenumberVal);
	
				if (phonenumberVal != 'PhoneNumber(Optional)') 
				{
					if (match == null) 
					{
						var phonenumberValResult = jQuery('#phonenumberValResult');
						phonenumber.addClass('validationRed');
						jQuery('#phonenumberHelp').html('6 chars minimum, 25 maximum. Only numbers');
						jQuery('#phonenumberHelp').css('display','block');
						jQuery('#hidden_error').css('visibility','visible');
						return false;
					}   
					else
					{
						phonenumber.addClass('');
						jQuery('#phonenumberHelp').html('');
						jQuery('#phonenumberHelp').css('display','none');
						jQuery('#hidden_error').css('visibility','hidden');
						return true;
					}
				}else{
					var phonenumberValResult = jQuery('#phonenumberValResult');
					phonenumber.addClass('');
					if (phonenumberVal != 'PhoneNumber(Optional)')
						jQuery('#phonenumber').value = phonenumberVal.replace(/\+/g, '').replace(/-/g, '').replace(/ /g, '');
					var phonenumberValResult = jQuery('phonenumberValResult');
					phonenumber.addClass('validationRed');
					jQuery('#phonenumberHelp').html('6 chars minimum, 25 maximum. Only numbers');
					jQuery('#phonenumberHelp').css('display','block');
					jQuery('#hidden_error').css('visibility','visible');
					return true;
				}
			};
		
			phonenumber.focus(function(){
				if (phonenumber.val().trim()=='PhoneNumber(Optional)') this.value='';
			});
			phonenumber.blur(function(){
				if (phonenumber.val().trim()=='') this.value = 'PhoneNumber(Optional)';
				phonenumber.validate();
			});
		}
		
		city = jQuery('#city');
		if (city.length>0) {
			city.focus(function(){
				if (city.val().trim()=='City(Optional)') this.value='';
			});
			city.blur(function(){
				if (city.val().trim()=='') this.value = 'City(Optional)';
			});
		}
		state = jQuery('#state');
		if (state.length>0) {
			state.focus(function(){
				if (state.val().trim()=='State(Optional)') this.value='';
			});
			state.blur(function(){
				if (state.val().trim()=='') this.value = 'State(Optional)';
			});
		}
		address = jQuery('#address');
		if (address.length>0) {
			address.validate = function(){
				var adrVal = address.val();
				//change regex to accept Full Name
				var regex = /^[a-z\sA-Z]*$/i;
				var match = regex.exec(adrVal);
	            if (match == null || match == "Address" ) 
	            {
	            	jQuery('#addressHelp').html('Use only letters for your Address.');
					jQuery('#addressHelp').css('display','block');
					jQuery('#hidden_error').css('visibility','visible');
					
	            	address.addClass('validationRed');
					addressOk=-1;
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
						address.addClass('validationRed');
						addressOk = 0;
						return false;
					}
					else
					{
						fullname.addClass('validationGreen');
						jQuery('#addressHelp').html('');
						jQuery('#addressHelp').css('display','none');
						jQuery('#hidden_error').css('visibility','hidden');
						addressOk=0;
						return true;	
					}
					
				}
			
	        }
        
			address.focus(function(){
				if (address.val().trim()=='Address') this.value='';
			});
			address.blur(function(){
				if (address.val().trim()=='') this.value = 'Address';
				address.validate();
			});
		}
		address2 = jQuery('#address2');
		if (address2.length>0) {
		address2.focus(function(){
			if (address2.val().trim()=='Address 2(Optional)') this.value='';
		});
		address2.blur(function(){
			if (address2.val().trim()=='') this.value = 'Address 2(Optional)';
		});
		}
		postCode = jQuery('#postcode');
		if (postCode.length>0) {
			postCode.validate = function(){
				var phonenumberVal = postCode.val();
				var regex = /^[0-9]([0-9]|-(?!-))+$/;
				var match = regex.exec(phonenumberVal);
	
				if (phonenumberVal != 'Post Code') 
				{
					if (match == null) 
					{
						postCode.removeClass('validationGreen').addClass('validationRed');
						jQuery('#postcodeHelp').html('Only numbers and "-"');
						jQuery('#postcodeHelp').css('display','block');
						jQuery('#hidden_error').css('visibility','visible');
						return false;
					}   
					else
					{
						postCode.removeClass('validationRed').addClass('validationGreen');
						jQuery('#postcodeHelp').html('');
						jQuery('#postcodeHelp').css('display','none');
						jQuery('#hidden_error').css('visibility','hidden');
						return true;
					}
				}else{
					if (phonenumberVal != 'Post Code')
						jQuery('#postcode').value = phonenumberVal.replace(/\+/g, '').replace(/-/g, '').replace(/ /g, '');
					postCode.removeClass('validationGreen').addClass('validationRed');
					jQuery('#postcodeHelp').html('Only numbers and "-"');
					jQuery('#postcodeHelp').css('display','block');
					jQuery('#hidden_error').css('visibility','visible');
					return true;
				}
			};
		
			postCode.focus(function(){
				if (this.value=='Post Code') this.value='';
			});
			postCode.blur(function(){
				if (this.value=='') this.value = 'Post Code';
				postCode.validate()
			});
		}

        // add on change to application field
        jQuery('#application').change(function() {
            var family = jQuery('#application').val();
            var app = family.split('-');
            jQuery.ajax({
                dataType: 'json',
                url: 'index.php?option=com_demoregister&format=json&task=listdatasets&application='+app[0]+'&family='+family,
                beforeSend: function( xhr, settings ) {
                    jQuery('#selectdataset').addClass('disabled').text('Loading...');
                    jQuery('#dataset').attr('disabled','disabled').find('option')
                        .remove()
                        .end()
                        .append('<option value="">Loading...</option>')
                        .val('')
                },
                success: function (response) {
                    jQuery('#selectdataset').removeClass('disabled').text('Default Installation');
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
                }
            });
        });

        jQuery('#dataset').change(function(){
            jQuery('#selectdataset').text(jQuery(this).find("option:selected").text());
        });

        jQuery('#application').trigger('change');
		
		jQuery('#demoForm_j25').submit(function(){
			var tos = jQuery('#tos');
			var fullnameOk = fullname.validate();
			sitename.blankvalidate();
			email.blankvalidate();
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
			var demosecureOK = true
			var tosOK = true

			if (checkcaptcha === true)
			{
				demosecureOK = recaptchaField.validate();	
			}

			if(!tos.is(':checked'))
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
			
			if (fullnameOk === false || postcodeOk == false || emailBlankIsOk == false || sitenameBlankIsOk == false ||  phonenumberOk == false || countryOK === false || demosecureOK === false || tosOK === false)
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
		
			return true;
		});
		
	}
});