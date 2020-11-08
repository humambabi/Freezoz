<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">

	<title>FreeZoz | Your free source of AE templates!</title>
	<meta name="description" content="FreeZoz is the free source of AE templates">
	<meta name="keywords" content="FreeZoz, free templates, AE templates, AfterEffects templates">

	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<link rel="shortcut icon" href="favicon.png">
	<link rel="apple-touch-icon" href="favicon.png">
	<link rel="apple-touch-icon-precomposed" href="favicon.png">

	<!-- STYLES -->
	<link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/burger.css">

	<!-- Manifest file of 'Progressive app' for Google Chrome browsers -->
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#C3C3C3">

	<!-- IE 10 Metro tile icon -->
	<meta name="msapplication-TileColor" content="#C3C3C3">
	<meta name="msapplication-TileImage" content="favicon.png">

	<!-- IE 11 Tile for Windows 8.1 Start Screen -->
	<meta name="application-name" content="FreeZoz">
	<meta name="msapplication-tooltip" content="FreeZoz, your free source of AE templates!">
	<meta name="msapplication-config" content="ieconfig.xml">

	<!-- Localized versions ->
	<link rel="alternate" href="https://www.filezigzag.com/online-converter" hreflang="x-default" />
	<link rel="alternate" href="https://www.filezigzag.com/online-converter" hreflang="en" /> <!- No need for region code ->
	-->

	<!-- Canonical links -->
	<!--<link rel="canonical" href="https://www.filezigzag.com/online-converter" />-->

	<!-- Global site tag (gtag.js) - Google Analytics ->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130660809-3"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() { dataLayer.push(arguments); }
		gtag('js', new Date());
		gtag('config', 'UA-130660809-3');
	</script>
	-->

	<?php # Include JQuery library
	if (env('CI_ENVIRONMENT') == 'development') {
		echo '<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>';
	} else {
		echo '<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>';
	}
	echo PHP_EOL;
	?>
	<?php # Include Font-Awesome library
	echo '<script src="https://kit.fontawesome.com/4ebc6d856a.js" crossorigin="anonymous"></script>';
	echo PHP_EOL;
	?>
	<?='<script type="text/javascript">' . PHP_EOL .
	"\t\t" . 'var SITE_ROOT = "' . esc($base_uri) . '";' . PHP_EOL .
	"\t" . '</script>'. PHP_EOL ?>
</head>
<body>