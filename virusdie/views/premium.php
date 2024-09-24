<?php
/**
 * Premium dashboard template.
 *
 * @package Virusdie
 */

// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}

VDWS_VirusdieView::renderJsMap($vd_site);
?>

<div class="vd-mainBlock">
	<div class="vd-mainBlock__block --big">
		<div class="vd-mainBlock__col">
			<div class="vd-mainBlock__box">
				<div class="vd-mainBlock__badges">
					<?php if ($vd_site->isSyncError()) : ?>
						<div class="vd-mainBlock__badge --not-sync"></div>
					<?php else : ?>
						<?php echo $vd_site->getInfectedCount() || $vd_site->getFirewallBlockedIp() ? '<div class="vd-mainBlock__badge --malware"></div>' : ''; ?>
						<?php echo $vd_site->getVulCount() ? '<div class="vd-mainBlock__badge --vulnerable"></div>' : ''; ?>
						<?php echo $vd_site->getDbCount() ? '<div class="vd-mainBlock__badge --database"></div>' : ''; ?>
						<?php // echo $vd_site->isBlacklisted() ? '<div class="vd-mainBlock__badge --blacklisted"></div>' : ''; ?>
					<?php endif ?>
				</div>
				<span class="vd-mainBlock__domain"><?php echo esc_html($vd_site->getDomain()); ?></span>
			</div>
			<div class="vd-mainBlock__info-block">
				<span class="vd-mainBlock__info-text"><?php echo VDWS_VirusdieMessages::getTextMessage('head_status'); ?></span>
				<span class="vd-mainBlock__info-date"><?php echo VDWS_VirusdieMessages::getTextMessage('scanned_at'); ?></span>
			</div>
		</div>
		<div class="vd-mainBlock__col --small">
			<a href="" class="vd-link-btn" onclick="return false">
				<span class="icon --mr-9">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M7.591 0.499756C4.74311 0.499756 2.457 2.78622 2.457 5.63376C2.457 8.48211 4.74293 10.7688 7.591 10.7688C10.4392 10.7688 12.726 8.48197 12.726 5.63376C12.726 2.78636 10.439 0.499756 7.591 0.499756ZM7.591 1.99976C9.61066 1.99976 11.226 3.61484 11.226 5.63376C11.226 7.65354 9.61079 9.26876 7.591 9.26876C5.57146 9.26876 3.957 7.65379 3.957 5.63376C3.957 3.6146 5.57159 1.99976 7.591 1.99976ZM18.0667 5.61636C18.0667 3.38183 16.2546 1.56936 14.0197 1.56936C13.6055 1.56936 13.2697 1.90514 13.2697 2.31936C13.2697 2.73357 13.6055 3.06936 14.0197 3.06936C15.4261 3.06936 16.5667 4.21019 16.5667 5.61636C16.5667 7.02314 15.4265 8.16336 14.0197 8.16336C13.6055 8.16336 13.2697 8.49914 13.2697 8.91336C13.2697 9.32757 13.6055 9.66336 14.0197 9.66336C16.2549 9.66336 18.0667 7.85157 18.0667 5.61636ZM17.2991 11.9938C16.7444 11.8708 16.1673 11.7876 15.5867 11.7481C15.1734 11.72 14.8156 12.0322 14.7875 12.4455C14.7594 12.8587 15.0716 13.2165 15.4849 13.2446C15.9905 13.279 16.4933 13.3515 16.9907 13.4616C17.7673 13.6163 18.2817 13.8732 18.4208 14.165C18.5256 14.3856 18.5256 14.6441 18.4204 14.8654C18.2823 15.1562 17.7705 15.4126 17.0017 15.5707C16.596 15.6542 16.3347 16.0507 16.4182 16.4565C16.5016 16.8622 16.8982 17.1234 17.3039 17.04C18.4987 16.7942 19.3728 16.3563 19.7752 15.5092C20.074 14.8806 20.074 14.1491 19.7752 13.5204C19.3705 12.6713 18.4872 12.2301 17.2991 11.9938ZM0 16.0183C0 13.3937 2.75759 12.4563 7.591 12.4563L7.91078 12.4576C12.5552 12.4981 15.183 13.4361 15.183 15.9983C15.183 18.5061 12.6645 19.4736 8.22314 19.5546L7.591 19.5603C2.74666 19.5603 0 18.6394 0 16.0183ZM13.683 15.9983C13.683 14.6505 11.6124 13.9563 7.591 13.9563C3.57365 13.9563 1.5 14.6612 1.5 16.0183C1.5 17.3667 3.56868 18.0603 7.591 18.0603C11.6086 18.0603 13.683 17.3552 13.683 15.9983Z" fill="#B3B3B3" />
					</svg>
				</span>
				Share access
			</a>
			<small>Coming in 2024</small>
		</div>
	</div>
	<div class="vd-mainBlock__plan --premium">
		<div class="vd-mainBlock__plan-header --col">
			<span class="vd-mainBlock__plan-title --white">Premium $15<sub>/MO</sub></span>
			<a href="<?php echo $vd_user->getDashboardLink(); ?>&r=<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>/billing" class="vd-learn-more --white --inline --mobile-br" target="_blank">Manage subscription</a>
		</div>
		<p class="vd-mainBlock__plan-text --white">
			The complete set of Premium website security tools now available for you for best website protection.
		</p>
	</div>
