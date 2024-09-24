<?php
/**
 * The scan error template.
 *
 * @package Virusdie
 */

// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}
?>

<div class="vd-container">
	<div class="vd-scanner">
		<div class="vd-scanner__container">
			<div class="vd-scanner__progress --hide" id="progress"></div>
			<span class="vd-scanner__header">Sorry, there's no such account.</span>
			<p class="vd-scanner__text"> We can't find your account in Virusdie.Cloud. You can create the new account via the link below or go back to authorization page and input a correct email.</p>
			<div class="vd-scanner__footer">
				<a href="<?php echo constant('VDWS_VIRUSDIE_SITE_ACCOUNT'); ?>/user/signup/" target="_blank" class="vd-learn-more --black --inline">Create account</a>
				<a href="" class="vd-learn-more --black --inline">Sign-in again</a>
			</div>
		</div>
	</div>
</div>
