<?php
/**
 * View class file for the Virusdie Plugin.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

class VDWS_VirusdieView
{
	public static function render($vars = array())
	{
		if ( !key_exists('header', $vars) || !is_array($vars['header']) )
			$vars['header'] = array();
		if ( !key_exists('body', $vars) || !is_array($vars['body']) )
			$vars['body'] = array();
		if ( !key_exists('footer', $vars) || !is_array($vars['footer']) )
			$vars['footer'] = array();
		ob_start();
		self::renderHeader($vars['header']);
		self::renderBody($vars['body']);
		self::renderFooter($vars['footer']);
		ob_end_flush();
	}

	private static function renderBody($vars)
	{
		$fname = VDWS_Virusdie::get_current_tab() . '.php';
		$fname = file_exists( constant('VDWS_VIRUSDIE_PLUGIN_DIRECTORY') . 'views/' . $fname) ? $fname :
			($vars['user']->isPaid ? 'premium.php' : 'free.php');
		extract($vars, EXTR_PREFIX_ALL, 'vd');
		include_once(constant('VDWS_VIRUSDIE_PLUGIN_DIRECTORY') . 'views/' . $fname );
	}

	private static function renderHeader($vars)
	{
		VDWS_Virusdie::enqueuesCss();
		$vars = array_merge($vars, array('avatar' => is_object($vars['user']) ? '<img src="' . $vars['user']->getImage() . '" class="vd-header__user-avatar">' : ''));
		extract($vars, EXTR_PREFIX_ALL, 'vd');
		include_once( VDWS_VIRUSDIE_PLUGIN_DIRECTORY . 'views/virusdie-header.php' );
	}

	private static function renderFooter($vars)
	{
		$tab = VDWS_Virusdie::get_current_tab();
		extract($vars, EXTR_PREFIX_ALL, 'vd');
		include_once( VDWS_VIRUSDIE_PLUGIN_DIRECTORY . 'views/virusdie-footer.php' );
		if ( $tab === 'free' ) {
			echo '<script> const virusdieUpgradeLink = "' . $vars['user']->getDashboardLink() . '&r=/payments%23open-button-changeplan"; </script>';
		}
		VDWS_Virusdie::enqueuesJs();
	}

	public static function renderJsMap( $vd_site )
	{
		$vdws_Countries = "\t<script>var vdws_Countries = {";
		if ( $iso_list = $vd_site->getFirewallBlockedIso() ) foreach ( $iso_list as $iso => $data ) {
			$vdws_Countries .= esc_html($iso) . ': "<small>Attacks - ' . esc_html($data['cnt']) . '</small><small>IP - ' . esc_html($data['ip']) . '</small>",';
		}
		$vdws_Countries .= '};</script>';
		echo $vdws_Countries;
	}

}