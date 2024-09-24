<?php
/**
 * The header template, printed on every Virusdie related page.
 *
 * @package Virusdie
 */
// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}
?>

<div class="vd-wrapper">
	<header class="vd-header">
		<title>Virusdie</title>
		<div class="vd-header__row">
			<a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>" class="vd-header__logo-link" target="_blank">
				<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/logo.svg" class="vd-header__logo">
			</a>
			<?php if (is_object($vd_user)) : ?>
				<div class="vd-header__right">
					<div class="vd-header__user-block" id="vdUserMenuLink">
						<span class="vd-header__user-login"><?php echo esc_html($vd_user->getEmail()); ?></span>
						<img src="<?php echo esc_attr($vd_user->getImage()) ?>" class="vd-header__user-avatar">
					</div>
					<div class="vd-header__user-menu" id="vdUserMenu">
						<a href="<?php echo esc_attr($vd_user->getDashboardLink()); ?>&r=/" class="vd-header__user-link" target="_blank">My Profile</a>
						<a href="<?php echo esc_attr($vd_user->getDashboardLink()); ?>" class="vd-header__user-link" target="_blank">Dashboard</a>
						<a href="<?php echo esc_attr(VDWS_Virusdie::current_location()); ?>&logout" class="vd-header__user-link">Logout</a>
					</div>
				</div>
			<?php endif ?>
		</div>
	</header>
	<script>
	document.cookie = 'TZ=' + (new Date().getTimezoneOffset() * 60) + '; domain=.<?php echo esc_js($vd_domain); ?>; path=/; expires=' + (new Date(Date.now() + 86400*365)).toUTCString();
	var VDWS_VIRUSDIE_SITE_PANEL = '<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>';
	</script>
