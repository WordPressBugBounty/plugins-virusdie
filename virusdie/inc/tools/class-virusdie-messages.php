<?php
/**
 * Helper class file for the Virusdie Plugin Messages.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

class VDWS_VirusdieMessages
{

	private static $messages;
	private static $markers;
	private static $user;
	private static $site;

	public function __construct( $user, $site = null )
	{
		self::init( $user, $site );
	}

	public static function init( $user, $site )
	{
		self::$messages = array();
		self::$markers = array();
		self::$user = $user;
		self::$site = $site;
		self::setTextMessage('head_status', self::$site->isSyncError()
			? 'Sync problem.'
			: (self::$site->getInfectedCount() ||
				self::$site->getVulCount() ||
				self::$site->getDbCount() ||
				self::$site->getFirewallBlockedCount() ||
				self::$site->getFirewallBlockedIp() ||
				self::$site->isBlacklisted()
					? 'Issues were found' : 'No issues found'));
		self::setTextMessage('scanned_at', 'Scanned at: ' . date('M d H:i', VDWS_VirusdieHelper::getBrowserTime(self::$site->lastScan())));
		self::setTextMessage('fw_report_date', 'Report ' . date('M d', self::$site->lastScan()));
		self::setTextMessage('scan_status', self::$site->getInfectedCount() ?
			self::$site->getInfectedCount() . ' infected '. self::Declension('files', self::$site->getInfectedCount()) . ' found' : 'No threats found');
		self::setTextMessage('vul_status', self::$site->getVulCount() ?
			self::$site->getVulCount() . ' ' . self::Declension('vulnerabilities', self::$site->getVulCount()) . ' found' : 'No threats found');
		self::setTextMessage('db_status', self::$site->getDbCount() ?
			self::$site->getDbCount() . ' threats found' : 'No threats found');
		self::setTextMessage('fw_status', self::$site->getFirewallBlockedCount() || self::$site->getFirewallBlockedIp() ?
			self::$site->getFirewallBlockedCount() . ' ' . self::Declension('attacks were', self::$site->getFirewallBlockedCount()) . ' blocked from ' .
				self::$site->getFirewallBlockedIp() .' IP ' . self::Declension('addresses', self::$site->getFirewallBlockedIp()) . ' today' :
					'No attacks were blocked today');
		self::setTextMessage('fw_status_f', self::$site->getFirewallBlockedCount() || self::$site->getFirewallBlockedIp() ?
			self::$site->getFirewallBlockedCount() . ' ' . self::Declension('attacks were', self::$site->getFirewallBlockedCount()) . ' detected from ' .
				self::$site->getFirewallBlockedIp() . ' IP ' . self::Declension('addresses', self::$site->getFirewallBlockedIp()) . ' today' :
					'No attacks were detected today');
		self::setTextMessage('black_status', self::$site->isBlacklisted() ?
			'Mentioned on '. self::$site->getBlacklistedCount() . ' ' . self::Declension('blacklists', self::$site->getBlacklistedCount()) : 'The site is not blacklisted');
		self::setMarkerColor('sync_status', self::$site->isSyncError() ? '--not-sync' : '--clear');
		self::setMarkerColor('scan_status', self::$site->getInfectedCount() ? '--malware' : '--clear');
		self::setMarkerColor('vul_status', self::$site->getVulCount() ? '--vulnerable' : '--clear');
		self::setMarkerColor('db_status', self::$site->getDbCount() ? '--database' : '--clear');
		self::setMarkerColor('fw_status', self::$site->getFirewallBlockedIp() ? '--malware' : '--clear');
		self::setMarkerColor('black_status', self::$site->isBlacklisted() ? '--blacklisted' : '--clear');
	}

	public static function getTextMessage( $name )
	{
		return is_string($name) && isset(self::$messages[$name]) ? esc_html(self::$messages[$name]) : '';
	}

	private static function setTextMessage( $name, $text )
	{
		if ( is_string($name) && is_string($text) )
			self::$messages[$name] = sanitize_text_field($text);
	}

	public static function getMarkerColor( $name )
	{
		return is_string($name) && isset(self::$markers[$name]) ? self::$markers[$name] : '';
	}

	private static function setMarkerColor( $name, $color )
	{
		if ( is_string($name) && is_string($color) )
			self::$markers[$name] = $color;
	}

	private static function Declension( $word, $num )
	{
		switch ($word) {
		case 'files':
			return $num == 1 ? 'file' : 'files';
		case 'vulnerabilities':
			return $num == 1 ? 'vulnerability' : 'vulnerabilities';
		case 'attacks were':
			return $num == 1 ? 'attack was' : 'attacks were';
		case 'addresses':
			return $num == 1 ? 'address' : 'addresses';
		case 'blacklists':
			return $num == 1 ? 'blacklist' : 'blacklists';
		default:
			return $word;
		}
	}

}