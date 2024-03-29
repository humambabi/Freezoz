<?php

//--------------------------------------------------------------------
// App Namespace
//--------------------------------------------------------------------
// This defines the default Namespace that is used throughout
// CodeIgniter to refer to the Application directory. Change
// this constant to change the namespace that all application
// classes should use.
//
// NOTE: changing this will require manually modifying the
// existing namespaces of App\* namespaced-classes.
//
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
|--------------------------------------------------------------------------
| Composer Path
|--------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
|--------------------------------------------------------------------------
| Freezoz-specific constants
|--------------------------------------------------------------------------
*/

defined('FREEZOZ_NAME')										|| define('FREEZOZ_NAME', "Freezoz"); # Project name (only for display)
defined('FREEZOZ_LIVE_BASEURL')							|| define('FREEZOZ_LIVE_BASEURL', "https://www.freezoz.com"); # The base_uri of the live version (only for display - not for the app)
defined('FREEZOZ_LIVE_BASEURL_NOPROTO')				|| define('FREEZOZ_LIVE_BASEURL_NOPROTO', "www.freezoz.com"); # The base_uri of the live version - without protocol
defined('FREEZOZ_EMAIL_SUPPORT')							|| define('FREEZOZ_EMAIL_SUPPORT', "support@freezoz.com"); # The support email address of Freezoz

defined('VALIDATION_EMAILCODE_LENGTH')					|| define('VALIDATION_EMAILCODE_LENGTH', 15); # Character count of the email validation codes
defined('ACTIVATION_EMAIL_VALIDITY_MINUTES')			|| define('ACTIVATION_EMAIL_VALIDITY_MINUTES', 60 * 24); # Activation email link validity deadline (minutes)
defined('RESETPW_EMAIL_VALIDITY_MINUTES')				|| define('RESETPW_EMAIL_VALIDITY_MINUTES', 60 * 1); # Activation email link validity deadline (minutes)
defined('ACTIVATION_EMAIL_GAP_MINUTES')				|| define('ACTIVATION_EMAIL_GAP_MINUTES', 5); # Gap (in minutes) between sending activation email(s)

# Forms constants
defined('FORM_USERNAME_MINLENGTH')						|| define('FORM_USERNAME_MINLENGTH', 3); # UserName minimum length
defined('FORM_USERNAME_MAXLENGTH')						|| define('FORM_USERNAME_MAXLENGTH', 32); # UserName maximum length
defined('FORM_EMAIL_MAXLENGTH')							|| define('FORM_EMAIL_MAXLENGTH', 45); # EMail maximum length
defined('FORM_PASSWORD_MINLENGTH')						|| define('FORM_PASSWORD_MINLENGTH', 4); # Password minimum length
defined('FORM_PASSWORD_MAXLENGTH')						|| define('FORM_PASSWORD_MAXLENGTH', 99); # Password maximum length

# Subscription types
defined('SUBSCRIPTION_FREE')								|| define('SUBSCRIPTION_FREE', 0);
defined('SUBSCRIPTION_PAID')								|| define('SUBSCRIPTION_PAID', 1);

# Google reCAPTCHA v2
defined('RECAPTCHA_V2_SECRETKEY_FREEZOZ')				|| define('RECAPTCHA_V2_SECRETKEY_FREEZOZ', '');

# Session & Cookie items
defined('COOKIE_EXPIRY_TIME')								|| define('COOKIE_EXPIRY_TIME', 30 * 24 * 60 * 60); # 30 Days
defined('SESSION_USERID')									|| define('SESSION_USERID', "UserID");
defined('COOKIE_USERID')									|| define('COOKIE_USERID', "UserID");

# Status constants
defined('STATUS_SUCCESS')									|| define('STATUS_SUCCESS', 0); # Success
defined('STATUS_GENERROR')									|| define('STATUS_GENERROR', 1); # General error

