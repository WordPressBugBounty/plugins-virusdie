<?php
/**
 * The scan process template.
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
		<div class="vd-scanner__progress" id="progress"></div>
		<div class="vd-scanner__container">
			<span class="vd-scanner__header">Scanning your site</span>
			<p class="vd-scanner__text">Please wait for a while. Virusdie scans your site for malware and vulnerabilities. The first scan may take minutes. All further scans will take just seconds.</p>
			<a href="<?php echo esc_attr(constant('VDWS_VIRUSDIE_PLUGIN_ADMIN_URL')); ?>" class="vd-learn-more --black --inline">Skip</a>
		</div>
	</div>
</div>
