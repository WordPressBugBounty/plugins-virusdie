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
			<span class="vd-scanner__header">Hey, it looks like you can't make requests to 
				<a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>"><?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?></a>.</span>
			<p class="vd-scanner__text">
			Set <strong><code>allow_url_fopen = On</code></strong> in php.ini file<br/>
			or install required <strong><code>php-curl</code></strong> for your PHP version.</p>
			<div class="vd-scanner__footer">
				<a href="<?php echo constant('VDWS_VIRUSDIE_SITE_ACCOUNT'); ?>/" target="_blank" class="vd-learn-more --black --inline">Jump to dashboard</a>
				<a href="<?php echo esc_attr(constant('VDWS_VIRUSDIE_PLUGIN_ADMIN_URL')); ?>" class="vd-learn-more --black --inline">Reload this page</a>
			</div>
		</div>
	</div>
</div>
