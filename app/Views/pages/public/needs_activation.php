<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<?php insert_hspace("3rem"); ?>
	<div id="page-msg-icon" class="justify-content-center" style="color:#f8bb86;"><i class="fas fa-clipboard-check"></i></div>
	<div id="page-msg-title">One more step needed!</div>
	<div id="page-msg-text">
		It seems that your account is <strong>not activated yet!</strong><br/>
		<br/>
		Please, check your email inbox (and spam folder) for a message from us.<br/>
		The message contains instructions on how to activate your account.<br/>
		<br/>
		If you cannot find the message, press the &quot;Re-send activation code&quot; button below.<br/>
		<br/>
		For any questions, please contact us at: <a href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>" class="link blue"><?= FREEZOZ_EMAIL_SUPPORT ?></a>.
	</div>

	<div id="page-msg-retbtn" class="justify-content-center-column">
		<button type="button" id="send-activationcode" class="solid large">Re-send activation code</button>
		<?php insert_hspace("1.5rem"); ?>
		<a href="<?= base_url() ?>" class="link blue" style="font-weight: bold">Ignore, and go to the Home page</a>
	</div>
	</div>
	<div style="color:rgba(0,0,0,0);">.</div>
</div>