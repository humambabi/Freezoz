<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">

	<title>Freezoz | Admin's Control Panel</title>
	<meta name="description" content="Freezoz is the free source of AE templates">
	<meta name="keywords" content="Freezoz, free templates, AE templates, AfterEffects templates">

	<link rel="icon" type="image/x-icon" href="<?= base_url() ?>/favicon.ico">
	<link rel="shortcut icon" href="<?= base_url() ?>/favicon.png">
	<link rel="apple-touch-icon" href="<?= base_url() ?>/favicon.png">
	<link rel="apple-touch-icon-precomposed" href="<?= base_url() ?>/favicon.png">

	<!-- STYLES -->
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
	<script>WebFont.load({google: {families: ['Montserrat:200,400,500,600,700,800,900']}});</script>
	<?php
	# Vendor
	include_css("/vendor/fontawesome-free/css/all." . (env('CI_ENVIRONMENT') == "development" ? "" : "min.") . "css");
	include_css("/vendor/bootstrap-4.6.0-dist/css/bootstrap." . (env('CI_ENVIRONMENT') == 'development' ? '' : 'min.') . "css");
	include_css("/vendor/summernote-0.8.18-dist/summernote-bs4." . (env('CI_ENVIRONMENT') == 'development' ? '' : 'min.') . "css");

	# Common
	include_css("/css/main.css");
	include_css("/css/burger.css");
	include_css("/css/components.css");

	# Additional (page-specific)
	foreach ($add_css as $cssname) {
		include_css($cssname);
	}
	
	# Include JQuery library
	echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery." . (env('CI_ENVIRONMENT') == 'development' ? '' : 'min.') . "js'></script>" . PHP_EOL;
	?>

	<?php # Convert PHP constants to JS (Password's min & max limits are defined in the sign-in form)
	echo "<script type='text/javascript'>" . PHP_EOL;
	echo "\tconst BASE_URI = '" . base_url() . "';" . PHP_EOL;
	echo "\tconst STATUS_SUCCESS = " . STATUS_SUCCESS . ";" . PHP_EOL;
	echo "</script>" . PHP_EOL;
	?>
</head>
<body>