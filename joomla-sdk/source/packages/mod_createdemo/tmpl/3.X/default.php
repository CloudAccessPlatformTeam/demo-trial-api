<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_createsite
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php

$document = JFactory::getDocument();

// Adding module external scripts
$document->addScript('modules/' . $module->module . '/assets/js/validator.js');

// Adding module external CSS files
$document->addStyleSheet('modules/' . $module->module . '/assets/css/createsite.css');

?>

<script type="text/javascript">
  mid = <?php echo $module->id; ?>;
  caFormDefaultValues = <?php echo json_encode($caFormDefaultValues); ?>;
  <?php if ($helper->captchaIsEnabled()): ?>
    var RecaptchaOptions = {
      theme : 'custom',
      custom_theme_widget: 'recaptcha_widget'
    };
  <?php endif; ?>
</script>

<div id="page" class="clearfix-ca">
  <div id="demoForm_j25" class="clearfix-ca <?php echo $params->get('moduleclass_sfx'); ?>">
    <form id="demoSignUp" name="demoSignUp" action="<?php echo JRoute::_('index.php?option=com_demoapi&amp;task=save'); ?>" method="post">
      <div id="signup_wrapper">
        <div id="signup_inwrapper">
          <div class="top-text">
            <?php echo $params->get('top_text'); ?>
          </div>
		
          <div id="hidden_error" class="alert alert-error" style="display: none;">
            Please fill all the information correctly.
          </div>
		
          <p>
            <?php
              // set attribute
              $form->setFieldAttribute('fullname','class','input-block-level');
              echo $form->getInput('fullname');
            ?>
            <span id="fullnameHelp" class="example" style="display: none;"></span>
          </p>
          
          <p>
            <?php 
              $form->setFieldAttribute('sitename','class','input-block-level');
              echo $form->getInput('sitename'); 
            ?>	
            <span class="example">URL: <span class="demo_site_name"><span id="cursorBlink"><?php if(isset($post_array['posted_sname']) && $post_array['posted_sname'] != ''){ echo $post_array['posted_sname']; } ?></span> <strong>.</strong> cloudaccess.net</span></span>
            <span id="sitenameHelp" class="example" style="display: none;"></span>
          </p>	
          		
          <p>
            <?php 
              $form->setFieldAttribute('email','class','input-block-level');
              echo $form->getInput('email'); 
            ?>		
           	<span id="emailHelp" class="example" style="display: none;"></span>
          </p>
          
          <?php if ($params->get('form_field_phonenumber')): ?>
          <p>
            <?php 
              $form->setFieldAttribute('phonenumber','class','input-block-level');
              echo $form->getInput('phonenumber'); 
            ?>
            <span class="example">Enter your phone number for a free Joomla! consultation with a Getting Started Specialist.</span>
            <span id="phonenumberHelp" class="example" style="display: none;"></span>
          </p>
          <?php endif; ?>
		
          <p>
            <?php 
              $form->setFieldAttribute('country','class','input-block-level');
              echo $form->getInput('country'); 
            ?>
            <?php // Validation icon ?>
            <?php if(isset($post_array["error_msg"]["cntry"]) && $post_array["error_msg"]["cntry"] != ""){ ?>
            <span id="countryValResult" class="validationRed"></span>
            <?php }else{ ?>
            <span id="countryValResult"></span>
            <?php } ?>
            <?php // Error message ?>
            <?php if(isset($post_array["error_msg"]["cntry"]) && $post_array["error_msg"]["cntry"] != ""){ ?>
            <span id="countryHelp" style="display: block;"><?php echo $post_array["error_msg"]["cntry"]; ?></span>
            <?php }else{ ?>
            <span id="countryHelp" style="display: none;"></span>
            <?php } ?>	
            </p>
          </p>
          
          <?php if ($params->get('form_field_state')): ?>
          <p>
            <?php 
              $form->setFieldAttribute('state','class','input-block-level');
              echo $form->getInput('state'); 
            ?>
          </p>
          <?php endif; ?>
          
          <?php if ($params->get('form_field_city')): ?>
          <p>
            <?php 
              $form->setFieldAttribute('city','class','input-block-level');
              echo $form->getInput('city'); 
            ?>
          </p>
          <?php endif; ?>
		
          <?php if ($params->get('form_field_address')): ?>
          <p>
            <?php 
              $form->setFieldAttribute('address','class','input-block-level');
              echo $form->getInput('address'); 
            ?>
            <span id="addressHelp" class="example" style="display: none;"></span>
          </p>
          <?php endif; ?>
		
          <?php if ($params->get('form_field_address2')): ?>
          <p>
            <?php 
              $form->setFieldAttribute('address2','class','input-block-level');
              echo $form->getInput('address2'); 
            ?>
          </p>
          <?php endif; ?>
		
          <?php if ($params->get('form_field_postcode')): ?>
          <p>
            <?php 
              $form->setFieldAttribute('postcode','class','input-block-level');
              echo $form->getInput('postcode'); 
            ?>
            <span id="postcodeHelp" class="example" style="display: none;"></span>
          </p>
          <?php endif; ?>
		
          <?php if (!empty($applicationsOptions)): ?>
              <p>
                  <select class="input-block-level" name="application" id="application">
                      <?php foreach ($applicationsOptions as $value => $text): ?>
                          <option value="<?php echo $value; ?>"><?php echo $text; ?></option>
                      <?php endforeach; ?>
                  </select>
              </p>
              <p>
                  <?php 
                    $form->setFieldAttribute('dataset','class','input-block-level');
                    echo $form->getInput('dataset'); 
                  ?>
              </p>
          <?php endif; ?>
    
          <p>
            <?php if ($helper->captchaIsEnabled()): ?>
            	<div id="recaptcha_widget" style="display:none">
            	<div id="recaptcha_image"></div>
            	<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
            	<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>
            	<!-- <span class="recaptcha_only_if_image">Enter the words above:</span> -->
            	<span class="recaptcha_only_if_audio">Enter the numbers you hear:</span>
            	<?php if(isset($post_array["error_msg"]["captcha"]) && $post_array["error_msg"]["captcha"] != ""){ ?>	
            	<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="validationRed" />
            	<?php }else{ ?>
            	<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" value="<?php if(isset($post_array['recaptcha_response_field']) && $post_array['recaptcha_response_field'] != ''){ echo $post_array['recaptcha_response_field']; }else{ echo 'Type the words above in the image'; } ?>" onblur="if (this.value == '') {this.value = 'Type the words above in the image';}"
            	onfocus="if (this.value == 'Type the words above in the image') {this.value = '';}"/>
            	<?php } ?>
            	<a id="recaptcha_change" href="javascript:Recaptcha.reload()"><img src="modules/<?php echo $module->module; ?>/assets/images/refresh.png" title="Refresh Captcha" width="26" height="28" /></a>
            	<span style="display: none; clear:both;" class="example" id="recpatchareponseHelp"></span>
            	</div>
            	<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $helper->getCaptchaPublicKey(); ?>">
            	</script>
            	<noscript>
            	<iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $helper->getCaptchaPublicKey(); ?>"
            	height="<?php echo $helper->getCaptchaHeight(); ?>" width="<?php echo $helper->getCaptchaWidth(); ?>" frameborder="0"></iframe><br>
            	<textarea name="recaptcha_challenge_field" rows="3" cols="40">
            	</textarea>
            	<input type="hidden" name="recaptcha_response_field"
            	value="manual_challenge">
            	</noscript>
            <?php endif; ?>
          </p>
		
          <div class="termsOfServices">
            <label class="checkbox"><input type="checkbox" id="tos" class="" name="tos" value="1"> By Launching a Joomla! demo site, you are agreeing to the <a href="http://demo.joomla.org/terms-of-service.html" target="_blank">Terms of Service</a>.</label>
            <span id="termsOfServicesHelp" class="example" style="display: block;">You must agree to Terms of Service.</span>
          </div>
		
          <p id="submitWholeForm">
            <span id="mysubtbttn">
            <!--input value="Click here to launch your Joomla! instance" name="demoSubmit" id="demoSubmit" type="submit" / -->
            <input type="button" id="demoSubmit" class="launchBtn" name="demoSubmit" value="Launch Joomla! Application in the Cloud"/>
            </span>
          
            <span id="aftersubtbttn">
            <img src="modules/<?php echo $module->module; ?>/assets/images/hold_after_submit.png" width="259" height="75" />
            </span>
          </p>
	</div>
</div>
<input type="hidden" name="selectcopmanysize" value="">
<input type="hidden" name="billing_cycle" value="Free Account" />
<input type="hidden" name="billing_paymentmethod" value="nopayment" />
</form>
</div>
</div>