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
			<div>Note: the request to reset your password is valid for <strong>one time only</strong>.<br/>So, if you have already reset your password using this link, you need to submit a new request.</div>
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