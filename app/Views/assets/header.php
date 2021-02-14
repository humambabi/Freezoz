<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">

	<title>Freezoz | Your free source of AE templates!</title>
	<meta name="description" content="Freezoz is the free source of AE templates">
	<meta name="keywords" content="Freezoz, free templates, AE templates, AfterEffects templates">

	<link rel="icon" type="image/x-icon" href="<?= base_url() ?>/favicon.ico">
	<link rel="shortcut icon" href="<?= base_url() ?>/favicon.png">
	<link rel="apple-touch-icon" href="<?= base_url() ?>/favicon.png">
	<link rel="apple-touch-icon-precomposed" href="<?= base_url() ?>/favicon.png">

	<!-- STYLES -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/burger.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/components.css">

	<!-- Manifest file of 'Progressive app' for Google Chrome browsers -->
	<link rel="manifest" href="<?= base_url() ?>/manifest.json">
	<meta name="theme-color" content="#C3C3C3">

	<!-- IE 10 Metro tile icon -->
	<meta name="msapplication-TileColor" content="#C3C3C3">
	<meta name="msapplication-TileImage" content="<?= base_url() ?>/favicon.png">

	<!-- IE 11 Tile for Windows 8.1 Start Screen -->
	<meta name="application-name" content="FreeZoz">
	<meta name="msapplication-tooltip" content="FreeZoz, your free source of AE templates!">
	<meta name="msapplication-config" content="<?= base_url() ?>/ieconfig.xml">

	<!-- Localized versions ->
	<link rel="alternate" href="https://www.filezigzag.com/online-converter" hreflang="x-default" />
	<link rel="alternate" href="https://www.filezigzag.com/online-converter" hreflang="en" /> <!- No need for region code ->
	-->

	<!-- Canonical links -->
	<!--<link rel="canonical" href="https://www.filezigzag.com/online-converter" />-->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-S5S0GBS4WT"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-S5S0GBS4WT');
	</script>

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

	<?php # Convert PHP constants to JS (Password's min & max limits are defined in the sign-in form)
	echo "<script type='text/javascript'>" . PHP_EOL;
	echo "\tconst BASE_URI = '" . base_url() . "';" . PHP_EOL;
	echo "\tconst FORM_USERNAME_MINLENGTH = " . FORM_USERNAME_MINLENGTH . ";" . PHP_EOL;
	echo "\tconst FORM_EMAIL_MAXLENGTH = " . FORM_EMAIL_MAXLENGTH . ";" . PHP_EOL;
	echo "\tconst FORM_PASSWORD_MINLENGTH = " . FORM_PASSWORD_MINLENGTH . ";" . PHP_EOL;
	echo "\tconst FORM_PASSWORD_MAXLENGTH = " . FORM_PASSWORD_MAXLENGTH . ";" . PHP_EOL;
	echo "\tconst STATUS_SUCCESS = " . STATUS_SUCCESS . ";" . PHP_EOL;
	echo "\tconst STATUS_GENERROR = " . STATUS_GENERROR . ";" . PHP_EOL;
	echo "\tconst STATUS_USERNAME_INVALID = " . STATUS_USERNAME_INVALID . ";" . PHP_EOL;
	echo "\tconst STATUS_USERNAME_EXISTS = " . STATUS_USERNAME_EXISTS . ";" . PHP_EOL;
	echo "\tconst STATUS_EMAIL_INVALID = " . STATUS_EMAIL_INVALID . ";" . PHP_EOL;
	echo "\tconst STATUS_EMAIL_EXISTS = " . STATUS_EMAIL_EXISTS . ";" . PHP_EOL;
	echo "\tconst STATUS_PASSWORD_INVALID = " . STATUS_PASSWORD_INVALID . ";" . PHP_EOL;
	echo "\tconst STATUS_TERMS_INVALID = " . STATUS_TERMS_INVALID . ";" . PHP_EOL;
	echo "\tconst STATUS_RECAPTCHA_INVALID = " . STATUS_RECAPTCHA_INVALID . ";" . PHP_EOL;
	echo "\tconst STATUS_ACTEMAIL_FAILED = " . STATUS_ACTEMAIL_FAILED . ";" . PHP_EOL;
	echo "\tconst STATUS_BAD_REMEMBERME = " . STATUS_BAD_REMEMBERME . ";" . PHP_EOL;
	echo "\tconst STATUS_RESETPWCODE_INVALID = " . STATUS_RESETPWCODE_INVALID . ";" . PHP_EOL;
	echo "</script>" . PHP_EOL;
	?>
</head>
<body>