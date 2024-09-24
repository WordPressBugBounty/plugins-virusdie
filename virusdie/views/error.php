<?php
/**
 * The error template.
 *
 * @package Virusdie
 */

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}
?>

<div class="vd-container">
	<div class="vd-scanner">
		<div class="vd-scanner__container">
			<span class="vd-scanner__header">Error.</span>
			<p class="vd-scanner__text">Oops! We are really sorry, but something went wrong. Please try again or contact Virusdie support team.</p>
			<div class="vd-scanner__footer">
				<a href="<?php echo $vd_user->getDashboardLink(); ?>" target="_blank" class="vd-learn-more --black --inline">Jump to complete dashboard to solve</a>
				<a href="<?php echo esc_attr(constant('VDWS_VIRUSDIE_PLUGIN_ADMIN_URL')); ?>" class="vd-learn-more --black --inline">Skip</a>
			</div>
		</div>
	</div>
</div>
