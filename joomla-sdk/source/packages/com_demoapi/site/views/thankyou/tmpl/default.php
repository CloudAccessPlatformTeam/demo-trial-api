<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php if (!empty($this->article)): ?>
<?php echo $this->article->introtext; ?>
<?php else: ?>
<link href='http://fonts.googleapis.com/css?family=La+Belle+Aurore|Lato:400,700' rel='stylesheet' type='text/css'>
<style type="text/css">
.la-belle { font-family: 'La Belle Aurore', cursive; font-weight: 400; font-size: 38px; margin-top:30px;}
.lato { font-family: 'Lato', sans-serif; font-weight: 400; font-size: 18px; }
</style>
<h1>Thank you!</h1>
<h2>You have successfully launched a free demo trial site.</h2>
<p>You will receive a welcome email with information about how to access your demo site, which is hosted by CloudAccess.net. Your demo site will eventually expire. Before it expires you can either upgrade to one of CloudAccess.net’s hosting & support packages or create a snapshot of the site which you can download to your computer.</p>
<?php endif; ?>