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
<p class="la-belle">Thank You.<p>
<p class="la-belle" style="font-size: 25px; ">You have successfully signed up for a free trial of Joomla!</p>
<p class="lato">After launching the site you will receive a confirmation email with information about how to access your site and where you can find some help getting started.</p>
<p class="lato">We recommend that you watch one of our free & live daily <a href="http://demo.joomla.org/register-for-a-live-webinar.html">webinars</a>. We host webinars every single day for all levels of Joomla! users. You can attend as many times as you'd like or watch a recorded version online. You can find more details about it in your welcome email.</p>
<?php endif; ?>