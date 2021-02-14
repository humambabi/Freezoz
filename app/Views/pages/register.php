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

	<div id="reg-container">
		<div id="reg-img">
			<div id="img-responsive"></div>
		</div>

		<div id="reg-formcontainer">
			<?php insert_hspace("1rem"); ?>
			<div id="regform-title">Register</div>

			<div>You can sign up using</div>

			<div class="social-container">
			<div id="socialbtn-facebook" class="social-item"><i class="fab fa-facebook-f"></i></div>
				<div id="socialbtn-google" class="social-item"><i class="fab fa-google"></i></div>
			</div>
			<?php insert_hspace("1.5rem"); ?>

			<div>Or register using your email address</div>
			<?= csrf_field() ?>
			
			<?php insert_hspace("0.5rem"); ?>
			<div class="editinput size-l"> <!-- name -->
				<input type="text" placeholder="Type your name" id="regform-name" maxlength="<?= FORM_USERNAME_MAXLENGTH ?>" />
				<i class="fas fa-user" style="font-size:1.2rem"></i>
				<div class="errmsg">This field is required, and should consist of <?= FORM_USERNAME_MINLENGTH ?> or more characters!</div>
			</div>
			<?php insert_hspace("1rem"); ?>
			<div class="editinput size-l"> <!-- email -->
				<input type="email" placeholder="Type your email address" id="regform-email" maxlength="<?= FORM_EMAIL_MAXLENGTH ?>" />
				<i class="fas fa-envelope"></i>
				<div class="errmsg">This field is required, and should be a valid email address!</div>
			</div>
			<?php insert_hspace("1rem"); ?>
			<div class="editinput size-l"> <!-- password -->
				<input type="password" placeholder="Type your password" id="regform-password" maxlength="<?= intval(FORM_PASSWORD_MAXLENGTH) + 1 ?>" />
				<i class="fas fa-key"></i>
				<div class="errmsg">This field is required, and should be between <?= FORM_PASSWORD_MINLENGTH ?> and <?= FORM_PASSWORD_MAXLENGTH ?> characters!</div><!-- Check the following js code too for error msg -->
			</div>
			<?php insert_hspace("1rem"); ?>
			<div class="editinput size-l"> <!-- pwconfirm -->
				<input type="password" placeholder="Confirm your password" id="regform-pwconfirm" maxlength="<?= intval(FORM_PASSWORD_MAXLENGTH) + 1 ?>" />
				<i class="fas fa-key"></i>
				<div class="errmsg">This field is required, and should be identical to your password!</div>
			</div>

			<?php insert_hspace("1.35rem"); ?>
			<div id="terms-read-agree">
				<input type="checkbox" id="chk-terms-read-agree" />
				<label>
					I have read and I agree to the
					<a href="<?= base_url() ?>/terms" class="link blue" target="_blank">Terms of use&nbsp;<i class="fas fa-external-link-square-alt"></i></a> and
					<a href="<?= base_url() ?>/privacy" class="link blue" target="_blank">Privacy Policy&nbsp;<i class="fas fa-external-link-square-alt"></i></a>.
				</label>
			</div>
			<?php insert_hspace("1.5rem"); ?>

			<div id="google-recaptcha"></div>

			<?php insert_hspace("1.3rem"); ?>
			<button type="button" id="register-regbtn" class="solid large">REGISTER</button>
			<?php insert_hspace("2.5rem"); ?>
		</div>
	</div>
</div>



