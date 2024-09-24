<?php
/**
 * Site class file for the Virusdie Plugin.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}

class VDWS_VirusdieSite
{
	private $id = 0;
	private $domain = '';
	private $firewall_status = 0;
	private $autotreatment_status = 0;
	private $dailyscan_status = 0;
	private $last_scan = 0;
	private $firewall_blocked = array();
	private $patchmanager_status = 0;
	private $scanreport_id = 0;
	private $sync_err = false;
	private $blstatus = 0;

	private $detected_m = 0;
	private $cleaned_m = 0;
	private $deleted_m = 0;
	private $detected_v = 0;
	private $cleaned_v = 0;
	private $deleted_v = 0;
	private $db_threats = 0;
	private $db_cleaned = 0;

	private $error = 0;

	public $checked = 0;

	public function __construct(VDWS_VirusdieUser $user)
	{
		$this->init($user);
	}

	private function init(VDWS_VirusdieUser &$user)
	{
		$site = VDWS_VirusdieApiClient::get_site_info();

		if (!$site) {
			return;
		}

		$user->setSitesCount(1);
		$user->hasCurrentSite(true);

		$state = $site['state'];
		$config = $site['config'];

		$isFirewallEnabled = $state['firewall']['enabled'];
		$isFirewallActiveMode = intval($isFirewallEnabled && $state['firewall']['level']) < 4;

		$isFSScan = $config['scan']['fs']['scan'];
		$isFSTreat = $config['scan']['fs']['treat'];
		$isFSPatch = $config['scan']['fs']['patch'];

		$isDBScan = $config['scan']['db']['scan'];
		$isDBTreat = $config['scan']['db']['treat'];

		$scanPeriod = $config['scan']['scheduler']['period'];
		$isScheduledScan = $scanPeriod > 0;
		$isDailyScan = $scanPeriod === 86400;

		$this->id = intval($site['_id']);
		$this->domain = strval($site['domain']);
		$this->firewall_status = $isFirewallEnabled;
		$this->autotreatment_status = $isFSTreat && $isDBTreat;
		$this->dailyscan_status = $isDailyScan;
		$this->patchmanager_status = $user->isPaid() ? $isFSPatch : false;
		$this->blstatus = intval($site['blstatus']);
		if (!empty($site['state']['scan'])) {
			$scan = $site['state']['scan'];
			$fsStats = $site['state']['stats']['fs'];
			$dbStats = $site['state']['stats']['db'];
			$this->last_scan = intval($scan['date']);
			$this->scanreport_id = $scan['id'];
			$this->detected_m = intval($fsStats['malwares']['detected']);
			$this->cleaned_m = intval($fsStats['malwares']['treated']);
			$this->deleted_m = 0;
			$this->detected_v = intval($fsStats['vulnerabilities']['detected']);
			$this->cleaned_v = intval($fsStats['vulnerabilities']['treated']);
			$this->deleted_v = 0;
			$this->db_threats = intval($dbStats['malwares']['detected']);
			$this->db_cleaned = intval($dbStats['malwares']['treated']);
			$this->error = $scan['connection']['fs']['code'];
		}
		$this->firewall_blocked = VDWS_VirusdieHelper::getBlockedIp($user, $isFirewallActiveMode);
		$this->sync_err = $scan['connection']['fs']['code'] !== 0;
		$this->checked += $isScheduledScan ? 1 : 0;
		$this->checked += $isFSTreat ? 1 : 0;
		$this->checked += $isFirewallActiveMode ? 1 : 0;
		$this->checked += $isFSPatch ? 1 : 0;
		$this->checked += false ? 1 : 0;
		$_SESSION['vdws_domain'] = $this->getDomain();
		$this->jsSiteId();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getDomain()
	{
		return strval($this->domain);
	}

	public function lastScan()
	{
		return intval($this->last_scan);
	}

	public function isDailyScan()
	{
		return !!$this->dailyscan_status;
	}

	public function isAutoTreatment()
	{
		return !!$this->autotreatment_status;
	}

	public function getInfectedCount()
	{
		return intval($this->detected_m - ($this->cleaned_m + $this->deleted_m));
	}

	public function getVulCount()
	{
		return intval($this->detected_v - ($this->cleaned_v + $this->deleted_v));
	}

	public function getDbCount()
	{
		return intval($this->db_threats - $this->db_cleaned);
	}

	public function isBlacklisted()
	{
		return !!$this->blstatus;
	}

	public function getBlacklistedCount()
	{
		return intval($this->blstatus);
	}

	public function isPatchManager()
	{
		return !!$this->patchmanager_status;
	}

	public function isSyncError()
	{
		return $this->error && $this->sync_err;
	}

	public function isScanned()
	{
		return true;
	}

	public function isFirewallOn()
	{
		return !!$this->firewall_status;
	}

	public function getFirewallBlockedCount()
	{
		return isset($this->firewall_blocked['total']) ? intval($this->firewall_blocked['total']) : false;
	}

	public function getFirewallBlockedIp()
	{
		return isset($this->firewall_blocked['ip']) ? $this->firewall_blocked['ip'] : false;
	}

	public function getFirewallBlockedIso()
	{
		return isset($this->firewall_blocked['iso']) ? $this->firewall_blocked['iso'] : false;
	}

	public function getScanreportId()
	{
		return intval($this->scanreport_id);
	}

	public function jsSiteId()
	{
		echo "<script>var VDWS_SITE_ID = " . $this->getId() . ";</script>\n";
	}

}