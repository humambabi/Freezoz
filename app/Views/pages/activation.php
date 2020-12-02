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
	font-size: 3rem;
	font-weight: bold;
	text-align: center;
	color: #757575;
	margin-bottom: 3rem;
}
#page-msg-text {
	width: 100%;
	font-size: 1.2rem;
	font-weight: lighter;
	text-align: center;
}
#page-msg-retbtn {
	margin-top: 5rem;
	margin-bottom: 5rem;
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
			It seems that you have <strong>followed an invalid link!</strong><br/>
			Please, click on the link that is provided with your email, or copy/paste it as it is, without modification or cut.<br/>
			<br/>
			If you tried that already and it didn't work, please, leave us an email at: <a href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>" class="link blue"><?= FREEZOZ_EMAIL_SUPPORT ?></a>.
		</div>
	<?php } else { ?>
		<?php if ($already_activated) { ?>
			<div id="page-msg-icon" class="justify-content-center" style="color:#1fbf55;"><i class="far fa-check-circle"></i></div>
			<div id="page-msg-title">Your account is already activated!</div>
			<div id="page-msg-text">
				Your account is <strong>already activated!</strong> You don't need to do this more than once.<br/>
				<br/>
				You can log-in using your email and password.<br/>
				<br/>
				Thank you!
			</div>
		<?php } else { ?>
			<?php if ($expired) { ?>
				<div id="page-msg-icon" class="justify-content-center" style="color:#f8bb86;"><i class="far fa-clock"></i></div>
				<div id="page-msg-title">Ooops!</div>
				<div id="page-msg-text">
					Sorry, your link <strong>has expired!</strong><br/>
					<br/>
					Please log-in, go to your profile page, and click on the "re-send activation email" button.<br/>
					<br/>
					<div id="list-container">
						<div style="text-align: left;">
							Make sure that you:
							<ol>
								<li>Click on the link in your email <strong>within the valid period</strong>, and</li>
								<li>Open the <strong>new</strong> email rather than the <strong>old</strong> one.</li>
							</ol>
						</div>
					</div>
					<br/>
					If you tried that already and it didn't work, please, leave us an email at: <a href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>" class="link blue"><?= FREEZOZ_EMAIL_SUPPORT ?></a>.
				</div>
			<?php } else { ?>
				<?php if ($act_status) { ?>
					<div id="page-msg-icon" class="justify-content-center" style="color:#1fbf55;"><i class="far fa-check-circle"></i></div>
					<div id="page-msg-title">Thank you!</div>
					<div id="page-msg-text">
						Your account was <strong>activated successfully!</strong><br/>
						<br/>
						You can now log-in using your email and password.<br/>
						<br/>
						Enjoy Freezoz!
					</div>
				<?php } else { ?>
					<div id="page-msg-icon" class="justify-content-center" style="color:#f8bb86;"><i class="fas fa-exclamation"></i></div>
					<div id="page-msg-title">Ooops!</div>
					<div id="page-msg-text">
						Some error occurred, and we <strong>could not activate</strong> your account!<br/>
						Please, try again later.<br/>
						<br/>
						If you already tried many times and it didn't work, please, leave us an email at: <a href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>" class="link blue"><?= FREEZOZ_EMAIL_SUPPORT ?></a>.
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	<div id="page-msg-retbtn" class="justify-content-center">
		<button type="button" id="return-home" class="solid medium"><?= empty($act_status) ? "Return" : "Go" ?>&nbsp;to the Home page</button>
	</div>
	<div style="color:rgba(0,0,0,0);">.</div>
</div>



<script type="text/javascript">
	$("#return-home").click(function() { window.location.href = BASE_URI; });
</script>