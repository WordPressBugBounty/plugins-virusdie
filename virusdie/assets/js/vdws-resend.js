const vdAjaxUrl = "/wp-admin/admin-ajax.php";
const request = new XMLHttpRequest();
const vd_email = document.querySelector('input[name="vd_email"]').value;
const resend_link = document.getElementById('resend');

const resendCode = (email) => {
	let data = "action=virusdie_resend&vd_email=" + email;
	request.open("POST", vdAjaxUrl, true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	request.addEventListener("readystatechange", () => {
		if (request.readyState === 4 && request.status === 200) {
			setTimeout( () => {
				document.querySelector('input[name="vd_code"]').focus();
				resend_link.innerHTML = 'Get one more one-time password';
				resend_link.classList.remove('vd-auth__link_mess');
			}, 3000);
		}
	});
	request.send(data);
	document.querySelector('input[name="vd_code"]').focus();
	resend_link.classList.add('vd-auth__link_mess');
	resend_link.innerHTML = 'One-time time password was sent';
};


window.onload = () => {
	resend_link.onclick = (e) => {
		resendCode(vd_email);
		return false;
	};
}
