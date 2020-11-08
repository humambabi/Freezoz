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
			<?php insert_hspace("0.5rem"); ?>
			<div class="inpname_container">
				<input type="text" class="inpname" placeholder="Type your name" />
				<i class="fas fa-user"></i>
			</div>
			<?php insert_hspace("0.5rem"); ?>
			<div class="inpemail_container">
				<input type="email" class="inpemail" placeholder="Type your email address" />
				<i class="fas fa-envelope"></i>
			</div>
			<?php insert_hspace("0.5rem"); ?>
			<div class="inppassword_container">
				<input type="password" class="inppassword" placeholder="Type your password" />
				<i class="fas fa-key"></i>
			</div>
			<?php insert_hspace("0.5rem"); ?>
			<div class="inppassword_container">
				<input type="password" class="inppassword" placeholder="Confirm your password" />
				<i class="fas fa-key"></i>
			</div>

			<?php insert_hspace("1.35rem"); ?>
			<div id="terms-read-agree">
				<input type="checkbox" id="chk-terms-read-agree" />
				<label>
					I have read and I agree to the
					<a href="<?= esc("$base_uri/terms"); ?>" class="link blue" target="_blank">Terms of use&nbsp;<i class="fas fa-external-link-square-alt"></i></a> and
					<a href="<?= esc("$base_uri/privacy"); ?>" class="link blue" target="_blank">Privacy Policy&nbsp;<i class="fas fa-external-link-square-alt"></i></a>.
				</label>
			</div>
			<?php insert_hspace("1.35rem"); ?>

			<div id="google-recaptcha"></div>

			<?php insert_hspace("1.3rem"); ?>
			<button type="button" id="register-regbtn" class="btn-solid-lrg">REGISTER</button>
			<?php insert_hspace("2.5rem"); ?>
		</div>
	</div>
</div>

<!--
grecaptcha.getResponse(opt_widget_id)
-->

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
