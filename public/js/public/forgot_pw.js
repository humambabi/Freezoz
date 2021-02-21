/* ----------------------------------------------
Forgot PW page
---------------------------------------------- */

var onloadCallback = function() {
	grecaptcha.render('google-recaptcha', {
		'sitekey': '6LdHvd8ZAAAAAFMgZKkFmVx8KkFZMBxdlGuGOYLj',
		'size': window.innerWidth < 650 ? 'compact' : 'normal'
	});
};


function pageForgotPwScripts() {
	$("#forgotpw_submit").click(function() {
		var elm, parent_elm, err_elm, value, grr, bEmail = false, bGR = false;

		// Validate "email"
		elm = document.getElementById("resetpw-email");
		parent_elm = elm.parentNode;
		err_elm = parent_elm.querySelector(".errmsg");
		value = elm.value.trim();
		elm.value = value;
		if (value.length < 1) {
			if (!isInViewport("resetpw-email")) scroll_page("resetpw-email");
			if (parent_elm.classList.contains("error")) {
				parent_elm.classList.remove("error");
				void parent_elm.offsetWidth; // Restart the animation
			}
			parent_elm.classList.add("error");
			err_elm.innerHTML = "This field cannot be empty!";
			return;
		} else if (!isEmail(value)) {
			if (!isInViewport("resetpw-email")) scroll_page("resetpw-email");
			if (parent_elm.classList.contains("error")) {
				parent_elm.classList.remove("error");
				void parent_elm.offsetWidth; // Restart the animation
			}
			parent_elm.classList.add("error");
			err_elm.innerHTML = "Not a valid email address!";
			return;
		} else {
			if (parent_elm.classList.contains("error")) parent_elm.classList.remove("error");
			bEmail = true;
		}

		// Check Google reCAPTCHA
		grr = grecaptcha.getResponse();
		if (!grr.length) {
			swal({
				text: "Please check Google reCAPTCHA and try again!",
				icon: "warning"
			});
			grecaptcha.reset();
			if (!isInViewport("google-recaptcha")) scroll_page("google-recaptcha");
			return;
		} else {
			bGR = true;
		}

		if (!bEmail || !bGR) return;

		/* OK, send the form to the server */

		// Show the overlay with a spinner, and don't allow closing on click
		g_bStopScroll = true;
		g_iStopScrollPos = $(window).scrollTop();
		$("#overlay-full").html(ELM_SPINNER);
		$("#overlay-full").removeClass("hidden");
		$("#overlay-full").data("can-close-on-click", false);

		// Send data
		$.ajax({
			url: BASE_URI + "/requests/forgot_pw",
			type: 'post',
			headers: {'X-Requested-With': 'XMLHttpRequest'},
			data: {
				email: $('#resetpw-email').val(),
				grecaptcha: grr
			},
			datatype: 'json',
			success: function(response) {
				// Leave spinner showing until processing the responce
				if (!isJson(response)) { // Should not happen unless server error happens or bad ajax was sent
					console.info(response); // Most likely response is a string
					$("#overlay-full").html("&nbsp;"); // Remove the spinner
					swal({
						title: "Ooops!", text: "An unknown error occurred!", icon: "warning"
					}).then(function() {
						$("#overlay-full").data("can-close-on-click", true); // Allow close on click
						$("#overlay-full").trigger("click");
					});
					return;
				} 

				// We are confident that response is a json object (returned gracefully from server)

				if (response.retcode == STATUS_GENERROR) { // Error message should have been tagged along
					$("#overlay-full").html("&nbsp;"); // Remove the spinner
					swal({
						text: response.retdata, icon: "warning"
					}).then(function() {
						$("#overlay-full").data("can-close-on-click", true); // Allow close on click
						$("#overlay-full").trigger("click");
					});
					return;
				}

				if (response.retcode == STATUS_RECAPTCHA_INVALID) { // Error message should have been tagged along
					$("#overlay-full").html("&nbsp;"); // Remove the spinner
					swal({
						title: "Wait!", text: response.retdata, icon: "warning"
					}).then(function() {
						$("#overlay-full").data("can-close-on-click", true); // Allow close on click
						$("#overlay-full").trigger("click");
						grecaptcha.reset();
					});
					return;
				}

				// Success
				if (response.retcode == STATUS_SUCCESS) { // Error message should have been tagged along
					$("#overlay-full").html("&nbsp;"); // Remove the spinner
					swal({
						title: "Submitted!", text: response.retdata, icon: "success"
					}).then(function() {
						$("#overlay-full").data("can-close-on-click", true); // Allow close on click
						$("#overlay-full").trigger("click");

						window.location.href = BASE_URI;
					});
					return;
				}

				// Should now show!
				console.log(response);
				$("#overlay-full").data("can-close-on-click", true); // Allow close on click
				$("#overlay-full").trigger("click");
				return;
			} // Received response
		}); // JQ.Ajax()
	}); // Click event on "Submit" button

	// keyup() event can handle backspace button!
	$("#resetpw-email").keyup(function() {
		if ($("#resetpw-email").parent().hasClass("error")) $("#resetpw-email").parent().removeClass("error");
	});
}


// Load <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
// before excuting page scripts.
const script = document.createElement('script');
script.src = "https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit";
script.async = true;
script.defer = true;
script.onload = pageForgotPwScripts();
document.body.append(script);