<style type='text/css'>
.justify-content-center {
	width: 100%;
	display: flex;
	flex-direction: row;
	justify-content: center;
	text-align: center;
}
#page-msg-icon {
	font-size: 6rem;
	margin-bottom: 1.5rem;
}
#page-msg-title {
	width: 100%;
	font-size: 2.75rem;
	font-weight: bold;
	text-align: center;
	color: #757575;
	margin-bottom: 3rem;
}
#page-msg-text {
	width: 100%;
	font-size: 1.2rem;
	text-align: center;
	display: flex;
	flex-direction: column;
	align-items: center;
}
#field-title {
	font-size: 1.3rem;
	font-weight: bold;
	width: 100%;
	text-align: center;
}
#page-msg-text .edit-container {
	width: 50%;
	min-width: 20rem;
	max-width: 25rem;
}
@media all and (max-width: 800px) {
	#page-msg-text .edit-container {
		width: 95%;
		max-width: 25rem;
		min-width: 15rem;
	}
}
#page-msg-retbtn {
	margin-top: 2rem;
	margin-bottom: 3rem;
}
</style>
<script type="text/javascript">
var onloadCallback = function() {
	grecaptcha.render('google-recaptcha', {
		'sitekey': '6LdHvd8ZAAAAAFMgZKkFmVx8KkFZMBxdlGuGOYLj',
		'size': window.innerWidth < 650 ? 'compact' : 'normal'
	});
};
</script>



<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>
	<?php insert_hspace("3rem"); ?>
	<div id="page-msg-icon" class="justify-content-center" style="color:#1fbf55;"><i class="fas fa-key"></i></div>
	<div id="page-msg-title">Forgot your password?</div>
	<div id="page-msg-text">
		If you forgot your password, and you cannot log-in anymore,<br/>
		The only way to recover your account is by resetting your password.<br/>
		<br/>
		First, we need to make sure you have access to the email address that you registered with.<br/>
		<br/>
		<div id="field-title">Please, enter your email address (that you registered with) below:</div>
		<?php insert_hspace(".75rem"); ?>
		<div class="edit-container">
			<div class="editinput size-l"> <!-- email -->
				<input type="email" placeholder="Type your email address" id="resetpw-email" maxlength="<?= FORM_EMAIL_MAXLENGTH ?>" />
				<i class="fas fa-envelope"></i>
				<div class="errmsg">This field cannot be empty!</div>
			</div>
		</div>
		<?php insert_hspace(".5rem"); ?>
		<div id="google-recaptcha"></div>
		<?php insert_hspace("1.3rem"); ?>
		We will send a message to the email address you enter here (if it's found already registered with <?= FREEZOZ_NAME ?>.com).<br/>
		This message will contain instructions on how to reset your password.
	</div>

	<div id="page-msg-retbtn" class="justify-content-center">
		<button type="button" id="forgotpw_submit" class="solid large">&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;</button>
	</div>
	
	<div style="color:rgba(0,0,0,.01);">.</div>
</div>



<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
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
</script>