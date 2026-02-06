<?php
/**
 * Behavior class file for the Virusdie Plugin.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}

class VDWS_VirusdieBehavior
{
	private $user;
	private $vars;
	private $site;

	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		if (!VDWS_VirusdieApiClient::get_conn_type()) {
			$this->error('php');
		} elseif (!VDWS_VirusdieApiClient::is_key_valid() && !$this->auth()) {
			// Not authorized
		} elseif ($this->isLogout()) {
			$this->logout();
		} elseif ($this->isScanError()) {
			$this->error('scan');
		} elseif ($this->isRegError()) {
			$this->error('reg');
		} elseif ($this->isSiteError()) {
			$this->error('site');
		} elseif ($this->isPhpError()) {
			$this->error('php');
		} elseif ($this->isError()) {
			$this->error();
		} else {
			$this->work();
		}
		$this->vars = array(
			'header' => array(
				'user' => $this->user,
				'domain' => !empty($this->site) ? $this->site->getDomain() : $_SERVER['SERVER_NAME'],
			),
			'body' => array(
				'site' => $this->site,
				'user' => $this->user,
				'fw_ping' => !empty($this->user) &&
					($ping = file_get_contents((isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http').'://'.$_SERVER['SERVER_NAME'].'/?fw_t=ping&fw_k='.md5($this->user->getSyncFileName()))) &&
					($ping = json_decode($ping, true)) && !empty($ping['status']),
			),
			'footer' => array(
				'user' => $this->user,
			),
		);
		VDWS_VirusdieView::render($this->vars);
	}

	private function auth()
	{
		if (empty($_POST['vd_email']) || empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'vd_otp_login')) {
			VDWS_Virusdie::set_current_tab('auth');
			return false;
		}
		$email = trim($_POST['vd_email']); // sanitize_email($_POST['vd_email']) can discard valid email addresses
		if (strlen($email) < 6) {
			define('VDWS_FOOTER_INVALID_EMAIL', 1);
			VDWS_Virusdie::set_current_tab('auth');
			return false;
		}
		$code = isset($_POST['vd_code']) ? sanitize_text_field($_POST['vd_code']) : null;
		if (!$code) {
			if (VDWS_VirusdieApiClient::signup($email, $error)) {
				VDWS_Virusdie::set_current_tab('auth-pass');
			} elseif ($error === 111901) {
				return $this->error('reg');
			} elseif ($error === 111900) {
				define('VDWS_FOOTER_UNSUBSCRIBED_EMAIL', 1);
				VDWS_Virusdie::set_current_tab('auth');
			} else {
				define('VDWS_FOOTER_INVALID_EMAIL', 1);
				VDWS_Virusdie::set_current_tab('auth');
			}
			return false;
		}
		$code = VDWS_VirusdieApiClient::signin($email, $code);
		if (!$code) {
			define('VDWS_FOOTER_INVALID_CODE', 1);
			VDWS_Virusdie::set_current_tab('auth-pass');
			return false;
		}
		VDWS_Virusdie::set_api_key($code);
		return true;
	}

	private function work()
	{
		$this->user = new VDWS_VirusdieUser();
		$this->site = new VDWS_VirusdieSite($this->user);
		new VDWS_VirusdieMessages($this->user, $this->site);
		if (!VDWS_Virusdie::is_user_exist($this->user->getEmail())) {
			if (($err = VDWS_VirusdieApiClient::add_site()) && (intval($err) == 141900)) {
				return $this->error('site');
			}
			VDWS_Virusdie::set_user_exist($this->user->getEmail());
			return $this->welcome();
		} elseif (!VDWS_VirusdieHelper::checkSyncFile($this->user)) {
			VDWS_Virusdie::set_user_exist($this->user->getEmail());
			if (!VDWS_VirusdieHelper::updateSyncFile($this->user)) {
				return $this->error('sync'); // $this->error('reg');
			}
			return $this->scan();
		} else {
			VDWS_Virusdie::set_user_exist($this->user->getEmail());
			return $this->dashboard();
		}
	}

	private function dashboard()
	{
		VDWS_Virusdie::set_current_tab(!$this->user->isPaid() || isset($_GET['free']) ? 'free' : 'premium');
		return true;
	}

	private function welcome()
	{
		VDWS_Virusdie::set_current_tab('welcome');
		return true;
	}

	private function scan()
	{
		VDWS_Virusdie::set_current_tab('scan-start');
		return true;
	}

	private function logout()
	{
		return VDWS_VirusdieApiClient::signout() && VDWS_Virusdie::set_current_tab('auth');
	}

	private function isLogout()
	{
		return isset($_GET['logout']);
	}

	private function isError()
	{
		return isset($_GET['error']);
	}

	private function isScanError()
	{
		return isset($_GET['scan-error']);
	}

	private function isRegError()
	{
		return isset($_GET['reg-error']);
	}

	private function isSiteError()
	{
		return isset($_GET['site-error']);
	}

	private function isPhpError()
	{
		return isset($_GET['php-error']);
	}

	private function error($type = null)
	{
		$this->user = new VDWS_VirusdieUser();
		switch ($type) {
		case 'site': VDWS_Virusdie::set_current_tab('site-error'); break;
		case 'scan': VDWS_Virusdie::set_current_tab('scan-error'); break;
		case 'reg':  VDWS_Virusdie::set_current_tab('reg-error'); break;
		case 'sync': VDWS_Virusdie::set_current_tab('sync-error'); break;
		case 'php':  VDWS_Virusdie::set_current_tab('php-error'); break;
		default:     VDWS_Virusdie::set_current_tab('error'); break;
		}
		return false;
	}

	/*
	public static function canDoAjax()
	{
		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('error' => 'Permission denied'), 403);
			return FALSE;
		}
		return TRUE;
	}

	public static function vd_switcher()
	{
		if (!self::canDoAjax()) {
			return;
		}
		if (empty($_POST['name']) || empty($_POST['checked'])) {
			wp_die(json_encode(array('status' => false)));
		}
		$response = array();
		$name = sanitize_text_field($_POST['name']);
		$sec_name = sanitize_text_field(VDWS_VirusdieHelper::getSecondSwitcher($name));
		$checked = sanitize_key($_POST['checked']);
		$res = false;
		switch ($name) {
		case 'onDailyScans':
		case 'onDailyScansSec':
			$res = VDWS_VirusdieApiClient::toggle_daily_scan($checked === 'true');
			break;
		case 'onAutoClean':
		case 'onAutoCleanSec':
			$res = VDWS_VirusdieApiClient::toggle_auto_clean($checked === 'true');
			break;
		case 'onPatchManager':
		case 'onPatchManagerSec':
			$res = VDWS_VirusdieApiClient::toggle_auto_patch($checked === 'true');
			break;
		case 'onFireWall':
		case 'onFireWallSec':
			$res = VDWS_VirusdieApiClient::toggle_firewall($checked === 'true');
			break;
		case 'onInsurance':
		case 'onInsuranceSec':
			$res = false;
			break;
		}
		$response['status'] = $res;
		$response['checked'] = $checked;
		$response['names'] = array($name, $sec_name);
		header("Content-Type: application/json; charset=UTF-8");
		wp_die(json_encode($response));
	}

	public static function vd_scan_start()
	{
		if (!self::canDoAjax()) {
			return;
		}
		wp_die(VDWS_VirusdieApiClient::scan());
	}

	public static function vd_get_progress()
	{
		if (!self::canDoAjax()) {
			return;
		}
		header("Content-Type: application/json; charset=UTF-8");
		wp_die(json_encode(VDWS_VirusdieApiClient::get_progress()));
	}

	public static function vd_get_apikey()
	{
		if (!self::canDoAjax()) {
			return;
		}
		wp_die(VDWS_Virusdie::get_api_key());
	}

	public static function vd_resend()
	{
		if (!self::canDoAjax() || !isset($_POST['vd_email'])) {
			return;
		}
		wp_die(VDWS_VirusdieApiClient::signup($_POST['vd_email'], $err));
	}
	*/

}