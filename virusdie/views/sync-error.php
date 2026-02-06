<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}
?>

<div class="vd-container">
	<div class="vd-scanner">
		<div class="vd-scanner__container">
			<div class="vd-scanner__progress --hide" id="progress"></div>
			<span class="vd-scanner__header">Error.</span>
			<p class="vd-scanner__text">The sync error detected. We can't upload a sync file to your website.
				Jump to the Virusdie master dashboard to upload your sync file manually.</p>
			<div class="vd-scanner__footer">
				<a href="<?php echo $vd_user->getDashboardLink(); ?>" target="_blank" class="vd-learn-more --black --inline">Jump to master dashboard to solve</a>
				<a href="<?php echo esc_attr(constant('VDWS_VIRUSDIE_PLUGIN_ADMIN_URL')); ?>&logout" class="vd-learn-more --black --inline">Logout</a>
			</div>
		</div>
	</div>
</div>
