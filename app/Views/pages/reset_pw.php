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
#field-title {
	font-size: 1.3rem;
	font-weight: bold;
	width: 100%;
	text-align: center;
}
#page-msg-text {
	width: 100%;
	font-size: 1.2rem;
	text-align: center;
	display: flex;
	flex-direction: column;
	align-items: center;
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
#list-container {
	display: flex;
	flex-direction: column;
	align-items: center;
}
</style>



<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<?php insert_hspace("3rem"); ?>
	<?php if ($bad_link) { ?>
		<div id="page-msg-icon" class="justify-content-center" style="color:#f8bb86;"><i class="fas fa-exclamation"></i></div>
		<div id="page-msg-title">Ooops!</div>
		<div id="page-msg-text">
			<div>It seems that you have <strong>followed an invalid link!</strong></div>
			<div>Please, click on the link that is provided with your email, or copy/paste it as it is, without modification or cut.</div>
			<?php insert_hspace("1rem"); ?>
			<div>Note: the request to reset your password is valid for one time only.<br/>So, if you have already reset your password using this link, you need to submit a new request.</div>
			<?php insert_hspace("1rem"); ?>
			<div>If you tried that already and it didn't work, please, leave us an email at: <a href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>" class="link blue"><?= FREEZOZ_EMAIL_SUPPORT ?></a>.</div>
		</div>

		<?php insert_hspace("5rem"); ?>
		<div class="justify-content-center">
			<button type="button" class="solid medium return-home">Return to the Home page</button>
		</div>
		<?php insert_hspace("5rem"); ?>
	<?php } elseif ($expired) { ?>
		<div id="page-msg-icon" class="justify-content-center" style="color:#f8bb86;"><i class="far fa-clock"></i></div>
		<div id="page-msg-title">Ooops!</div>
		<div id="page-msg-text">
			<div>Sorry, your link <strong>has expired!</strong></div>
			<?php insert_hspace("1rem"); ?>
			<div>If you still cannot remember your password, please</div>
			<div>click again on &quot;Forgot your password&quot; link in the sign-in form, and submit your request again.</div>
			<?php insert_hspace("1rem"); ?>
			<div id="list-container">
				<div style="text-align: left;">
					Make sure that you:
					<ol>
						<li>Click on the link in your email <strong>within the valid period</strong>, and</li>
						<li>Open the <strong>new</strong> email rather than the <strong>old</strong> one.</li>
					</ol>
				</div>
			</div>
			<?php insert_hspace("1rem"); ?>
			<div>If you tried that already and it didn't work, please, leave us an email at: <a href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>" class="link blue"><?= FREEZOZ_EMAIL_SUPPORT ?></a>.</div>
		</div>

		<?php insert_hspace("5rem"); ?>
		<div class="justify-content-center">
			<button type="button" class="solid medium return-home">Return to the Home page</button>
		</div>
		<?php insert_hspace("5rem"); ?>
	<?php } ?>

	<?php if (!$bad_link && !$expired) { ?>
		<div id="page-msg-icon" class="justify-content-center" style="color:#1fbf55;"><i class="fas fa-key"></i></div>
		<div id="page-msg-title">Create a new password</div>
		<div id="page-msg-text">
			<div id="field-title">Type a new password for your account and then click &quot;Save&quot; to continue:</div>
			<?php insert_hspace("2rem"); ?>

			<div class="edit-container">
				<div class="editinput size-l"> <!-- password -->
					<input type="password" placeholder="Type your password" id="resetpw-password" maxlength="<?= intval(FORM_PASSWORD_MAXLENGTH) + 1 ?>" />
					<i class="fas fa-key"></i>
					<div class="errmsg">This field cannot be empty!</div>
				</div>
			</div>
			<?php insert_hspace(".75rem"); ?>
			<div class="edit-container">
				<div class="editinput size-l"> <!-- pwconfirm -->
					<input type="password" placeholder="Confirm your password" id="resetpw-pwconfirm" maxlength="<?= intval(FORM_PASSWORD_MAXLENGTH) + 1 ?>" />
					<i class="fas fa-key"></i>
					<div class="errmsg">This field is required, and should be identical to the password!</div>
				</div>
			</div>

			<?php insert_hspace("5rem"); ?>
			<div class="justify-content-center">
				<button type="button" id="reset-save" class="solid large">&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;</button>
			</div>
			<?php insert_hspace("5rem"); ?>
		</div>
	<?php } ?>

	<div style="color:rgba(0,0,0,.1);">.</div>
</div>



<script type="text/javascript">
	const EMAIL = '<?= empty($email) ? "" : $email ?>';
	const RPW_CODE = '<?= empty($resetpw_code) ? "" : $resetpw_code ?>';
	const LOGGEDIN = <?= empty($user_loggedin) ? "false" : "true" ?>;
</script>
<script type="text/javascript">
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
			url: "/requests/reset_pw",
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
</script>