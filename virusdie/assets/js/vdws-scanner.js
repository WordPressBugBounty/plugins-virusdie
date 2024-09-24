const vdCircle = () => {
  let Bar = new ProgressBar.Circle("#progress", {
    color: "#00B083",
    strokeWidth: 10,
    duration: 3000,
    trailColor: "#EEEEEE",
    trailWidth: 10,
    svgStyle: null,
    easing: "easeInOut",
    from: {
      color: "#00B083",
    },
    to: {
      color: "#00B083",
    },
    text: {
      value: "",
      alignToBottom: false,
    },
    step(state, bar) {
      bar.path.setAttribute("stroke", state.color);
      var value = Math.round(bar.value() * 100);
      if (value === 0) {
        bar.setText("0%");
      } else {
        bar.setText(value + "%");
      }
      bar.text.style.color = state.color;
    },
  });
  Bar.path.style.strokeLinecap = "round";
  Bar.text.style.fontFamily = "'Open Sans', sans-serif";
  Bar.text.style.fontSize = "2rem";
  return Bar;
};

const startScan = () => {
  const request = new XMLHttpRequest();
  request.open("POST", VDWS_VIRUSDIE_AJAX_URL, true);
  request.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded; charset=UTF-8"
  );
  request.addEventListener("readystatechange", () => {
    if (request.readyState === 4 && request.status === 200) {
      if (!request.responseText) {
        checkScan(false);
      }
    }
  });
  request.send("action=virusdie_start_scan");
};

const checkScan = (success) => {
  if (success) {
    document.querySelector("a.vd-learn-more").innerHTML = "Go to dashboard";
    setTimeout(() => location.replace(location.href + "&scanned"), 5e3);
  } else {
    location.replace(location.href + "&scan-error");
  }
};

const checkProgress = () => {
  return new Promise((resolve) => {
    const request = new XMLHttpRequest();
    request.open("POST", VDWS_VIRUSDIE_AJAX_URL, true);
    request.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded; charset=UTF-8"
    );
    request.addEventListener("readystatechange", () => {
      if (request.readyState === 4 && request.status === 200) {
				resolve(+request.responseText);
			}
    });
    request.send("action=virusdie_get_progress");
  });
};

var vdBar;

const updateScanPercent = (percent) => {
  if (vdBar) {
    if (percent === 0) vdBar.set(percent);
    else vdBar.animate(percent);
  }
};

document.addEventListener("DOMContentLoaded", () => {
  let timerId = setInterval(() => {
    vdBar = vdCircle();
    startScan();
    clearTimeout(timerId);
    const progressIntervalId = setInterval(() => {
      checkProgress().then((progress) => {
				updateScanPercent(progress / 100)
				if (progress === 100) location.reload();
			});
    }, 10000);
  }, 100);
});
