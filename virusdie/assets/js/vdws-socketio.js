var VDWS_VIRUSDIE_SITE_PANEL; // Set in virusdie-header.php
var VDWS_VIRUSDIE_AJAX_URL = '/wp-admin/admin-ajax.php';
var VDWS_SITE_ID; // Set in VDWS_VirusdieSite::jsSiteId()
var VDWS_VIRUSDIE_AUTH_STATUS = false;
var VDWS_SCAN_PROGRESS = false;

const virusdieRunFunction = (func, data, sdata) => {
	try { eval(func)(data, sdata); } catch (e) {};
};

const virusdieProgressEvent = (data) => {
	if (!data.id || data.id !== VDWS_SITE_ID) return;
	// console.log('vdws:scanning', data.percentage+'%', data.type, data.path);
	VDWS_SCAN_PROGRESS = true;
	virusdieRunFunction('updateScanPercent', (data.percentage < 1) ? 0 : data.percentage / 100);
};

const virusdieIOSocket = io.connect(VDWS_VIRUSDIE_SITE_PANEL, {
	transports: ['websocket'],
	withCredentials: true,
	reconnectionAttempts: 10,
	reconnectionDelay: 1e3,
	timeout: 10e3,
});

virusdieIOSocket.on('connect', () => {
	console.log('vdws:connect', VDWS_VIRUSDIE_SITE_PANEL);
	const request = new XMLHttpRequest();
	request.open('POST', VDWS_VIRUSDIE_AJAX_URL, true);
	request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
	request.addEventListener('readystatechange', () => {
		if (request.readyState === 4 && request.status === 200) {
			virusdieIOSocket.emit('api_user', request.responseText);
		}
	});
	request.send('action=virusdie_apikey');
}).on('connect_error', (e) => {
	console.log('vdws:connect_error', e);
}).on('disconnect', (e) => {
	console.log('vdws:disconnect', e);
}).on('user', user => {
	console.log('vdws:user', user && user.id, user && user.login);
	VDWS_VIRUSDIE_AUTH_STATUS = user;
}).on('scanning_path', virusdieProgressEvent
).on('scanning_table', virusdieProgressEvent
).on('scanner_stop', (data) => {
	if (!data.id || data.id !== VDWS_SITE_ID) return;
	// console.log('vdws:scanner_stop', VDWS_SCAN_PROGRESS, data); // {id, report}
	VDWS_SCAN_PROGRESS && virusdieRunFunction('updateScanPercent', 1.0);
	virusdieRunFunction('checkScan', VDWS_SCAN_PROGRESS);
}).on('website_set_option', (data) => {
	// console.log('vdws:website_set_option', data);
	if (!data || !data.key || !data.siteid || data.siteid !== VDWS_SITE_ID) return;
	switch (data.key) {
	case 'firewall_level':
		virusdieRunFunction('clickToSwitcher', 'firewall', data.val);
		virusdieRunFunction('changeFirewallLevel', 'fwlevel', data.val);
		break;
	case 'autopatching':
		virusdieRunFunction('clickToSwitcher', 'autopatch', data.val);
		break;
	case 'scanperiod':
		virusdieRunFunction('clickToSwitcher', 'dailyscan', data.val);
		break;
	case 'autocleanup':
		virusdieRunFunction('clickToSwitcher', 'autoscan', data.val);
		break;
	/*case '':
		virusdieRunFunction('clickToSwitcher', 'tariff', data.val);
		break;*/
	}
});
