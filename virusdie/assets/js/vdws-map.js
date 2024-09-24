jQuery(function($) {
	let gdpData = Object.keys(vdws_Countries);
	vdGenerateColors = function (mp, cn) {
		let c = {}, k;
		for (k in mp.regions) {
			if (cn.includes(k)) 
				c[k] = '#d54e4e'; 
			else 
				c[k] = '#8c98bf';
		}
		return c;
	};

	let map = new jvm.Map({
		map: 'world_mill',
		container: $('#world-map'),
		backgroundColor:  "#e9e9e9",
		zoomMin: 1,
		zoomMax: 1,
		zoomButtons : false,
		zoomOnScroll: false,
		regionStyle: {
			initial: {
				"fill-opacity": .9,
			},
			hover: {
				cursor: 'default',
				"fill-opacity": 1,
			}
		},
		series: {
			regions: [{
				values: {},
				attribute: 'fill',
				normalizeFunction: 'polynomial',
			}]
		},
		onRegionTipShow: function (e, el, code) {
			let text = '<big>' + el.html() + '</big>' + (typeof vdws_Countries[code] !== 'undefined' ? vdws_Countries[code] : '');
			el.html('<span class="map-label">' + text + '</span>');
		}
	});
	map.series.regions[0].setValues(vdGenerateColors(map, gdpData));
	$('#update-colors-button').click(function (e) {
		e.preventDefault();
		map.series.regions[0].setValues(vdGenerateColors(map, gdpData));
	});
});
