<?php
/**
 * The auth one-time password template.
 *
 * @package Virusdie
 */

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}
?>

<div class="vd-container">
	<div class="vd-auth">
		<div class="vd-auth__container">
			<span class="vd-auth__header">One-time pass</span>
			<form method="POST" action="?page=virusdie">
				<input type="hidden" name="vd_email" value="<?php echo esc_attr(isset($_POST['vd_email']) ? sanitize_email($_POST['vd_email']) : ''); ?>" />
				<input class="form-control --mb-16" placeholder="Enter one-time password" name="vd_code" />
				<p class="vd-auth__text">
					Please enter one-time secure password we've just sent to your <strong><?php echo esc_html(isset($_POST['vd_email']) ? sanitize_email($_POST['vd_email']) : ''); ?></strong>
				</p>
				<div class="vd-auth__btns">
					<button type="submit" class="vd-btn --green">Confirm one-time password</button>
					<a href="" id="resend" class="vd-auth__link">Get one more one-time password</a>
				</div>
			</form>
		</div>
	</div>
	<div class="vd-auth__footer">
		<p class="vd-auth__footer-text">Don’t have an account? <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_ACCOUNT'); ?>/user/signup/">Create Account</a></p>
	</div>
</div>
