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