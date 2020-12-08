<?php

use App\Models\Users;
use CodeIgniter\HTTP\URI;

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
		$config = config(App::class);
		$base_uri = new URI(rtrim($config->baseURL, '/'));

		try {
			$str = "<script type='text/javascript' src='$base_uri/js/$filename";
			$str .= "?t=" . filemtime(FCPATH . "/js/$filename") . "'></script>" . PHP_EOL;

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
		$config = config(App::class);
		$base_uri = new URI(rtrim($config->baseURL, '/'));
		$act_link = $base_uri . "/activation/$useremail/$act_code";

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
				'<strong>(This link is valid only if used within an hour from the time of registration)</strong>' .
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
	#
	function user_loggedin() {
		$db_users = new Users();
		$session = \Config\Services::session();
		helper('cookie');

		$userid_ss = $session->get(SESSION_USERID);
		$userid_ck = get_cookie(COOKIE_USERID, true); ### XSS-Clean
		$rowid_ss = empty($userid_ss) ? NULL : userid_decode($userid_ss);
		$rowid_ck = empty($userid_ck) ? NULL : userid_decode($userid_ck);

		// If both session and cookies CONTAIN data, they must be identical, or else order the user to log in again!
		if ((!empty($rowid_ss) && !empty($rowid_ck)) && ($rowid_ss != $rowid_ck)) return FALSE;

		if (!empty($rowid_ss)) { // Session
			if (!empty($db_users->where('rowid', $rowid_ss)->first())) return TRUE; else return FALSE;
		}

		// Session doesn't contain the user_id, search cookeis

		if (!empty($rowid_ck)) { // Cookies
			if (!empty($db_users->where('rowid', $rowid_ck)->first())) {
				// Cookie is OK, Session is empty => create a session variable, and return OK (Previous sign-in)
				$session->set([SESSION_USERID => $userid_ck]);
				return TRUE;
			} else {
				// Cookie is wrong, session is empty
				return FALSE;
			}
		}

		// Cookies and Session don't contain user_id !
		return FALSE;
	}
}