</div>

<div class="vd-hmonitor">
	<span class="vd-hmonitor__header">Website health monitor</span>
	<p class="vd-hmonitor__text">
		Monitor your website security and fix issues automatically from the single dashboard.
		<a href="<?php echo $vd_user->getDashboardLink(); ?>" class="vd-learn-more --black --inline --mobile-br" target="_blank">Jump to complete dashboard</a>
	</p>
</div>

<div class="vd-tools">
	<div class="vd-tools__col">
		<div class="vd-antivirus">
			<div class="vd-antivirus__head">
				<span class="vd-antivirus__header">Website Antivirus<mark>Premium</mark></span>
			</div>
			<div class="vd-antivirus__upgrade-block">
				<p class="vd-antivirus__upgrade-text">
					Daily scans and safest in the industry automatic website cleanup available for you.
				</p>
			</div>
			<div class="vd-antivirus__ctrls">
				<div class="vd-antivirus__ctrl-block">
					<span class="vd-antivirus__ctrl-text">Daily scans</span>
					<label class="vd-switch-block__control --m-0" for="onDailyScans">
						<input id="onDailyScans" type="checkbox" name="onDailyScans" class="vd-js-switch" data-available="on" <?php echo $vd_site->isDailyScan() ? 'checked' : ''; ?> />
						<span class="vd-switch-block__slider --round --green"></span>
					</label>
				</div>
				<div class="vd-antivirus__ctrl-block">
					<span class="vd-antivirus__ctrl-text">Automatic website cleanup</span>
					<label class="vd-switch-block__control --m-0" for="onAutoClean">
						<input id="onAutoClean" type="checkbox" name="onAutoClean" class="vd-js-switch" data-available="on" <?php echo $vd_site->isAutoTreatment() ? 'checked' : ''; ?> ?> />
						<span class="vd-switch-block__slider --round --green"></span>
					</label>
				</div>
			</div>
			<div class="vd-antivirus__reports">
				<div class="vd-antivirus__report-block">
					<span class="vd-antivirus__title">File scan & Cleaning</span>
					<a href="<?php echo $vd_user->getDashboardLink(); ?>&r=<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>/websites%23quick_SIDEBAR:<?php echo intval($vd_site->getId()); ?>/FS_MALWARES_STATE" class="vd-report" target="_blank">
						<div class="vd-report__col">
							<div class="vd-report__item-box">
								<div class="vd-report__indicator <?php echo VDWS_VirusdieMessages::getMarkerColor('scan_status'); ?>"></div>
								<span class="vd-report__title"><?php echo VDWS_VirusdieMessages::getTextMessage('scan_status'); ?></span>
							</div>
							<span class="vd-report__date"><?php echo VDWS_VirusdieMessages::getTextMessage('scanned_at'); ?></span>
						</div>
						<span class="vd-report__arrow-btn">
							<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-round.svg" alt="" />
						</span>
					</a>
				</div>
				<div class="vd-antivirus__report-block">
					<span class="vd-antivirus__title">Database scan & Cleaning</span>
					<a href="<?php echo $vd_user->getDashboardLink(); ?>&r=<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>/websites%23quick_SIDEBAR:<?php echo intval($vd_site->getId()); ?>/DB_MALWARES_STATE" class="vd-report" target="_blank">
						<div class="vd-report__col">
							<div class="vd-report__item-box">
								<div class="vd-report__indicator <?php echo VDWS_VirusdieMessages::getMarkerColor('db_status'); ?>"></div>
								<span class="vd-report__title"><?php echo VDWS_VirusdieMessages::getTextMessage('db_status'); ?></span>
							</div>
							<span class="vd-report__date"><?php echo VDWS_VirusdieMessages::getTextMessage('scanned_at'); ?></span>
						</div>
						<span class="vd-report__arrow-btn">
							<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-round.svg" alt="" />
						</span>
					</a>
				</div>
			</div>
		</div>

		<div class="vd-patchmanager">
			<div class="vd-patchmanager__head">
				<span class="vd-patchmanager__header">Patch Manager<mark>Premium</mark></span>
				<label class="vd-switch-block__control --m-0" for="onPatchManager">
					<input id="onPatchManager" type="checkbox" name="onPatchManager" class="vd-js-switch" data-available="on" <?php echo $vd_site->isPatchManager() ? 'checked' : ''; ?> />
					<span class="vd-switch-block__slider --round --green"></span>
				</label>
			</div>
			<div class="vd-patchmanager__upgrade-block">
				<p class="vd-patchmanager__upgrade-text">
					Website hardening - a virtual and real patch management for vulnerabilities now available.
				</p>
			</div>
			<div class="vd-patchmanager__reports">
				<div class="vd-patchmanager__report-block">
					<span class="vd-patchmanager__title">Vulnerabilities</span>
					<a href="<?php echo $vd_user->getDashboardLink(); ?>&r=<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>/websites%23quick_SIDEBAR:<?php echo intval($vd_site->getId()); ?>/VULNERABILITIES_STATE" class="vd-report" target="_blank">
						<div class="vd-report__col">
							<div class="vd-report__item-box">
								<div class="vd-report__indicator <?php echo VDWS_VirusdieMessages::getMarkerColor('vul_status'); ?>"></div>
								<span class="vd-report__title"><?php echo VDWS_VirusdieMessages::getTextMessage('vul_status'); ?></span>
							</div>
							<span class="vd-report__date"><?php echo VDWS_VirusdieMessages::getTextMessage('scanned_at'); ?></span>
						</div>
						<span class="vd-report__arrow-btn">
							<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-round.svg" alt="" />
						</span>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="vd-tools__col">
		<div class="vd-fwall">
			<div class="vd-fwall__head">
				<span class="vd-fwall__header">Website Firewall<mark>Premium</mark></span>
				<label class="vd-switch-block__control --m-0" for="onFireWall">
					<input id="onFireWall" type="checkbox" name="onFireWall" class="vd-js-switch" data-available="on" <?php echo $vd_site->isFirewallOn() ? 'checked' : ''; ?> />
					<span class="vd-switch-block__slider --round --green"></span>
				</label>
			</div>
			<div class="vd-fwall__upgrade-block">
				<p class="vd-fwall__upgrade-text">
					Realtime hack protection, attack mitigation, coutry blocking, IP and URLs whitelist/blacklist, and custom firewall rules available on complete dashboard.
				</p>
			</div>
			<div class="vd-fwall__reports">
				<div class="vd-fwall__report-block">
					<span class="vd-fwall__title">Firewall Reports</span>
					<div class="vd-fwall__map" id="chartDiv">
						<div id="world-map"></div>
					</div>
					<?php if ($vd_fw_ping): ?>
					<a href="<?php echo $vd_user->getDashboardLink(); ?>&r=<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>/websites%23quick_SIDEBAR:<?php echo intval($vd_site->getId()) . '/FIREWALL_TAB/FIREWALL_DAILY:' . date('Ymd', intval($vd_site->lastScan())); ?>" class="vd-report" target="_blank">
						<div class="vd-report__col">
							<div class="vd-report__item-box">
								<div class="vd-report__indicator <?php echo VDWS_VirusdieMessages::getMarkerColor('fw_status'); ?>"></div>
								<span class="vd-report__title"><?php echo VDWS_VirusdieMessages::getTextMessage('fw_status'); ?></span>
							</div>
							<span class="vd-report__date"><?php echo VDWS_VirusdieMessages::getTextMessage('fw_report_date'); ?></span>
						</div>
						<span class="vd-report__arrow-btn">
							<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-round.svg" alt="" />
						</span>
					</a>
					<?php else: ?>
					<a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>/faq/firewall/" class="vd-report" target="_blank">
						<div class="vd-report__col">
							<div class="vd-report__item-box">
								<div class="vd-report__indicator --not-sync"></div>
								<span class="vd-report__title">Failure to get data from the website firewall</span>
							</div>
							<span class="vd-report__date">Connection failure: <?php echo date('M d, Y'); ?></span>
						</div>
						<span class="vd-report__arrow-btn">
							<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-round.svg" alt="" />
						</span>
					</a>
					<?php endif ?>
				</div>
			</div>
		</div>

		<?php /* <div class="vd-blist">
			<div class="vd-blist__head">
				<span class="vd-blist__header">Blacklist Monitoring</span>
			</div>
			<div class="vd-blist__upgrade-block">
				<p class="vd-blist__upgrade-text">
					We check sites against 60+ blacklists automatically, and we'll notify you if any issues are detected.
				</p>
			</div>
			<div class="vd-blist__reports --pt-0">
				<div class="vd-blist__report-block">
					<a href="<?php echo $vd_user->getDashboardLink(); ?>&r=<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>/websites%23<?php echo intval($vd_site->getId()); ?>/BLACKLISTS" class="vd-report" target="_blank">
						<div class="vd-report__col">
							<div class="vd-report__item-box">
								<div class="vd-report__indicator <?php echo VDWS_VirusdieMessages::getMarkerColor('black_status'); ?>"></div>
								<span class="vd-report__title"><?php echo VDWS_VirusdieMessages::getTextMessage('black_status'); ?></span>
							</div>
							<span class="vd-report__date"><?php echo VDWS_VirusdieMessages::getTextMessage('scanned_at'); ?></span>
						</div>
						<span class="vd-report__arrow-btn">
							<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-round.svg" alt="" />
						</span>
					</a>
				</div>
			</div>
		</div> */ ?>
	</div>

	<div class="vd-tools__col">
		<div class="vd-seclevel">
			<div class="vd-seclevel__head">
				<span class="vd-seclevel__header">Security Level<mark>Premium</mark></span>
				<div class="vd-seclevel__level"><?php echo intval($vd_site->checked) ?>/5</div>
			</div>
			<div class="vd-seclevel__upgrade-block">
				<p class="vd-seclevel__upgrade-text">
					Improve your website security with the best automatic protection features in the industry.
				</p>
			</div>
			<div class="vd-seclevel__ctrls">
				<div class="vd-seclevel__ctrl-block">
					<div class="vd-seclevel__ctrl-box">
						<div class="vd-seclevel__ctrl-num">1</div>
						<p class="vd-seclevel__ctrl-text">
							Daily scans for malware and vulnerabilities <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>/faq/antivirus/" target="_blank"><img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/icon-info.svg" class="vd-seclevel__ctrl-info"></a>
						</p>
					</div>
					<label class="vd-switch-block__control --m-0 --green-border" for="onDailyScansSec">
						<input id="onDailyScansSec" type="checkbox" name="onDailyScansSec" class="vd-js-switch" data-available="on" <?php echo $vd_site->isDailyScan() ? 'checked' : ''; ?> />
						<span class="vd-switch-block__slider --round --green-border"></span>
					</label>
				</div>
				<div class="vd-seclevel__ctrl-block">
					<div class="vd-seclevel__ctrl-box">
						<div class="vd-seclevel__ctrl-num">2</div>
						<p class="vd-seclevel__ctrl-text">
							Auto clean up infected files and database fields <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>/faq/antivirus/" target="_blank"><img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/icon-info.svg" class="vd-seclevel__ctrl-info"></a>
						</p>
					</div>
					<label class="vd-switch-block__control --m-0 --green-border" for="onAutoCleanSec">
						<input id="onAutoCleanSec" type="checkbox" name="onAutoCleanSec" data-available="on" class="vd-js-switch" <?php echo $vd_site->isAutoTreatment() ? 'checked' : ''; ?> />
						<span class="vd-switch-block__slider --round --green-border"></span>
					</label>
				</div>
				<div class="vd-seclevel__ctrl-block">
					<div class="vd-seclevel__ctrl-box">
						<div class="vd-seclevel__ctrl-num">3</div>
						<p class="vd-seclevel__ctrl-text">
							Block hacks and online attacks in real-time <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>/faq/firewall/" target="_blank"><img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/icon-info.svg" class="vd-seclevel__ctrl-info"></a>
						</p>
					</div>
					<label class="vd-switch-block__control --m-0 --green-border" for="onFireWallSec">
						<input id="onFireWallSec" type="checkbox" name="onFireWallSec" data-available="on" class="vd-js-switch" <?php echo $vd_site->isFirewallOn() ? 'checked' : ''; ?> />
						<span class="vd-switch-block__slider --round --green-border"></span>
					</label>
				</div>
				<div class="vd-seclevel__ctrl-block">
					<div class="vd-seclevel__ctrl-box">
						<div class="vd-seclevel__ctrl-num">4</div>
						<p class="vd-seclevel__ctrl-text">
							Fix vulnerabilities with virtual patching <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>/faq/patchmanager/" target="_blank"><img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/icon-info.svg" class="vd-seclevel__ctrl-info"></a>
						</p>
					</div>
					<label class="vd-switch-block__control --m-0 --green-border" for="onPatchManagerSec">
						<input id="onPatchManagerSec" type="checkbox" name="onPatchManagerSec" data-available="on" class="vd-js-switch" <?php echo $vd_site->isPatchManager() ? 'checked' : ''; ?> />
						<span class="vd-switch-block__slider --round --green-border"></span>
					</label>
				</div>
				<div class="vd-seclevel__ctrl-block">
					<div class="vd-seclevel__ctrl-box">
						<div class="vd-seclevel__ctrl-num">5</div>
						<p class="vd-seclevel__ctrl-text">
							Protect yourself against extra costs with insurance <a href="<?php echo constant('VDWS_VIRUSDIE_SITE_LANDING'); ?>/company/mission/#insurance-desc" target="_blank"><img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/icon-info.svg" class="vd-seclevel__ctrl-info"></a>
							<br><small>Coming in 2024</small>
						</p>
					</div>
					<label class="vd-switch-block__control --m-0 --green-border" for="onInsuranceSec">
						<input id="onInsuranceSec" type="checkbox" name="onInsuranceSec" data-available="on" disabled="disabled">
						<span class="vd-switch-block__slider --round --green-border"></span>
					</label>
				</div>
			</div>
		</div>

		<div class="vd-support">
			<div class="vd-support__head">
				<span class="vd-support__header">Support 24/7</span>
			</div>
			<div class="vd-support__upgrade-block">
				<p class="vd-support__upgrade-text">
					We can help you! Use the built-in ticket system on your Virusdie dashboard to send us a message.
				</p>
			</div>
			<a href="<?php echo $vd_user->getDashboardLink(); ?>&r=<?php echo constant('VDWS_VIRUSDIE_SITE_PANEL'); ?>/support%23NEWTICKET" class="vd-learn-more --black --inline" target="_blank">Send request to support</a>
		</div>
	</div>
</div>
