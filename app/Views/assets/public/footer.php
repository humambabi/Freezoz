<footer>
	<div id="foot-foot">
		<div id="foot-logodesc">
			<div id="foot-logo-gototop">
				<img alt="logo" width="144" height="36" src="<?= base_url() ?>/img/footer-logo.png" />
				<div class="foot-gototop"><i class="far fa-arrow-alt-circle-up fa-2x"></i></div>
			</div>

			<?php insert_hspace("0.5rem"); ?>
			<div id="footer-desc">Freezoz is your ultimate source of free After Effects&reg; templates. It contains nearly limitless counts of templates that you can download. Register today to enjoy the full features on Freezoz.com.</div>
		</div>
		<div id="foot-links">
			<div id="foot-links-title">Links:</div>
			<div id="foot-links-links">
				<?php if ($is_home) { ?>
				<a class="link white" id="footer-categories" href="javascript:void(0);">Categories</a>
				<?php } else { ?>
				<a class="link white" id="footer-home" href="<?= base_url() ?>">Home</a>
				<?php } ?>
				&nbsp;&bull;&nbsp;
				<a class="link white" href="#">FAQs</a>
				&nbsp;&bull;&nbsp;
				<a class="link white" href="<?= base_url() ?>/terms">Terms</a>
				&nbsp;&bull;&nbsp;
				<a class="link white" href="<?= base_url() ?>/privacy">Privacy</a>
			</div>
			<br/>
			<div id="foot-contactus-title">Contact us:</div>
			<div id="foot-links-contactus"><a class="link white" href="mailto:support@freezoz.com">support@freezoz.com</a></div>
		</div>
		<div class="foot-gototop"><i class="far fa-arrow-alt-circle-up fa-2x"></i></div>
	</div>
	<div id="foot-copy">&copy; <?= date('Y') ?> Freezoz.com, All rights reserved.</div>
</footer>

<!-- JScript(s) -->
<?php
# Vendor
include_jscript("/vendor/sweetalert/sweetalert.min.js");

# Common
include_jscript("/js/main.js");

# Additional (page-specific)
foreach ($add_js as $jsname) {
	include_jscript($jsname);
}
?>
</body>
</html>