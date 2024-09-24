<?php
/**
 * The auth email template.
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
			<span class="vd-auth__header">Please log in</span>
			<form method="POST" action="?page=virusdie">
				<input class="form-control --mb-16" placeholder="Enter your Email" name="vd_email" value="<?php echo esc_attr(isset($_POST['vd_email']) ? sanitize_email($_POST['vd_email']) : ''); ?>" />
				<p class="vd-auth__text">We’ll send your one-time password by email,
					to let you sign in from WordPress plugin securely.</p>
				<button type="submit" class="vd-btn --green">Get one-time password</button>
			</form>
		</div>
	</div>
	<div class="vd-auth__footer">
		<p class="vd-auth__footer-text">Don’t have an account? <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_ACCOUNT'); ?>/user/signup/" target="_blank">Create Account</a></p>
	</div>
</div>
