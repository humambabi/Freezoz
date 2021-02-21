<nav id="navbar">
	<div id="navbar-container" class="noselect">
		<div id="navbar-logoburger">
			<div id="navbar-burger">
				<button class="hamburger hamburger--squeeze" type="button" id="btn-burger">
					<span class="hamburger-box"><span class="hamburger-inner"></span></span>
					<div class="hamburger-label">MENU</div>
				</button>
			</div>
			<div id="navbar-logo"><a href="<?= base_url() ?>"><img alt="logo" width="191" height="48" src="<?= base_url() ?>/img/nav-logo-admin.png" /></a></div>
		</div>

		<div id="navbar-menu">
			<a class="navbar-navitem<?= $page == 'dashboard' ? " selected" : "" ?>" href="<?= base_url() ?>/admincp/dashboard" id="navbar-categories">DASHBOARD<div class="navbar-itemselection"></div></a>
			<a class="navbar-navitem<?= $page == 'items' ? " selected" : "" ?>" href="<?= base_url() ?>/admincp/items">ITEMS<div class="navbar-itemselection"></div></a>

			<div class="navbar-navitemsep"><div class="navbar-navitemsep-inner"></div></div>

			<button type="button" class="solid medium" onclick="window.location.href = BASE_URI">Back to Freezoz</button>
		</div>
	</div>
</nav>

<div class="overlay hidden" id="overlay-full"></div>