<footer style="padding: .75rem 0">
	<div id="foot-copy" style="margin-top:0">&copy; <?= date('Y') ?> Freezoz.com, All rights reserved.</div>
</footer>

<!-- JScript(s) -->
<?php
# Vendor
include_jscript("/vendor/bootstrap-4.6.0-dist/js/bootstrap.bundle." . (env('CI_ENVIRONMENT') == 'development' ? '' : 'min.') . "js");
include_jscript("/vendor/bs-custom-file-input-1.3.2/dist/bs-custom-file-input." . (env('CI_ENVIRONMENT') == 'development' ? '' : 'min.') . "js");
include_jscript("/vendor/sweetalert/sweetalert.min.js");
include_jscript("/vendor/summernote-0.8.18-dist/summernote-bs4." . (env('CI_ENVIRONMENT') == 'development' ? '' : 'min.') . "js");

# Common
include_jscript("/js/main.js");

# Additional (page-specific)
foreach ($add_js as $jsname) {
	include_jscript($jsname);
}
?>
</body>
</html>
