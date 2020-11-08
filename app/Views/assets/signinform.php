<div id='signinform' class='popup-show'>
	<div id="signinform-title">Sign in</div>
	<div>You can sign up using</div>

	<div class="social-container">
		<div id="socialbtn-facebook" class="social-item"><i class="fab fa-facebook-f"></i></div>
		<div id="socialbtn-google" class="social-item"><i class="fab fa-google"></i></div>
	</div>
	<?php insert_hspace("1.5rem"); ?>

	<div>Or login using your email address</div>
	<?php insert_hspace("0.5rem"); ?>
	<div class="inpemail_container">
		<input type="email" class="inpemail" placeholder="Type your email address" />
		<i class="fas fa-envelope"></i>
	</div>
	<div class="inppassword_container">
		<input type="password" class="inppassword" placeholder="Type your password" />
		<i class="fas fa-key"></i>
	</div>


	<div id="forget-password">Forgot your password? <a href="#" class="link blue">Click here</a></div>
	<?php insert_hspace("1.35rem"); ?>
	<div id="remember-me">
		<input type="checkbox" id="chk-remember-me" />
		<label for="chk-remember-me">Remember me</label>
	</div>
	<?php insert_hspace("0.5rem"); ?>

	<button type="button" id="signinform-loginbtn" class="btn-solid-lrg">SIGN IN</button>

	<?php insert_hspace("1.3rem"); ?>
	<div>Don't have an account yet? <a href="<?= esc("$base_uri/register"); ?>" class="link blue">Register</a></div>
	<!--<i class="fas fa-sync-alt fa-3x fa-spin"></i>-->
</div>
