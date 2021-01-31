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
</style>



<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<?php insert_hspace("3rem"); ?>
	<div id="page-msg-icon" class="justify-content-center" style="color:#f8bb86;"><i class="far fa-sad-tear"></i></div>
	<div id="page-msg-title">404<br/>Ooops!</div>
	<div id="page-msg-text">
		<div>It seems that you have <strong>followed an invalid link!</strong><br/>The page you were looking for <strong>doesn't exist!</strong></div>
		<?php insert_hspace("1rem"); ?>
		<div>Please, double-check that you have followed the correct link.</div>
		<?php insert_hspace("1rem"); ?>
		<div>If you tried that already and it didn't work, please, leave us an email at: <a href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>" class="link blue"><?= FREEZOZ_EMAIL_SUPPORT ?></a>.</div>
	</div>

	<?php insert_hspace("5rem"); ?>
	<div class="justify-content-center">
		<button type="button" class="solid large return-home">Return to the Home page</button>
	</div>
	<?php insert_hspace("5rem"); ?>
	
	<div style="color:rgba(0,0,0,.1);">.</div>
</div>



<script type="text/javascript">
	$(".return-home").click(function() { window.location.href = BASE_URI; });
</script>