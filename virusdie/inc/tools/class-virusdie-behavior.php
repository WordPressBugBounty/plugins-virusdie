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
		if (VDWS_VirusdieApiClient::get_conn_type()) {
			if ($res = $this->check_auth()) {
				if ($this->is_logout()) {
					$this->logout();
				} else {
					if ($this->isScanError()) {
						$this->error('scan');
					} elseif ($this->is_error()) {
						$this->error();
					} else {
						$this->work();
					}
				}
			}
		} else {
			$this->error('php');
		}
		$this->build_vars();
		VDWS_VirusdieView::render($this->vars);
	}

	private function check_auth()
	{
		if (!VDWS_VirusdieApiClient::is_key_valid()) {
			return $this->auth();
		} else {
			return true;
		}
	}

	private function auth()
	{
		$email = isset($_POST['vd_email']) ? sanitize_email($_POST['vd_email']) : null;
		$code = isset($_POST['vd_code']) ? sanitize_text_field($_POST['vd_code']) : null;
		if (!$email) {
			if (empty($_POST['vd_email'])) {
				VDWS_Virusdie::set_current_tab('auth');
			} else {
				define('VDWS_FOOTER_INVALID_EMAIL', 1);
				VDWS_Virusdie::set_current_tab('auth');
			}
			return false;
		}
		if ($email && !$code) {
			if (VDWS_VirusdieApiClient::signup($email, $error)) {
				VDWS_Virusdie::set_current_tab('auth-pass');
			} else {
				if ($error === 111901) {
					return $this->error('reg');
				} elseif ($error === 111900) {
					define('VDWS_FOOTER_UNSUBSCRIBED_EMAIL', 1);
					VDWS_Virusdie::set_current_tab('auth');
				} else {
					define('VDWS_FOOTER_INVALID_EMAIL', 1);
					VDWS_Virusdie::set_current_tab('auth');
				}
			}
			return false;
		}
		if ($email && $code) {
			if ($code = VDWS_VirusdieApiClient::signin($email, $code)) {
				VDWS_Virusdie::set_api_key($code);
				return true;
			} else {
				define('VDWS_FOOTER_INVALID_CODE', 1);
				VDWS_Virusdie::set_current_tab('auth-pass');
				return false;
			}
		}
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
		} elseif (VDWS_Virusdie::is_user_exist($this->user->getEmail()) && !VDWS_VirusdieHelper::checkSyncFile($this->user)) {
			VDWS_Virusdie::set_user_exist($this->user->getEmail());
			if (!VDWS_VirusdieHelper::updateSyncFile($this->user)) {
				return $this->error('reg');
			}
			return $this->scan();
		} elseif (VDWS_Virusdie::is_user_exist($this->user->getEmail()) && VDWS_VirusdieHelper::checkSyncFile($this->user)) {
			VDWS_Virusdie::set_user_exist($this->user->getEmail());
			if (!VDWS_VirusdieHelper::updateSyncFile($this->user)) {
				return $this->error('sync');
			}
			return $this->dashboard();
		} else {
			VDWS_Virusdie::set_user_exist($this->user->getEmail());
			return $this->error();
		}
	}

	private function dashboard()
	{
		//VDWS_Virusdie::set_current_tab( !$this->user->isPaid() || isset($_GET['premium']) ? 'scan-start' : 'scan-start' );
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

	private function is_logout()
	{
		return isset($_GET['logout']);
	}

	private function is_error()
	{
		return isset($_GET['error']) || $this->isScanError() || $this->isRegError() || $this->isSiteError() || $this->isPhpError();
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

	private function logout()
	{
		return VDWS_VirusdieApiClient::signout() && VDWS_Virusdie::set_current_tab('auth');
	}

	private function error($type = null)
	{
		$this->user = new VDWS_VirusdieUser();
		switch ($type) {
			case 'site':
				VDWS_Virusdie::set_current_tab('site-error');
				break;
			case 'scan':
				VDWS_Virusdie::set_current_tab('scan-error');
				break;
			case 'reg':
				VDWS_Virusdie::set_current_tab('reg-error');
				break;
			case 'sync':
				VDWS_Virusdie::set_current_tab('sync-error');
				break;
			case 'php':
				VDWS_Virusdie::set_current_tab('php-error');
				break;
			default:
				VDWS_Virusdie::set_current_tab('error');
		}
		return false;
	}

	private function isJson($string)
	{
		return is_string($string) && json_last_error() === JSON_ERROR_NONE;
	}

	private function build_vars()
	{
		$this->vars = array(
			'header' => array(
				'user' => $this->user,
				'domain' => !empty($this->site) ? $this->site->getDomain() : $_SERVER['SERVER_NAME'],
			),
			'body' => array(
				'site' => $this->site,
				'user' => $this->user,
				'fw_ping' => !empty($this->user) ? (
					($ping = file_get_contents($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '?fw_t=ping&fw_k=' . md5($this->user->getSyncFileName()))) &&
					$this->isJson($ping) && ($ping = json_decode($ping, true)) && ($ping['status'] === 1)
				) : false,
			),
			'footer' => array(
				'user' => $this->user,
			),
		);
	}

	public static function vd_switcher()
	{
		if (empty($_POST['name']) || empty($_POST['checked']))
			wp_die(json_encode(array('status' => false)));

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
		wp_die(VDWS_VirusdieApiClient::scan());
	}

	public static function vd_get_progress()
	{
		header("Content-Type: application/json; charset=UTF-8");
		wp_die(json_encode(VDWS_VirusdieApiClient::get_progress()));
	}

	public static function vd_get_apikey()
	{
		wp_die(VDWS_Virusdie::get_api_key());
	}

	public static function vd_resend()
	{
		return isset($_POST['vd_email']) && wp_die(VDWS_VirusdieApiClient::signup(sanitize_email($_POST['vd_email']), $err));
	}

}