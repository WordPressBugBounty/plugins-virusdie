<?php
/**
 * Helper class file for the Virusdie Plugin.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

class VDWS_VirusdieHelper
{
	private static $wpRoot = './';
	private static $geo = null;

	public function __construct()
	{
		self::init();
	}

	public static function init()
	{
		if (file_exists($path = constant('VDWS_VIRUSDIE_PLUGIN_DIRECTORY').'inc/geodb/SxGeo.dat'))
			self::$geo = new VDWS_VirusdieSxGeo($path);
		/* if ( is_dir( $_ = $_SERVER['DOCUMENT_ROOT'].'/' ) || is_dir( $_ = ABSPATH ) )
			self::$wpRoot = $_; */
		self::$wpRoot = ABSPATH;
	}

	public static function wpRoot()
	{
		return self::$wpRoot;
	}

	public static function getSecondSwitcher( $name )
	{
		return substr($name, -3) === 'Sec'
			? substr($name, 0, -3)
			: $name.'Sec';
	}

	public static function checkSyncFile($user)
	{
		return file_exists(self::wpRoot().$user->getSyncFileName());
	}

	public static function updateSyncFile($user)
	{
		$code = VDWS_VirusdieApiClient::getSyncFile();
		return $code && file_put_contents(self::wpRoot().$user->getSyncFileName(), $code);
	}

	public static function getBlockedIp($user, $isFirewallActiveMode)
	{
		$folder = self::wpRoot() . substr($user->getSyncFileName(), 0, -4) . '/firewall/blocked/' . date('Ymd');
		if (!file_exists($folder)) {
			return array('ip' => 0, 'total' => 0, 'iso' => 0);
		}
		$ip = 0;
		$total = 0;
		$list = array();
		$countries = array();
		foreach (scandir($folder) as $file) {
			if ($file === '.' || $file === '..') continue;
			if ($iso = self::getIsoByIp(str_replace('-', '.', $file))) {
				if ($iso === '?')
					$iso = '__';
				$list[] = $iso;
				if (!isset($countries[$iso]))
					$countries[$iso] = array('ip' => 0, 'cnt' => 0);
				++$countries[$iso]['ip'];
			} else {
				continue;
			}
			++$ip;
			$handle = fopen($folder.'/'.$file, 'r');
			if ($handle) {
				while (fgets($handle) !== false) {
					++$countries[$iso]['cnt'];
					++$total;
				}
				fclose($handle);
			}
		}
		return array(
			'ip' => $ip,
			'total' => $total,
			'iso' => $countries,
		);
	}

	private static function getIsoByIp($ip)
	{
		return is_string($ip) && self::$geo ? self::$geo->get($ip) : false;
	}

	public static function getWelcomeImgPath($num)
	{
		return constant('VDWS_VIRUSDIE_PLUGIN_URL').'assets/img/slides/slide_'.intval($num).'.png';
	}

	public static function getBrowserTime($time) {
		return intval(isset($_COOKIE['TZ']) ? $time - intval($_COOKIE['TZ']) : $time);
	}
}