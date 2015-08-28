<?php
/**
 * @package 	Cloud Panel Component for Joomla!
 * @author 		CloudAccess.net LCC
 * @copyright 	(C) 2010 - CloudAccess.net LCC
 * @license 	GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

//!no direct access
defined ('_JEXEC') or die ('Restricted access');
JHTML::_('behavior.modal');
?>
<div id="activationmodal" style="text-align: center;">
    <img src="<?php echo JFactory::getURI()->root(); ?>modules/mod_createcloudaccessdemo/assets/images/loading.gif" />
    <p><?php echo JText::_('COM_CLOUDACCESSAPI_CREATION_SITE'); ?></p>
</div>
<script>
window.addEvent('domready', function() {
    SqueezeBox.initialize();
    SqueezeBox.open($('activationmodal'), {
        handler: 'adopt',
        size: {x: 300, y: 100},
        closable: false,
        closeBtn: false,
        onOpen: function ()
        {
            jQuery('#sbox-btn-close').hide();
            window.setTimeout('checkProcess()', 5000);
        }
    });
});

function checkProcess()
{
    jQuery.ajax({
        dataType: 'json',
        url: 'index.php?option=com_cloudaccessapi&task=checkStatus&format=json',
        cache: false,
        success: function (response)
        {
            var emptyResponse = jQuery.isEmptyObject(response);
            var found = false;

            if (response == null || response.length === 0) {
                jQuery('#activationmodal').text('no processes are running.');
                window.setTimeout( 'SqueezeBox.close();', 5000 );
                return false;
            } else {
                jQuery.each( response, function( pid, process ) {
                        if (process.status != 'running' && process.status != 'pending') {
                            jQuery('#activationmodal').text('').html('');
                            var p = document.createElement('p');
                            if (process.status == 'succeeded') {
                                msg = '<?php echo JText::_('com_CLOUDACCESSAPI_CREATION_SUCCESS'); ?>';
                            } else if (status.status == 'failure') {
                                msg = 'An error occur during creation process. '+process.status;
                            } else {
                                msg = 'An error occur during creation process. '+process.status;
                            }
                            p.innerHTML = msg;
                            jQuery('#activationmodal').append(p);
                        } else {
                            found = true;
                        }
                });
            }

            if (found) {
                window.setTimeout('checkProcess()', 5000);
            } else {
                window.setTimeout( 'SqueezeBox.close();document.location.href="";', 8000 );
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            jQuery('#activationmodal').text(textStatus);
            window.setTimeout( 'SqueezeBox.close();document.location.href="";', 8000 );
        }
    });
}

</script>