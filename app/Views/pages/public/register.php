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