<?php

use App\Models\Users;

###################################################################################################
if (!function_exists('include_css')) {
	#
	# Include CSS
	#
	# Inserts an HTML <link> tag for a local css file, along with its modification
	# time (as a uri parameter), to prevent caching of *modified* file
	#
	# @param string $filename File name
	#
	# @return null
	#
	function include_css($filename) {
		try {
			$str = "<link rel='stylesheet' type='text/css' href='" .  base_url() . "$filename";
			$str .= "?t=" . filemtime(FCPATH . "$filename") . "'>" . PHP_EOL . "\t";
			echo $str;
		} catch (\Throwable $e) {
			return;
		}
	}
}


###################################################################################################
if (!function_exists('include_jscript')) {
	#
	# Include JScript
	#
	# Inserts an HTML <script> tag for a local js file, along with its modification
	# time (as a uri parameter), to prevent caching of *modified* file
	#
	# @param string $filename File name
	#
	# @return null
	#
	function include_jscript($filename) {
		try {
			$str = "<script type='text/javascript' src='" . base_url() . "$filename";
			$str .= "?t=" . filemtime(FCPATH . "$filename") . "'></script>" . PHP_EOL;
			echo $str;
		} catch (\Throwable $e) {
			return;
		}
	}
}


###################################################################################################
if (!function_exists('insert_hspace')) {
	#
	# Insert horizontal space
	#
	# Insert a horizontal html <div>, with a 100% width and a desired height
	# (all units are accepted: px, rem, etc...)
	#
	# @param string $cssvalue the desired css 'height' property value
	#
	# @return null
	#
	function insert_hspace($cssvalue) {
		echo "<div style='width:100%;height:$cssvalue;'></div>";
	}
}


###################################################################################################
if (!function_exists('sendemail_accountactivation')) {
	#
	# Send Email (Account Activation)
	#
	# Sends an email with an activation link (e.g. to a newly registered user)
	#
	# @param string $email the user email to send this email to (must be already sanitized)
	#
	# @return bool (success or failure)
	#
	function sendemail_accountactivation($useremail) {
		$db_users = new Users();
		$act_code = $db_users->get_activationcode($useremail);

		// Construct the url for both live and localhost version (to allow testing & debugging)
		$act_link = base_url() . "/activation/$useremail/$act_code";

		$validity = sprintf("%.1f", ACTIVATION_EMAIL_VALIDITY_MINUTES / 60); // Precision should be kept .1f
		if (strpos($validity, ".0")) $validity = substr($validity, 0, strlen($validity) - 2);
		$validity = "$validity hour" . ($validity == "1" ? "" : "s");

		$email = \Config\Services::email();
		$email->setFrom(FREEZOZ_EMAIL_SUPPORT, 'Freezoz');
		$email->setTo($useremail);
		$email->setSubject('Complete your sign-up at Freezoz.com');
		$email->setMessage(
			'<h1>Hello from Freezoz!</h1>' .
			'<br/>' .
			'<p>' .
				'Click the following link to continue your registration and activate your account:' . '<br/>' .
				'<a href="' . $act_link . '">Activate my account</a>' . '<br/>' .
				'<strong>(This link is valid only if used within ' . $validity . ' from the time of registration)</strong>' .
			'</p>' .
			'<br/>' .
			'<p>For any questions, contact us at <a href="mailto:support@freezoz.com">support@freezoz.com</a>.</p>' .
			'<p>' .
			   'With our best regards,<br/>Freezoz support team' .
			'</p>'
		);

		return $email->send();
	}
}


###################################################################################################
if (!function_exists('userid_encode')) {
	#
	# Encodes a user's db row id into a public UserID
	#
	# @param (int) The user's db row id
	#
	# @return (string) Encoded UserID
	#
	function userid_encode($rowid) {
		# Plan: R r . R e . R
		# Legend: (R) random ignored,     (r) random between 0 and 99 (indicated how e is shifted),     (e) rowid + r
		helper('text');
		
		$r = rand(0, 99); // (limits are inclusive, returns int)
		$e = $rowid + $r;
		
		return random_string('alnum', 1) . $r . '.' . random_string('alnum', 1) . $e . '.' . random_string('alnum', 1);
	}
}


###################################################################################################
if (!function_exists('userid_decode')) {
	#
	# Decodes a UserID back into user's db row id
	#
	# @param (string) The encoded UserID
	#
	# @return (int) User's db row id
	#
	function userid_decode($user_id) {
		# Refer to userid_encode() for the plan and legend
		if (empty($user_id)) return NULL;

		$loc1 = strpos($user_id, '.', 1); //3
		$r = intval(substr($user_id, 1, $loc1 - 1));

		$loc2 = strpos($user_id, '.', $loc1 + 2); //8
		$e = intval(substr($user_id, $loc1 + 2, $loc2 - ($loc1 + 2)));
		
		return ($e - $r);
	}
}


