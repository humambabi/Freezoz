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
	<link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?= '/vendor/fontawesome-free/css/all.' . (env('CI_ENVIRONMENT') == 'development' ? 'min.' : '') . 'css' ?>">
	
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/burger.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/components.css">

	<?php # Include JQuery library
	if (env('CI_ENVIRONMENT') == 'development') {
		echo '<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>';
	} else {
		echo '<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>';
	}
	echo PHP_EOL;
	?>

<?php # Convert PHP constants to JS (Password's min & max limits are defined in the sign-in form)
	echo "<script type='text/javascript'>" . PHP_EOL;
	echo "\tconst BASE_URI = '" . base_url() . "';" . PHP_EOL;
	echo "</script>" . PHP_EOL;
?>
</head>
<body>