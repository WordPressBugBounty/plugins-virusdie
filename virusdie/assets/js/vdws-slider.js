var sliderFirst = tns({
  container: "#firstSlider",
  mode: "carousel",
  center: true,
  loop: false,
  items: 1,
  slideBy: "page",
  touch: false,
  autoplay: false,
  nav: true,
  viewportMax: true,
  // gutter: 5,
  speed: 300,
  // navContainer: "#customize-thumbnails",
  controlsContainer: ".slider__controls.--first",
});

const prev = document.querySelector('li.prev>img');
const next = document.querySelector('li.next>img');
const skip = document.querySelector('a.vd-learn-more');
const button = document.getElementById('vdNextSlideBtn');
var last = false;

const ctrlShow = (el) => {
	return el.style.display = 'inherit';
};

const ctrlHide = (el) => {
	return el.style.display = 'none';
};

const buttonClose = () => {
	return button.innerText = 'Close';
};

const buttonNext = () => {
	return button.innerText = 'Next';
};

const skipDisplay = (cmd) => {
	switch(cmd) {
		case 'hide':
			skip.classList.add('disable');
			break;
		case 'show':
			skip.classList.remove('disable');
			break;
	}
};

const manageControls = function (info, eventName) {
	switch (info.index) {
		case 6:
			ctrlHide(next) && ctrlShow(prev) && buttonClose() && (last = true) && skipDisplay('hide');
			break;
		case 0:
			ctrlHide(prev) && ctrlShow(next) && buttonNext() && skipDisplay('show') && (last = false);
			break;
		default:
			ctrlShow(prev) && ctrlShow(next) && buttonNext() && skipDisplay('show') && (last = false);
	}
};

// bind function to event
sliderFirst.events.on('indexChanged', manageControls);

const nextBtn = document.getElementById('vdNextSlideBtn').onclick = function () {
	if (last) {
		window.location.reload();
	} else {
		sliderFirst.goTo("next");
	}
};
