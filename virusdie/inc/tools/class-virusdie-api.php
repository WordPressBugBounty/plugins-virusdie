<?php
/**
 * Rest API class file for the Virusdie Plugin.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}

class VDWS_VirusdieApiClient
{

	private static $connType;

	public function __construct()
	{
		self::init();
	}

	public static function init()
	{
		if (ini_get('allow_url_fopen')) {
			self::$connType = 'php';
		} elseif (function_exists('curl_init')) {
			self::$connType = 'curl';
		} else {
			self::$connType = false;
		}
	}

	public static function get_conn_type()
	{
		return self::$connType;
	}

	private static function build_stream_context($method, $apikey, array $vars = array(), $json = false)
	{
		if ($method && $apikey) {
			if (self::$connType === 'php') {
				return self::php_build_stream_context($method, $apikey, $vars, $json);
			} elseif (self::$connType === 'curl') {
				return self::curl_build_stream_context($method, $apikey, $vars, $json);
			}
		}
		return false;
	}

	private static function php_build_stream_context($method, $apikey, array $vars = array(), $json = false)
	{
		$ctype = self::http_request_ctype($method, $json);
		$header = array(
			"Accept: */*",
			"Cookie: apikey={$apikey}",
		);
		if ($ctype) {
			$header[] = $ctype;
		}
		$params = array(
			'http' => array(
				'method' => $method,
				'header' => implode("\r\n", $header),
				'follow_location' => 1,
				'max_redirects' => 3,
				'timeout' => 10,
			)
		);
		if ($ctype) {
			$params['http']['content'] = self::http_request_cdata($vars, $json);
		}
		return stream_context_create($params);
	}

	private static function curl_build_stream_context($method, $apikey, array $vars = array(), $json = false)
	{
		$ctype = self::http_request_ctype($method, $json);
		$header = array(
			"Accept: */*",
			"Cookie: apikey={$apikey}",
		);
		if ($ctype) {
			$header[] = $ctype;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		if ($ctype) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, self::http_request_cdata($vars, $json));
		}
		return $ch;
	}

	private static function http_request_ctype($method, $json = false)
	{
		return !in_array($method, array('POST', 'PUT', 'PATCH')) ? '' :
			($json ? 'Content-Type: application/json; charset=UTF-8' : 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8');
	}

	private static function http_request_cdata($vars = array(), $json = false)
	{
		return $json ? json_encode($vars, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : http_build_query($vars, '', '&');
	}

	private static function make_http_request($url, $context, $raw = false)
	{
		if ($url && $context) {
			if (self::$connType === 'php') {
				return @self::php_make_http_request($url, $context, $raw);
			} elseif (self::$connType === 'curl') {
				return @self::curl_make_http_request($url, $context, $raw);
			}
		}
		return false;
	}

	private static function php_make_http_request($url, $context, $raw = false)
	{
		$result = file_get_contents($url, false, $context);
		return !is_string($result) ? null : ($raw ? $result : json_decode($result, true));
	}

	private static function curl_make_http_request($url, $context, $raw = false)
	{
		curl_setopt($context, CURLOPT_URL, $url);
		$result = curl_exec($context);
		curl_close($context);
		return !is_string($result) ? null : ($raw ? $result : json_decode($result, true));
	}

	private static function check_result($result, &$error = null)
	{
		if (!is_array($result) || !key_exists('error', $result) || !key_exists('result', $result)) {
			$error = -1;
			return false;
		}
		if ($result['error']) {
			$error = $result['error'];
			return false;
		}
		return true;
	}

	public static function signup($email, &$error = null)
	{
		if (!is_string($email))
			return false;
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'signup/' . urlencode($email);
		$context = self::build_stream_context('GET', constant('VDWS_VIRUSDIE_SHARED_KEY'));
		$result = self::make_http_request($url, $context);
		return self::check_result($result, $error);
	}

	public static function signin($email, $code)
	{
		if (!is_string($email) || !is_string($code))
			return false;
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'signin/' . urlencode($email) . '/' . urlencode($code);
		$context = self::build_stream_context('GET', constant('VDWS_VIRUSDIE_SHARED_KEY'));
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result']['privatekey']['key'] : false;
	}

	public static function signout()
	{
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'signout/';
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		return self::check_result(self::make_http_request($url, $context)) && VDWS_Virusdie::del_api_key();
	}

	public static function get_dashboard_link()
	{
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'dashboard/';
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : false;
	}

	public static function get_site_info()
	{
		$url = constant('VDWS_VIRUSDIE_API2_HOST') . 'website_info/' . urlencode(VDWS_Virusdie::get_domain());
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : false;
	}

	public static function get_user_info()
	{
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'api_userinfo/';
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : false;
	}

	public static function add_site()
	{
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'sites_add/';
		$context = self::build_stream_context('POST', VDWS_Virusdie::get_api_key(), array(VDWS_Virusdie::get_domain()), true);
		$result = self::make_http_request($url, $context);
		self::check_result($result, $error);
		return $error;
	}

	public static function is_key_valid()
	{
		$key = VDWS_Virusdie::get_api_key();
		if (empty($key))
			return false;
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'api_keyinfo/';
		$context = self::build_stream_context('GET', $key);
		$result = self::make_http_request($url, $context);
		return self::check_result($result) && $result['result']['expires'] > time();
	}

	public static function getSyncFile(VDWS_VirusdieUser $user)
	{
		$url = constant('VDWS_VIRUSDIE_API_HOST') . 'syncfile_get/';
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context, true);
		$fname = $user ? $user->getSyncFileName() : null;
		if ($result && $fname) {
			return array('name' => $fname, 'content' => $result);
		} else {
			return false;
		}
	}

	public static function scan()
	{
		$url = constant('VDWS_VIRUSDIE_API2_HOST') . 'website_checkup/' . urlencode(VDWS_Virusdie::get_domain());
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result);
	}

	public static function get_progress()
	{
		$url = constant('VDWS_VIRUSDIE_API2_HOST') . 'website_get_progress/' . urlencode(VDWS_Virusdie::get_domain());
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : 0;
	}

	public static function toggle_firewall($value)
	{
		$url = constant('VDWS_VIRUSDIE_API2_HOST') . ($value ? 'firewall_enable/' : 'firewall_disable/') . urlencode(VDWS_Virusdie::get_domain());
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : false;
	}

	public static function toggle_daily_scan($value)
	{
		$url = constant('VDWS_VIRUSDIE_API2_HOST') . ($value ? 'daily_scan_enable/' : 'daily_scan_disable/') . urlencode(VDWS_Virusdie::get_domain());
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : false;
	}

	public static function toggle_auto_clean($value)
	{
		$url = constant('VDWS_VIRUSDIE_API2_HOST') . ($value ? 'auto_clean_enable/' : 'auto_clean_disable/') . urlencode(VDWS_Virusdie::get_domain());
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : false;
	}

	public static function toggle_auto_patch($value)
	{
		$url = constant('VDWS_VIRUSDIE_API2_HOST') . ($value ? 'auto_patch_enable/' : 'auto_patch_disable/') . urlencode(VDWS_Virusdie::get_domain());
		$context = self::build_stream_context('GET', VDWS_Virusdie::get_api_key());
		$result = self::make_http_request($url, $context);
		return self::check_result($result) ? $result['result'] : false;
	}
}
