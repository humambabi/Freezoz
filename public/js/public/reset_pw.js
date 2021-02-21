/* ----------------------------------------------
Reset PW page
---------------------------------------------- */

$(".return-home").click(function() { window.location.href = BASE_URI; }); // For *ALL* "return-home" buttons

$(window).on('load', function() {
	if (LOGGEDIN) { // This will happen only if other checks passed
		swal({
			title: "Wait!",
			text: "You are already signed in.\r\nResetting your password will sign you out!\r\n\r\nDo you still want to reset your password?",
			icon: "warning",
			dangerMode: true,
			closeOnEsc: false,
			closeOnClickOutside: false,
			buttons: {
				cancel: "No, return to the home page",
				confirm: "Yes, continue"
			}
		}).then((value) => {
			// value of 'cancel' button is null
			if (!value) window.location.href = BASE_URI;
		});
	}
});

$("#reset-save").click(function() {
	var elm, parent_elm, err_elm, value, bPassword = false;

	// Validate "password"
	elm = document.getElementById("resetpw-password");
	parent_elm = elm.parentNode;
	err_elm = parent_elm.querySelector(".errmsg");
	value = elm.value; // No trim() here, and no re-apply value!
	if (value.length < FORM_PASSWORD_MINLENGTH) {
		if (!isInViewport("resetpw-password")) scroll_page("resetpw-password");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
		err_elm.innerHTML = "This field is required, and should consist of " + FORM_PASSWORD_MINLENGTH + " or more characters!";
	} else if (value.length > FORM_PASSWORD_MAXLENGTH) {
		if (!isInViewport("resetpw-password")) scroll_page("resetpw-password");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
		err_elm.innerHTML = "This field is required, and should be no more than " + FORM_PASSWORD_MAXLENGTH + " characters!";
	} else {
		if (parent_elm.classList.contains("error")) parent_elm.classList.remove("error");
	}

	// Validate "password confirm"
	elm = document.getElementById("resetpw-pwconfirm");
	parent_elm = elm.parentNode;
	value2 = elm.value; // No trim() here, and no re-apply value!
	if ((value2 != value) || value2.length < FORM_PASSWORD_MINLENGTH) {
		if (!isInViewport("resetpw-pwconfirm")) scroll_page("resetpw-pwconfirm");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
		return;
	} else {
		if (parent_elm.classList.contains("error")) parent_elm.classList.remove("error");
		bPassword = true;
	}

	// Final check
	if (!bPassword) return;

	// Show full screen overlay, with spinner
	if ($("#overlay-full").hasClass("hidden")) {
		$("#overlay-full").html(ELM_SPINNER);
		$("#overlay-full").removeClass("hidden");
	}

	$.ajax({
		url: BASE_URI + "/requests/reset_pw",
		type: 'post',
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		data: {
			email: EMAIL,
			password: $("#resetpw-password").val(),
			rpw_code: RPW_CODE
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

			if (response.retcode == STATUS_EMAIL_INVALID || response.retcode == STATUS_RESETPWCODE_INVALID || response.retcode == STATUS_GENERROR) { // Error message should have been tagged along
				$("#overlay-full").html("&nbsp;"); // Remove the spinner
				swal({
					title: "Wait!", text: response.retdata, icon: "warning"
				}).then(function() {
					$("#overlay-full").data("can-close-on-click", true); // Allow close on click
					$("#overlay-full").trigger("click");
				});
				return;
			}

			if (response.retcode == STATUS_PASSWORD_INVALID) { // Error message should have been tagged along
				// Remove the overlay
				$("#overlay-full").data("can-close-on-click", true); // Allow close on click
				$("#overlay-full").trigger("click");

				// Show the editbox-special error message
				elm = document.getElementById("resetpw-password");
				parent_elm = elm.parentNode;
				err_elm = parent_elm.querySelector(".errmsg");
				if (!isInViewport("resetpw-password")) scroll_page("resetpw-password");
				if (parent_elm.classList.contains("error")) {
					parent_elm.classList.remove("error");
					void parent_elm.offsetWidth; // Restart the animation
				}
				parent_elm.classList.add("error");
				err_elm.innerHTML = response.retdata;
				return;
			}

			// Success
			if (response.retcode == STATUS_SUCCESS) { // Error message should have been tagged along
				/*
				The backend should have signed the user out by now
				*/
				$("#overlay-full").html("&nbsp;"); // Remove the spinner
				swal({
					title: "Success!", text: response.retdata, icon: "success"
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
		} // Response
	}); // Ajax
}); // Click

// keyup() event can handle backspace button!
$("#resetpw-password").keyup(function() {
	if ($("#resetpw-password").parent().hasClass("error")) $("#resetpw-password").parent().removeClass("error");
});
$("#resetpw-pwconfirm").keyup(function() {
	if ($("#resetpw-pwconfirm").parent().hasClass("error")) $("#resetpw-pwconfirm").parent().removeClass("error");
});