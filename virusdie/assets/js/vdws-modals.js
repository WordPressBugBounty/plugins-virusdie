const switchControls = document.getElementsByClassName(
  "vd-switch-block__control"
);
const switchCtrl = document.getElementsByClassName("vd-js-switch");
const bodyWrapper = document.querySelector(".vd-wrapper");

const MODALS_TYPE_DATA = [
  {
    type: "onDailyScans",
    header:
      "Looks like you need Premium<br><strong>Upgrade to a Premium license to turn on scheduled scanning..</strong>",
    text: "You'll be able to detect and eliminate viruses on your sites before they can cause serious damage. You can start unlimited scans whenever you want and set up scheduled scans with shorter intervals: every day or week.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
  {
    type: "onAutoClean",
    header:
      "Looks like you need Premium<br><strong>Upgrade to a Premium plan to turn on automatic cleanup.</strong>",
    text: "Buy a Premium license to be able to automatically eliminate malicious code. Virusdie doesn't just delete files from your site or set their size to zero. It can also cut fragments of malicious code right out of your files with high accuracy. That way, your site will continue to work stably after automatic cleanup. And no matter what happens, Virusdie makes automatic backups that you can restore in one click. Learn more about what you'll gain.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
  {
    type: "onPatchManager",
    header:
      "Looks like you need Premium</strong>",
    text: "Virusdie detects not only viruses, but also website vulnerabilities. An automatic vulnerability manager is available for certain types, while other vulnerabilities will require action on your part to fix them. Full descriptions of each vulnerability include all the details you need and recommended actions to take.  Learn more about what you'll gain.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
  {
    type: "onFireWall",
    header:
      "Looks like you need Premium<br><strong>Upgrade to a Premium license to turn on firewall protection.</strong>",
    text: "Virusdie website firewall (web application firewall) deploys to your website automatically, synchronizes with the Virusdie anti-malware network, and protects your website from hackers, malware, attacks, content grabbing, XSS/SQL injections, malicious code uploads, suspicious activities, and blacklists. It loads before your website and serves as a shield. Learn more about what you'll gain.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
  {
    type: "onDailyScansSec",
    header:
      "Looks like you need Premium<br><strong>Upgrade to a Premium license to turn on scheduled scanning..</strong>",
    text: "You'll be able to detect and eliminate viruses on your sites before they can cause serious damage. You can start unlimited scans whenever you want and set up scheduled scans with shorter intervals: every day or week.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
  {
    type: "onAutoCleanSec",
    header:
      "Looks like you need Premium<br><strong>Upgrade to a Premium plan to turn on automatic cleanup.</strong>",
    text: "Buy a Premium license to be able to automatically eliminate malicious code. Virusdie doesn't just delete files from your site or set their size to zero. It can also cut fragments of malicious code right out of your files with high accuracy. That way, your site will continue to work stably after automatic cleanup. And no matter what happens, Virusdie makes automatic backups that you can restore in one click. Learn more about what you'll gain.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
  {
    type: "onFireWallSec",
    header:
      "Looks like you need Premium<br><strong>Upgrade to a Premium license to turn on firewall protection.</strong>",
    text: "Virusdie website firewall (web application firewall) deploys to your website automatically, synchronizes with the Virusdie anti-malware network, and protects your website from hackers, malware, attacks, content grabbing, XSS/SQL injections, malicious code uploads, suspicious activities, and blacklists. It loads before your website and serves as a shield. Learn more about what you'll gain.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
  {
    type: "onPatchManagerSec",
    header:
      "Looks like you need Premium</strong>",
    text: "Virusdie detects not only viruses, but also website vulnerabilities. An automatic vulnerability manager is available for certain types, while other vulnerabilities will require action on your part to fix them. Full descriptions of each vulnerability include all the details you need and recommended actions to take.  Learn more about what you'll gain.",
    btnText: "Upgrade to Premium",
    action: virusdieUpgradeLink,
  },
];


if (switchCtrl) {
	for (let control of switchCtrl) {
	  control.addEventListener("change", () => {
		if (control.getAttribute("data-available") === "on") {
		  console.log(control.checked);
		  console.log("turn on");
		}
		if (control.getAttribute("data-available") === "off") {
		  control.checked = false;
		  const selectTool = MODALS_TYPE_DATA.filter(
			(d) => d.type === control.getAttribute("name")
		  )[0];
		  Swal.fire({
			title: selectTool.header,
			text: selectTool.text,
			confirmButtonText: selectTool.btnText,
			padding: "2rem",
			showCloseButton: true,
			focusConfirm: false,
			buttonsStyling: false,
			customClass: {
			  title: "--vd-header",
			  htmlContainer: "--vd-text",
			  confirmButton: "--vd-confirm",
			  closeButton: "--vd-close",
			  container: "--vd-container",
			},
		  }).then((res) => {
			console.log(res)
			if (res.isConfirmed) {
			  console.log("confirm")
			  window.open(selectTool.action, "_blank")
			} else {
			  console.log("exit")
			}
		  })
		}
	  });
	}
  }