###################################################################################################
if (!function_exists('user_loggedin')) {
	#
	# Checks Session and Cookies for a previous sign in (user_id),
	# and checks their data against the db contents.
	# Returns an array:
	# ["is_loggedin" => (BOOL), "is_admin" => (BOOL)]
	#
	function user_loggedin() {
		$db_users = new Users();
		$session = \Config\Services::session();
		helper('cookie');
		$retdata = ['is_loggedin' => FALSE, 'is_admin' => FALSE]; // Initially

		$userid_ss = $session->get(SESSION_USERID);
		$userid_ck = get_cookie(COOKIE_USERID, true); ### XSS-Clean
		$rowid_ss = empty($userid_ss) ? NULL : userid_decode($userid_ss);
		$rowid_ck = empty($userid_ck) ? NULL : userid_decode($userid_ck);

		// If both session and cookies CONTAIN data, they must be identical, or else order the user to log in again!
		if ((!empty($rowid_ss) && !empty($rowid_ck)) && ($rowid_ss != $rowid_ck)) return $retdata; // It's FALSE

		if (!empty($rowid_ss)) { // Session
			$dbrow = $db_users->where('rowid', $rowid_ss)->first();
			if (empty($dbrow)) return $retdata; // It's FALSE
			
			$retdata = ['is_loggedin' => TRUE, 'is_admin' => empty($dbrow['is_admin']) ? FALSE : TRUE];
			return $retdata;
		}

		// Session doesn't contain the user_id, search cookeis

		if (!empty($rowid_ck)) { // Cookies
			$dbrow = $db_users->where('rowid', $rowid_ck)->first();
			if (empty($dbrow)) return $retdata; // It's FALSE  (Cookie is wrong, session is empty)

			// Cookie is OK, Session is empty => create a session variable, and return OK (Previous sign-in)
			$session->set([SESSION_USERID => $userid_ck]);
			$retdata = ['is_loggedin' => TRUE, 'is_admin' => empty($dbrow['is_admin']) ? FALSE : TRUE];
		}

		// Cookies and Session don't contain user_id !
		return $retdata; // It's FALSE
	}
}

###################################################################################################
if (!function_exists('user_signout')) {
	#
	# Sign the user out
	#
	# Stop and destroy the seeion, and delete the cookie if it exists
	#
	# @param null
	#
	# @return bool the sign-in status
	#
	function user_signout() {
		$session = \Config\Services::session();
		helper('cookie');

		// Destroy the cookie
		delete_cookie(COOKIE_USERID);

		/*
		NOTE: Think about when we need to preserve other data of the session, like number of downloads for the days...etc
		*/
		
		// Destroy the session
		$session->stop();
		$session->destroy(); // Kill session, destroy data, and destroy the cookie that contains the session id

		// BaseController->bIsLoggedIn is NOT accessible outside of it & its decendant classes (protected)
		// Just return the value to remember to update the protected variable
		return user_loggedin()['is_loggedin'];
	}
}

###################################################################################################
if (!function_exists('sendemail_resetpassword')) {
	#
	# Send Email (Reset Password)
	#
	# Sends an email with a link to reset the user's password
	#
	# @param string $email the user email to send this email to (must be already sanitized)
	#
	# @return bool (success or failure)
	#
	function sendemail_resetpassword($useremail) {
		$db_users = new Users();
		$resetpw_code = $db_users->createSaveGet_resetpwcode($useremail);

		// Construct the url for both live and localhost version (to allow testing & debugging)
		$resetpw_link = base_url() . "/reset_pw/$useremail/$resetpw_code";

		$validity = sprintf("%.1f", RESETPW_EMAIL_VALIDITY_MINUTES / 60); // Precision should be kept .1f
		if (strpos($validity, ".0")) $validity = substr($validity, 0, strlen($validity) - 2);
		$validity = "$validity hour" . ($validity == "1" ? "" : "s");

		$email = \Config\Services::email();
		$email->setFrom(FREEZOZ_EMAIL_SUPPORT, 'Freezoz');
		$email->setTo($useremail);
		$email->setSubject('Reset your password at Freezoz.com');
		$email->setMessage(
			'<h1>Hello from Freezoz!</h1>' .
			'<br/>' .
			'<p>' .
				'We have received a request to reset your password.<br/>' .
				'<br/>' .
				'If you did not requested that, you can ignore this email.<br/>' .
				'<br/>' .
				'Click the following link to reset your password. You will be redirected to the website to enter a new one:<br/>' .
				'<a href="' . $resetpw_link . '">Reset my password</a>' . '<br/>' .
				'<strong>(This link is valid only if used within ' . $validity . ' from the time of request)</strong>' .
			'</p>' .
			'<br/>' .
			'<p>For any questions, contact us at <a href="mailto:support@freezoz.com">support@freezoz.com</a>.</p>' .
			'<p>' .
			   'With our best regards,<br/>Freezoz support team' .
			'</p>'
		);

		return $email->send();
	}
}