<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
$("#register-regbtn").click(function() {
	var elm, value, value2, parent_elm, err_elm, grr, bName = false, bEmail = false, bPassword = false, bTerms = false, bGR = false;

	// Validate "name"
	elm = document.getElementById("regform-name");
	parent_elm = elm.parentNode;
	value = elm.value.trim();
	elm.value = value;
	if (value.length < FORM_USERNAME_MINLENGTH) {
		if (!isInViewport("regform-name")) scroll_page("regform-name");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
	} else {
		if (parent_elm.classList.contains("error")) parent_elm.classList.remove("error");
		bName = true;
	}

	// Validate "email"
	elm = document.getElementById("regform-email");
	parent_elm = elm.parentNode;
	value = elm.value.trim();
	elm.value = value;
	if (!isEmail(value)) {
		if (!isInViewport("regform-email")) scroll_page("regform-email");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
	} else {
		if (parent_elm.classList.contains("error")) parent_elm.classList.remove("error");
		bEmail = true;
	}

	// Validate "password"
	elm = document.getElementById("regform-password");
	parent_elm = elm.parentNode;
	err_elm = parent_elm.querySelector(".errmsg");
	value = elm.value; // No trim() here, and no re-apply value!
	if (value.length < FORM_PASSWORD_MINLENGTH) {
		if (!isInViewport("regform-password")) scroll_page("regform-password");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
		err_elm.innerHTML = "This field is required, and should consist of " + FORM_PASSWORD_MINLENGTH + " or more characters!";
	} else if (value.length > FORM_PASSWORD_MAXLENGTH) {
		if (!isInViewport("regform-password")) scroll_page("regform-password");
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
	elm = document.getElementById("regform-pwconfirm");
	parent_elm = elm.parentNode;
	value2 = elm.value; // No trim() here, and no re-apply value!
	if ((value2 != value) || value2.length < FORM_PASSWORD_MINLENGTH) {
		if (!isInViewport("regform-pwconfirm")) scroll_page("regform-pwconfirm");
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

	// Check the terms & privacy agreement
	if (!$("#chk-terms-read-agree").prop('checked')) {
		swal({
			text: "You have to agree to the terms of use and privacy policy!",
			icon: "warning"
		});
		if (!isInViewport("chk-terms-read-agree")) scroll_page("chk-terms-read-agree");
		return;
	} else {
		bTerms = true;
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

	// Final check
	if (!bName || !bEmail || !bPassword || !bTerms || !bGR) return;


	// Show full screen overlay, with spinner
	if ($("#overlay-full").hasClass("hidden")) {
		$("#overlay-full").html(ELM_SPINNER);
		$("#overlay-full").removeClass("hidden");
	}

	$.ajax({
		url: "/requests/user_register",
		type: 'post',
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		data: {
			name: $("#regform-name").val(),
			email: $("#regform-email").val(),
			password: $("#regform-password").val(),
			conditions: $("#chk-terms-read-agree").prop('checked'),
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

			if (response.retcode == STATUS_USERNAME_INVALID || response.retcode == STATUS_USERNAME_EXISTS) { // Error message should have been tagged along
				// Remove the overlay
				$("#overlay-full").data("can-close-on-click", true); // Allow close on click
				$("#overlay-full").trigger("click");

				grecaptcha.reset();

				// Show the editbox-special error message
				elm = document.getElementById("regform-name");
				parent_elm = elm.parentNode;
				err_elm = parent_elm.querySelector(".errmsg");
				if (!isInViewport("regform-name")) scroll_page("regform-name");
				if (parent_elm.classList.contains("error")) {
					parent_elm.classList.remove("error");
					void parent_elm.offsetWidth; // Restart the animation
				}
				parent_elm.classList.add("error");
				err_elm.innerHTML = response.retdata;
				return;
			}

			if (response.retcode == STATUS_EMAIL_INVALID) { // Error message should have been tagged along
				// Remove the overlay
				$("#overlay-full").data("can-close-on-click", true); // Allow close on click
				$("#overlay-full").trigger("click");

				grecaptcha.reset();

				// Show the editbox-special error message
				elm = document.getElementById("regform-email");
				parent_elm = elm.parentNode;
				err_elm = parent_elm.querySelector(".errmsg");
				if (!isInViewport("regform-email")) scroll_page("regform-email");
				if (parent_elm.classList.contains("error")) {
					parent_elm.classList.remove("error");
					void parent_elm.offsetWidth; // Restart the animation
				}
				parent_elm.classList.add("error");
				err_elm.innerHTML = response.retdata;
				return;
			}

			if (response.retcode == STATUS_EMAIL_EXISTS) { // Error message should have been tagged along
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

			if (response.retcode == STATUS_PASSWORD_INVALID) { // Error message should have been tagged along
				// Remove the overlay
				$("#overlay-full").data("can-close-on-click", true); // Allow close on click
				$("#overlay-full").trigger("click");

				grecaptcha.reset();

				// Show the editbox-special error message
				elm = document.getElementById("regform-password");
				parent_elm = elm.parentNode;
				err_elm = parent_elm.querySelector(".errmsg");
				if (!isInViewport("regform-password")) scroll_page("regform-password");
				if (parent_elm.classList.contains("error")) {
					parent_elm.classList.remove("error");
					void parent_elm.offsetWidth; // Restart the animation
				}
				parent_elm.classList.add("error");
				err_elm.innerHTML = response.retdata;
				return;
			}

			if (response.retcode == STATUS_TERMS_INVALID || response.retcode == STATUS_RECAPTCHA_INVALID) { // Error message should have been tagged along
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

			if (response.retcode == STATUS_ACTEMAIL_FAILED) { // Error message should have been tagged along
				$("#overlay-full").html("&nbsp;"); // Remove the spinner
				swal({
					text: response.retdata, icon: "warning"
				}).then(function() {
					$("#overlay-full").data("can-close-on-click", true); // Allow close on click
					$("#overlay-full").trigger("click");
					
					window.location.href = BASE_URI;
				});
				return;
			}

			// Success
			if (response.retcode == STATUS_SUCCESS) { // Error message should have been tagged along
				$("#overlay-full").html("&nbsp;"); // Remove the spinner
				swal({
					title: "Thank you for registration", text: response.retdata, icon: "success"
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
}); // Click event on "Register" button

// keyup() event can handle backspace button!
$("#regform-name").keyup(function() {
	if ($("#regform-name").parent().hasClass("error")) $("#regform-name").parent().removeClass("error");
});
$("#regform-email").keyup(function() {
	if ($("#regform-email").parent().hasClass("error")) $("#regform-email").parent().removeClass("error");
});
$("#regform-password").keyup(function() {
	if ($("#regform-password").parent().hasClass("error")) $("#regform-password").parent().removeClass("error");
});
$("#regform-pwconfirm").keyup(function() {
	if ($("#regform-pwconfirm").parent().hasClass("error")) $("#regform-pwconfirm").parent().removeClass("error");
});
</script>