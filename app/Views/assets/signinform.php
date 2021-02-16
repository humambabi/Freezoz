<div id='signinform' class='popup-show'>
	<div class="back-bubble"></div>
	<div class="back-bubble"></div>
	<div class="back-bubble"></div>

	<div id="signin-close">
		<img src="<?= base_url() ?>/img/times.png" width="15" height="15" alt="Close" />
	</div>

	<div id="signinform-title">Sign in</div>
	<div>You can sign up using</div>

	<div class="social-container">
		<div id="socialbtn-facebook" class="social-item"><i class="fab fa-facebook-f"></i></div>
		<div id="socialbtn-google" class="social-item"><i class="fab fa-google"></i></div>
	</div>
	<?php insert_hspace("1.5rem"); ?>

	<div>Or login using your email address</div>
	<?= csrf_field() ?>
	<?php insert_hspace(".5rem"); ?>

	<div class="edit-container">
		<div class="editinput size-l"> <!-- email -->
			<input type="email" placeholder="Type your email address" id="signin-email" maxlength="<?= FORM_EMAIL_MAXLENGTH ?>" />
			<i class="fas fa-envelope"></i>
			<div class="errmsg">This field cannot be empty!</div>
		</div>
	</div>
	<div class="edit-container">
		<div class="editinput size-l"> <!-- password -->
			<input type="password" placeholder="Type your password" id="signin-password" maxlength="<?= intval(FORM_PASSWORD_MAXLENGTH) + 1 ?>" />
			<i class="fas fa-key"></i>
			<div class="errmsg">This field cannot be empty!</div>
		</div>
	</div>

	<div id="forget-password">Forgot your password? <a href="<?= base_url() ?>/forgot_pw" class="link blue">Click here</a></div>

	<?php insert_hspace("1rem"); ?>
	<div id="remember-me">
		<input type="checkbox" id="chk-remember-me" />
		<label for="chk-remember-me">Remember me</label>
	</div>
	<?php insert_hspace(".85rem"); ?>

	<button type="button" id="signinform-loginbtn" class="solid large">SIGN IN</button>

	<?php insert_hspace("0.9rem"); ?>
	<div>Don't have an account yet? <a href="<?= base_url() ?>/register" class="link blue">Register</a></div>
</div>



<script type="text/javascript">
function postData() {
	$.ajax({
		url: BASE_URI + "/requests/sign_in",
		type: 'post',
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		data: {
			email: $('#signin-email').val(),
			password: $('#signin-password').val(),
			remember: $('#chk-remember-me').prop('checked')
		},
		datatype: 'json',
		success: function(response) {
			// Leave spinner showing until processing the responce
			if (!isJson(response) || (isJson(response) && response.retcode == STATUS_BAD_REMEMBERME)) { // Should not happen unless server error happens or bad ajax was sent
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
				$("#overlay-full > i").remove();
				swal({
					text: response.retdata, icon: "warning"
				}).then(function() {
					// After closing the swal() message, re-show the signin form (with user data)
					$("#signinform").css("display", 'flex');
					$("#signinform").removeClass("popup-hide");
					$("#signinform").addClass("popup-show");
	
					$("#overlay-full").data("can-close-on-click", true); // Enable close on click (again)
				});
				return;
			}

			// OK, sign the user in
//			$("#overlay-full").html('<i style="color:#20bf55;border-radius:50%;background-color:rgb(255 255 255 / 0.75);" class="far fa-5x fa-check-circle"></i>');
//			setTimeout(function() {
				window.location.reload();
//			}, 1000);
		} // Received response
	}); // JQ.Ajax()
}

$("#signinform-loginbtn").click(function() {
	var elm, parent_elm, err_elm, value, bEmail = false, bPassword = false;

	// Validate "email"
	elm = document.getElementById("signin-email");
	parent_elm = elm.parentNode;
	err_elm = parent_elm.querySelector(".errmsg");
	value = elm.value.trim();
	elm.value = value;
	if (value.length < 1) {
		if (!isInViewport("signin-email")) scroll_page("signin-email");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
		err_elm.innerHTML = "This field cannot be empty!";
	} else if (!isEmail(value)) {
		if (!isInViewport("signin-email")) scroll_page("signin-email");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
		err_elm.innerHTML = "Not a valid email address!";
	} else {
		if (parent_elm.classList.contains("error")) parent_elm.classList.remove("error");
		bEmail = true;
	}

	// Validate "password"
	elm = document.getElementById("signin-password");
	parent_elm = elm.parentNode;
	value = elm.value; // No trim() here, and no re-apply value!
	if (!value.length) {
		if (!isInViewport("signin-password")) scroll_page("signin-password");
		if (parent_elm.classList.contains("error")) {
			parent_elm.classList.remove("error");
			void parent_elm.offsetWidth; // Restart the animation
		}
		parent_elm.classList.add("error");
	} else {
		if (parent_elm.classList.contains("error")) parent_elm.classList.remove("error");
		bPassword = true;
	}

	// Final check
	if (!bEmail || !bPassword) return;

	/* OK, send the form to the server */

	// Close the form
	$("#signinform").removeClass("popup-show");
	$("#signinform").addClass("popup-hide");
	$("#signinform").css("display", 'none'); // Needed in order to be able to use .append()

	// Prevent closing the overlay
	$("#overlay-full").data("can-close-on-click", false);

	// Show the spinner (after the form-closing animation finishes)
	setTimeout(function() {
		$("#overlay-full").append(ELM_SPINNER);
		postData();
	}, 150); // Same time out as the animation speed (of closing the form)
});

$("#signin-close").click(function() {
	$("#overlay-full").data("can-close-on-click", true); // Allow close on click
	$("#overlay-full").trigger("click");
});

// keyup() event can handle backspace button!
$("#signin-email").keyup(function() {
	if ($("#signin-email").parent().hasClass("error")) $("#signin-email").parent().removeClass("error");
});
$("#signin-password").keyup(function() {
	if ($("#signin-password").parent().hasClass("error")) $("#signin-password").parent().removeClass("error");
});

$('#signinform').on('keypress', function(e) {
	if (e.keyCode == 13) {
		e.preventDefault(); // On keyup -> error message is removed
		$('#signinform-loginbtn').trigger('click');
	}
});
</script>