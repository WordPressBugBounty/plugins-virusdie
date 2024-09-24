<?php
/**
 * User class file for the Virusdie Plugin.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

class VDWS_VirusdieUser
{
	private $id = 0;
	private $email = '';
	private $image = '';
	private $tariff = 0;
	private $free = true;
	private $sync_file = '';
	private $dashboard_link = '';
	private $ps = 0;
	private $sites_count;
	private $has_cur_site = false;

	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		$user = VDWS_VirusdieApiClient::get_user_info();
		$this->id = intval($user['id']);
		$this->email = strval($user['email']);
		$this->tariff = $user['billtype'];
		$this->free = boolval($user['free']);
		$this->sync_file = strval($user['clientfile']);
		$this->dashboard_link = VDWS_VirusdieApiClient::get_dashboard_link();
		$this->ps = $this->ps();
		$this->image = !empty($user['avatar'])
			? constant('VDWS_VIRUSDIE_SITE_ACCOUNT') . '/img/users/' . esc_attr($user['avatar'])
			: constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/img/icons/avatar.png';
	}

	private function ps()
	{
		$f = ABSPATH . str_replace('.php', '', $this->getSyncFileName()) . '/firewall/security/settings.json';
		if (is_file($f)) {
			$s = @json_decode(file_get_contents($f), true);
			if (is_array($s) && key_exists('ps', $s)) {
				return intval($s['ps']);
			}
		}
		return 0;
	}

	public function getId()
	{
		return $this->$id;
	}

	public function getEmail()
	{
		return esc_html($this->email);
	}

	public function getTariff()
	{
		return intval($this->tariff);
	}

	public function getImage()
	{
		return esc_url($this->image);
	}

	public function getDashboardLink()
	{
		return esc_url_raw($this->dashboard_link);
	}

	public function getSyncFileName()
	{
		return strval($this->sync_file);
	}

	public function getPS()
	{
		return $this->ps;
	}

	public function isPaid()
	{
		return !boolval($this->free);
	}

	public function setSitesCount($count)
	{
		$this->sites_count = $count;
	}

	public function getSitesCount()
	{
		return $this->sites_count;
	}

	public function hasCurrentSite( $set = false ) {
		if ($set) {
			$this->has_cur_site = true;
			return null;
		} else {
			return $this->has_cur_site;
		}
	}

}