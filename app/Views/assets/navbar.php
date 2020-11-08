<nav id="navbar">
	<div id="navbar-container" class="noselect">
		<div id="navbar-logoburger">
			<div id="navbar-burger">
				<button class="hamburger hamburger--squeeze" type="button" id="btn-burger">
					<span class="hamburger-box"><span class="hamburger-inner"></span></span>
					<div class="hamburger-label">MENU</div>
				</button>
			</div>
			<div id="navbar-logo"><a href="<?= esc($base_uri); ?>"><img alt="logo" width="191" height="48" src="img/nav-logo.png" /></a></div>
		</div>

		<div id="navbar-menu">
			<?php if ($is_home) { ?>
			<a class="navbar-navitem" href="javascript:void(0);" id="navbar-categories">CATEGORIES<div class="navbar-itemselection"></div></a>
			<?php } else { ?>
			<a class="navbar-navitem" href="<?= esc($base_uri); ?>" id="navbar-home">HOME<div class="navbar-itemselection"></div></a>
			<?php } ?>

			<a class="navbar-navitem" href="#">FAQs<div class="navbar-itemselection"></div></a>
			<a class="navbar-navitem" href="mailto:support@freezoz.com">CONTACT US<div class="navbar-itemselection"></div></a>
			<div class="navbar-navitemsep"><div class="navbar-navitemsep-inner"></div></div>
			<button type="button" id="navbar-signinbtn" class="btn-outline-med">SIGN IN</button>
		</div>
	</div>
</nav>

<div class="overlay hidden" id="overlay-full"></div>