# Registration/Sign-in errors
defined('STATUS_USERNAME_INVALID')						|| define('STATUS_USERNAME_INVALID', 11); # Invalid user name
defined('STATUS_USERNAME_EXISTS')						|| define('STATUS_USERNAME_EXISTS', 12); # Status: User name already exists
defined('STATUS_EMAIL_INVALID')							|| define('STATUS_EMAIL_INVALID', 13); # Invalid emaiil
defined('STATUS_EMAIL_EXISTS')							|| define('STATUS_EMAIL_EXISTS', 14); # Status: Email already exists
defined('STATUS_EMAIL_NOTFOUND')							|| define('STATUS_EMAIL_NOTFOUND', 15); # Status: Email was not found in the DB
defined('STATUS_PASSWORD_INVALID')						|| define('STATUS_PASSWORD_INVALID', 16); # Invalid password
defined('STATUS_TERMS_INVALID')							|| define('STATUS_TERMS_INVALID', 17); # User didn't agree to terms and conditions
defined('STATUS_RECAPTCHA_INVALID')						|| define('STATUS_RECAPTCHA_INVALID', 18); # Invalid reCAPTCHA response
defined('STATUS_ACTEMAIL_FAILED')						|| define('STATUS_ACTEMAIL_FAILED', 19); # Status: Sending activation email failed
defined('STATUS_BAD_REMEMBERME')							|| define('STATUS_BAD_REMEMBERME', 20); # Status: Sending activation email failed
defined('STATUS_RESETPWCODE_INVALID')					|| define('STATUS_RESETPWCODE_INVALID', 21); # Status: Invalid code for reset password
defined('STATUS_ITEMTITLE_EXISTS')						|| define('STATUS_ITEMTITLE_EXISTS', 22); # Status: While adding new item in admin panel: item title exists

# Home page constants
defined('HOMEPAGE_MAXITEMROWCOUNT')						|| define('HOMEPAGE_MAXITEMROWCOUNT', 7); # Maximum item row count per page

# Items-related constants
defined('ITEMS_ROOT')                              || define('ITEMS_ROOT', WRITEPATH . ((substr(WRITEPATH, -1) == "/") || (substr(WRITEPATH, -1) == "\\") ? "" : "/") . "ProdItems");
defined('ITEMS_ITEMFILE_ACCEPTEDFILETYPES')			|| define('ITEMS_ITEMFILE_ACCEPTEDFILETYPES', ["ZIP", "RAR"]); # Accepted filetypes for the item's file
defined('ITEMS_IMAGE_ACCEPTEDFILETYPES')				|| define('ITEMS_IMAGE_ACCEPTEDFILETYPES', ["JPG", "PNG"]); # Accepted filetypes for the images
defined('ITEMS_IMAGE_SMALL_DIMENSIONS')				|| define('ITEMS_IMAGE_SMALL_DIMENSIONS', ["width" => 590, "height" => 332]); # Small image dimensions
defined('ITEMS_IMAGE_FULL_DIMENSIONS')					|| define('ITEMS_IMAGE_FULL_DIMENSIONS', ["width" => 1920, "height" => 1080]); # Full-size image dimensions
defined('ITEMS_VIDEO_ACCEPTEDFILETYPES')				|| define('ITEMS_VIDEO_ACCEPTEDFILETYPES', ["MP4"]); # Accepted filetypes for the videos
defined('ITEMS_VIDEO_SMALL_DIMENSIONS')				|| define('ITEMS_VIDEO_SMALL_DIMENSIONS', ["width" => 590, "height" => 332]); # Small video dimensions
defined('ITEMS_VIDEO_FULL_DIMENSIONS')					|| define('ITEMS_VIDEO_FULL_DIMENSIONS', ["width" => 1920, "height" => 1080]); # Full-size video dimensions
defined('ITEMS_AUDIO_ACCEPTEDFILETYPES')				|| define('ITEMS_AUDIO_ACCEPTEDFILETYPES', ["MP3"]); # Accepted filetypes for the audios
defined('ITEMS_LICENSES')									|| define('ITEMS_LICENSES', [
   ["index" => 1,		"name" => "CC0-1.0",				"description" => "Creative Commons Zero v1.0 Universal"],
	["index" => 2,		"name" => "MIT",					"description" => "MIT License"]
]); # Item license types
defined('ITEMS_TITLE_MAXLENGTH')                   || define('ITEMS_TITLE_MAXLENGTH', 99); # Maximum length for item's name (also its folder name)
