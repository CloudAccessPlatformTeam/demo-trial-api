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
<h2>Check your email, your new site is just a click away.</h2>
<p>You will receive a welcome email with information about how to access your site, which is hosted by CloudAccess.net.</p>
<?php endif; ?>