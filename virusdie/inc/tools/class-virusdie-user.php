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
	private $paid = false;
	private $sync_file = '';
	private $dashboard_link = '';

	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		$user = VDWS_VirusdieApiClient::get_user_info();
		$this->id = (int)$user['id'];
		$this->email = (string)$user['email'];
		$this->paid = !!$user['paid'];
		$this->sync_file = (string)$user['clientfile'];
		$this->dashboard_link = VDWS_VirusdieApiClient::get_dashboard_link();
		$this->image = !empty($user['avatar'])
			? constant('VDWS_VIRUSDIE_SITE_ACCOUNT') . '/img/users/' . esc_attr($user['avatar'])
			: constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/img/icons/avatar.png';
	}

	public function getId()
	{
		return $this->$id;
	}

	public function getEmail()
	{
		return esc_html($this->email);
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
		return (string)$this->sync_file;
	}

	public function isPaid()
	{
		return $this->paid;
	}

}