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
	private static $geo = null;

	public function __construct()
	{
		self::init();
	}

	public static function init()
	{
		if ( file_exists( $path = constant('VDWS_VIRUSDIE_PLUGIN_DIRECTORY') . 'inc/geodb/SxGeo.dat') )
			self::$geo = new VDWS_VirusdieSxGeo($path);
	}

	public static function getSecondSwitcher( $name )
	{
		return substr($name, -3) === 'Sec'
			? substr($name, 0, -3)
			: $name.'Sec';
	}

	public static function validateEmail( string $email )
	{
		return true;
	}

	public static function checkSyncFile( $user )
	{
		return file_exists( ABSPATH . $user->getSyncFileName() );
	}

	private static function putSyncFile( array $file )
	{
		return boolval(file_put_contents( ABSPATH . $file['name'], $file['content'] ));
	}

	private static function getSyncFile( $user )
	{
		return VDWS_VirusdieApiClient::getSyncFile( $user );
	}

	public static function updateSyncFile( $user )
	{
		if ( self::checkSyncFile( $user ) ) {
			return true;
		} elseif ( $file = self::getSyncFile( $user ) ) {
			return self::putSyncFile( $file );
		} else {
			return false;
		}
	}

	public static function getBlockedIp( $user, $isFirewallActiveMode )
	{
		$folder = ABSPATH . str_replace('.php', '', $user->getSyncFileName() ) . '/firewall/blocked/' . date('Ymd');

		if ( !file_exists($folder) ) {
			return array('ip' => 0, 'total' => 0, 'iso' => 0);
		}

		$ps = $user->getPS();

		$ip = 0;
		$total = 0;
		$list = array();
		$countries = array();
		foreach ( scandir($folder) as $file ) {
			if ( $file === '.' || $file === '..') continue;
			if ( $iso = self::getIsoByIp(str_replace('-', '.', $file)) ) {
				if ($iso === '?')
					$iso = '__';
				$list[] = $iso;
				if ( !isset($countries[$iso]) )
					$countries[$iso] = array('ip' => 0, 'cnt' => 0);
				++$countries[$iso]['ip'];
			} else {
				continue;
			}
			++$ip;
			$handle = fopen($folder . '/' . $file, 'r');
			if ($handle) {
				while (($line = fgets($handle)) !== false) {
					// $_ = ($arr = explode(',', $line)) && count($arr) === 8 ? intval(end($arr)) : $ps;
					// if ($isFirewallActiveMode && $_ === 1) {
						++$countries[$iso]['cnt'];
						++$total;
					// }
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

	private static function getIsoByIp( $ip )
	{
		return is_string($ip) && self::$geo ? self::$geo->get($ip) : false;
	}

	public static function getWelcomeImgPath($num)
	{
		return constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/img/slides/slide_' . intval($num) . '.png';
	}

	public static function getBrowserTime($time) {
		return intval(isset($_COOKIE['TZ']) ? $time - intval($_COOKIE['TZ']) : $time);
	}
}