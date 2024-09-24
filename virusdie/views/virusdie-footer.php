<?php
/**
 * The footer template, printed on every Virusdie related page.
 *
 * @package Virusdie
 */

// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}
?>

</div>

<?php if (defined('VDWS_FOOTER_INVALID_EMAIL')) : ?>
	<div class="vd-error --show">
		<div class="vd-error__container">
			<span class="vd-error__badge --white">Invalid email</span>
			<span class="vd-error__text --white">Please enter valid email address. Just letters A-Z, digits 0-9 and some special symbols like “_” are allowed.</span>
		</div>
	</div>
<?php endif ?>

<?php if (defined('VDWS_FOOTER_UNSUBSCRIBED_EMAIL')) : ?>
	<div class="vd-error --show">
		<div class="vd-error__container">
			<span class="vd-error__badge --white">Invalid email</span>
			<span class="vd-error__text --white --with-link">
				You're not getting emails? Probably you requested not to receive any emails from virusdie.com. Please enable the option in your account profile settings <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_ACCOUNT'); ?>/" class="--white" terget="_blank"><?php echo constant('VDWS_VIRUSDIE_SITE_ACCOUNT'); ?>/</a>.
			</span>
		</div>
	</div>
<?php endif ?>

<?php if (defined('VDWS_FOOTER_INVALID_CODE')) : ?>
	<div class="vd-error --show">
		<div class="vd-error__container">
			<span class="vd-error__badge --white">Invalid one-time password</span>
			<span class="vd-error__text --white">Please enter the correct one-time password you got by email. Or request one more password.</span>
		</div>
	</div>
<?php endif ?>
