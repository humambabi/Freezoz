<nav id="navbar">
	<div id="navbar-container" class="noselect">
		<div id="navbar-logoburger">
			<div id="navbar-burger">
				<button class="hamburger hamburger--squeeze" type="button" id="btn-burger">
					<span class="hamburger-box"><span class="hamburger-inner"></span></span>
					<div class="hamburger-label">MENU</div>
				</button>
			</div>
			<div id="navbar-logo"><a href="<?= esc($base_uri) ?>"><img alt="logo" width="191" height="48" src="<?= esc($base_uri) ?>/img/nav-logo.png" /></a></div>
		</div>

		<div id="navbar-menu">
			<?php if ($is_home) { ?>
			<a class="navbar-navitem" href="javascript:void(0);" id="navbar-categories">CATEGORIES<div class="navbar-itemselection"></div></a>
			<?php } else { ?>
			<a class="navbar-navitem" href="<?= esc($base_uri) ?>" id="navbar-home">HOME<div class="navbar-itemselection"></div></a>
			<?php } ?>

			<a class="navbar-navitem" href="#">FAQs<div class="navbar-itemselection"></div></a>
			<a class="navbar-navitem" href="mailto:<?= FREEZOZ_EMAIL_SUPPORT ?>">CONTACT US<div class="navbar-itemselection"></div></a>
			<div class="navbar-navitemsep"><div class="navbar-navitemsep-inner"></div></div>

			<?php 
			$user_id = session(SESSION_USERID);
			if (empty($user_id)) {
			?>
				<button type="button" id="navbar-signinbtn" class="btn-outline-med">SIGN IN</button>
			<?php
			} else {
			?>
				<div id="navbar-userbtn"><i class="fas fa-user-circle"></i></div>
			<?php
			}
			?>
		</div>
	</div>
</nav>

<div class="overlay hidden" id="overlay-full"></div>

<div id="usermenu-container">
	<div class="usermenu-item"><i class="fas fa-user"></i>My Account</div>
	<div class="usermenu-item" type="separator"><div class="usermenu-separator"></div></div>
	<div class="usermenu-item" id="usermenu_signout"><i class="fas fa-sign-out-alt"></i>Sign Out</div>
</div>