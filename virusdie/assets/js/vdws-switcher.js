const vdAjaxUrl = "/wp-admin/admin-ajax.php";

window.onload = () => {
	document.querySelectorAll('input[type="checkbox"]').forEach((el) => {
		el.onchange = (e) => {
			virusdieSwitcher(e);
		}
	});
};

const virusdieSwitchersCount = () => {
	let count = document.querySelectorAll('input[type="checkbox"]:checked').length / 2;
	document.querySelector('.vd-seclevel__level').innerHTML = count + '/5';
};

const virusdieSwitcher = (e) => {
	let data = "action=virusdie_switcher&name=" + e.target.name + "&checked=" + e.target.checked;
	let sname = getSecondSwitcher(e.target.name);
	let se = document.getElementById(sname);
	se.checked = e.target.checked;
	const request = new XMLHttpRequest();
	request.open("POST", vdAjaxUrl, true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	request.addEventListener("readystatechange", () => {
		if (request.readyState === 4 && request.status === 200) {
			let response = JSON.parse(request.responseText);
			if (typeof (response.status) !== 'undefined' && response.status != false) {
				virusdieSwitchersCount();
			} else {
				e.target.checked = !e.target.checked;
				se.checked = e.target.checked;
			}
		}
	});
	request.send(data);
};

const clickToSwitcher = (name, value) => {
	let id;
	switch (name) {
		case 'firewall':
			id = 'onFireWall';
			break;
		case 'autopatch':
			id = 'onPatchManager';
			break;
		case 'autoscan':
			id = 'onAutoClean';
			break;
		case 'dailyscan':
			id = 'onDailyScans';
			break;
	}
	try {
		if ( typeof(id) !== 'undefined' ) {
			let el = document.getElementById(id);
			let oldval = el.checked;
			let newval = value == 1 || value == 86400 ? true : false;
			if (oldval !== newval)
				el.click();
		}
	} catch (e) {
		if (e instanceof TypeError) {
			console.log({
				'request': name,
				'exception': e
			})
		}
	}
};

const getSecondSwitcher = (name) => {
	let second_name;
	if ( name.match(/Sec$/) !== null )
		second_name = name.replace(/Sec/, ''); //preg_replace('/Sec/', '', name);
	else
		second_name = name + 'Sec';
	return second_name;
}

const updateScanAt = (odt) => {
	const formatter = new Intl.DateTimeFormat('en', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: false });
	let tdt = document.querySelector('.vd-mainBlock__info-date').innerText.replace(/:.*/, ': ');
	let ndt = formatter.format(new Date(odt * 1000)).replace(',', '');
	let txt = tdt + ndt;
	document.querySelector(
		'.vd-antivirus__report-block .vd-report__date, .vd-patchmanager__report-block .vd-report__date, .vd-blist__report-block .vd-report__date'
	).forEach((el, i) => {
		el.innerText = txt;
	});
};
