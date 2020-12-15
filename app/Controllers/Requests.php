<?php namespace App\Controllers;

use App\Models\Users;

class Requests extends BaseController {
	################################################################################################
	# Register a new user
	# Params: name, email, password, conditions, grecaptcha.
	################################################################################################
	public function user_register() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		$db_users = new Users();
		define("ERRMSG_USERNAME_ALREADYEXISTS", "Sorry, this user name is already taken. Choose another one.");
		define("ERRMSG_USEREMAIL_ALREADYEXISTS", "There is already an account using this email. Please sign in instead.\r\n\r\n(Use the button in the top to sign in)");
		define("ERRMSG_RECAPTCHA_INVALID", "Please check Google reCAPTCHA and try again!");

		$param_name = trim($this->request->getPost("name"));
		if (strcmp($this->request->getPost('name', FILTER_SANITIZE_STRING), $param_name) != 0) {
			return $this->response->setJSON(['retcode' => STATUS_USERNAME_INVALID, 'retdata' => "Your name contains invalid characters!"]);
		}
		if (empty($param_name) || strlen($param_name) < FORM_USERNAME_MINLENGTH || strlen($param_name) > FORM_USERNAME_MAXLENGTH) {
			return $this->response->setJSON([
				'retcode' => STATUS_USERNAME_INVALID,
				'retdata' => "Your name length should be between " . FORM_USERNAME_MINLENGTH . " and " . FORM_USERNAME_MAXLENGTH . " characters!"
			]);
		}
		if ($db_users->username_exists($param_name)) {
			return $this->response->setJSON(['retcode' => STATUS_USERNAME_EXISTS, 'retdata' => ERRMSG_USERNAME_ALREADYEXISTS]);
		}

		$param_email = $this->request->getPost('email');
		if (strcmp($this->request->getPost('email', FILTER_SANITIZE_EMAIL), $param_email) != 0) {
			return $this->response->setJSON(['retcode' => STATUS_EMAIL_INVALID, 'retdata' => "Your email address is invalid!"]);
		}
		if (empty($param_email) || strlen($param_email) > FORM_EMAIL_MAXLENGTH) {
			return $this->response->setJSON([
				'retcode' => STATUS_EMAIL_INVALID,
				'retdata' => "Your email length should be no more than " . FORM_EMAIL_MAXLENGTH . " characters!" // Front-end must have taken care of the minimum limit
			]);
		}
		if ($db_users->email_exists($param_email)) {
			return $this->response->setJSON(['retcode' => STATUS_EMAIL_EXISTS, 'retdata' => ERRMSG_USEREMAIL_ALREADYEXISTS]);
		}

		$param_password = $this->request->getPost('password');
		if (empty($param_password) || strlen($param_password) < FORM_PASSWORD_MINLENGTH || strlen($param_password) > FORM_PASSWORD_MAXLENGTH) {
			return $this->response->setJSON([
				'retcode' => STATUS_PASSWORD_INVALID,
				'retdata' => "Password length should be between " . FORM_PASSWORD_MINLENGTH . " and " . FORM_PASSWORD_MAXLENGTH . " characters!"
			]);
		}

		if (empty($this->request->getPost('conditions')) || $this->request->getPost('conditions') != "true") {
			return $this->response->setJSON(['retcode' => STATUS_TERMS_INVALID, 'retdata' => "You have to agree to the terms of use and privacy policy!"]);
		}

		// Check Google reCAPTCHA v2
		$param_recaptchatoken = $this->request->getPost('grecaptcha', FILTER_SANITIZE_STRING);
		if (empty($param_recaptchatoken)) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => ERRMSG_RECAPTCHA_INVALID]);
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array('secret' => RECAPTCHA_V2_SECRETKEY_FREEZOZ, 'response' => $param_recaptchatoken);
		if (!empty($this->request->getIPAddress())) $data['remoteip'] = $this->request->getIPAddress();
		$options = array(
			'http' => array( // Use key 'http' even if you send the request to https://...
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$resjson = json_decode(file_get_contents($url, false, $context), TRUE);
		if (empty($resjson)) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => ERRMSG_RECAPTCHA_INVALID]);
		if (empty($resjson['success'])) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => ERRMSG_RECAPTCHA_INVALID]);
		if ($resjson['success'] != true) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => ERRMSG_RECAPTCHA_INVALID]);

		/* OK, verification done */

		// Add the user, and check for "name" and "email" to be unique
		$result = $db_users->add_user($param_name, $param_email, $param_password);
		if ($result['retcode'] == STATUS_USERNAME_EXISTS) return $this->response->setJSON(['retcode' => $result['retcode'], 'retdata' => ERRMSG_USERNAME_ALREADYEXISTS]);
		if ($result['retcode'] == STATUS_EMAIL_EXISTS) return $this->response->setJSON(['retcode' => $result['retcode'], 'retdata' => ERRMSG_USEREMAIL_ALREADYEXISTS]);
		
		// Send activation email
		$result = sendemail_accountactivation($param_email);
		if (!$result) {
			return $this->response->setJSON([
				'retcode' => STATUS_ACTEMAIL_FAILED,
				'retdata' => "You account was created successfully\r\n\r\nHowever, we could not send an activation code to your email address ($param_email).\r\n\r\n" .
					"Please, contact our support: " . FREEZOZ_EMAIL_SUPPORT
			]);
		}

		// here return the message to display "success" (and then redirect)
		return $this->response->setJSON([
			'retcode' => STATUS_SUCCESS,
			'retdata' => "Your account was created successfully. You can login now using your credentials.\r\n\r\nPlease, check your email inbox " .
				"(including the spam folder) for instructions to activate your account."
		]);
	}

	################################################################################################
	# Checks the credentials for a signing-in user
	# params: email, password, [remember]
	################################################################################################
	public function sign_in() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		$db_users = new Users();
		define("ERRMSG_BAD_CREADENTIALS", "Your sign-in credentials are incorrect!");

		$param_email = $this->request->getPost('email'); // Sanitized later
		$param_password = $this->request->getPost('password');
		$param_remember = $this->request->getPost('remember');

		// Check parameters
		if (strcmp($this->request->getPost('email', FILTER_SANITIZE_EMAIL), $param_email) != 0) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => ERRMSG_BAD_CREADENTIALS]);
		}
		if (empty($param_email) || strlen($param_email) > FORM_EMAIL_MAXLENGTH) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => ERRMSG_BAD_CREADENTIALS]);
		}
		if (empty($param_password) || strlen($param_password) < FORM_PASSWORD_MINLENGTH || strlen($param_password) > FORM_PASSWORD_MAXLENGTH) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => ERRMSG_BAD_CREADENTIALS]);
		}
		if (!empty($param_remember)) {
			if (($param_remember != 'true') && ($param_remember != 'false')) {
				return $this->response->setJSON(['retcode' => STATUS_BAD_REMEMBERME, 'retdata' => NULL]);
			}
		}

		// Check that credentials are correct
		$pwhash = $db_users->hash_password($param_password);
		if (empty($db_users->where('email', $param_email)->where('pw_hash', $pwhash)->first())) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => ERRMSG_BAD_CREADENTIALS]);
		}

		// Save the rowid (after being encoded) as the user_id session variable
		$row = $db_users->where('email', $param_email)->where('pw_hash', $pwhash)->first();
		$rowid = $row['rowid'];
		$user_id = userid_encode($rowid);
		$this->session->set([SESSION_USERID => $user_id]);

		// Only if the user requested to 'remember' them, save the user_id in cookies too
		if (!empty($param_remember) && ($param_remember == 'true')) {
			$this->response->setcookie(COOKIE_USERID, $user_id, COOKIE_EXPIRY_TIME);
		}
		
		// Return OK
		$this->bLoggedIn = user_loggedin();
		return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => NULL]);
	}

	################################################################################################
	# Sign the current user out (clear session + cookies)
	# params: none
	################################################################################################
	public function sign_out() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		user_signout($this);

		return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => NULL]);
	}

	################################################################################################
	# Send a "reset password" email, after checking that the email already belongs to an account
	# params: email, grecaptcha
	################################################################################################
	public function forgot_pw() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		$db_users = new Users();
		define("MSG_BAD_PARAM", "This email address is invalid!");
		define("MSG_RECAPTCHA_INVALID", "Please check Google reCAPTCHA and try again!");
		define("MSG_FINISHED", "Your request was received.\r\nif this email address is found registered, it will receive a message shortly.");

		$param_email = $this->request->getPost('email'); // Sanitized later
		if (strcmp($this->request->getPost('email', FILTER_SANITIZE_EMAIL), $param_email) != 0) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => MSG_BAD_PARAM]);
		}
		if (empty($param_email) || strlen($param_email) > FORM_EMAIL_MAXLENGTH) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => MSG_BAD_PARAM]);
		}

		// Check Google reCAPTCHA v2
		$param_recaptchatoken = $this->request->getPost('grecaptcha', FILTER_SANITIZE_STRING);
		if (empty($param_recaptchatoken)) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => MSG_RECAPTCHA_INVALID]);
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array('secret' => RECAPTCHA_V2_SECRETKEY_FREEZOZ, 'response' => $param_recaptchatoken);
		if (!empty($this->request->getIPAddress())) $data['remoteip'] = $this->request->getIPAddress();
		$options = array(
			'http' => array( // Use key 'http' even if you send the request to https://...
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context = stream_context_create($options);
		$resjson = json_decode(file_get_contents($url, false, $context), TRUE);
		if (empty($resjson)) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => MSG_RECAPTCHA_INVALID]);
		if (empty($resjson['success'])) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => MSG_RECAPTCHA_INVALID]);
		if ($resjson['success'] != true) return $this->response->setJSON(['retcode' => STATUS_RECAPTCHA_INVALID, 'retdata' => MSG_RECAPTCHA_INVALID]);

		// Check the the email exists (After checking ALL user input including reCAPTCHA, because the results of this check is not to be shown to the user)
		if (!empty($db_users->where('email', $param_email)->first())) { // Email is found in db
			$result = sendemail_resetpassword($param_email);
			if (!$result) log_message('error', "User requested to reset their password. Their email is found, but sending the reset email failed!\r\nemail=$param_email");
		}

		/***/
		log_message('error', "Just a test: user requested to reset their password - email=$param_email");
		/***/
		
		return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => MSG_FINISHED]);
	}

	################################################################################################
	# Reset a password (saves new password (password hash) in the DB)
	# params: email, password
	################################################################################################
	public function reset_pw() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		$db_users = new Users();
		define("MSG_UNSECSESSFUL", "Sorry!\r\n\r\nAn error occurred. Please try again later.");

		$param_email = $this->request->getPost('email');
		if (strcmp($this->request->getPost('email', FILTER_SANITIZE_EMAIL), $param_email) != 0) {
			return $this->response->setJSON(['retcode' => STATUS_EMAIL_INVALID, 'retdata' => "Your email address is invalid!"]);
		}
		if (empty($param_email) || strlen($param_email) > FORM_EMAIL_MAXLENGTH) {
			return $this->response->setJSON([
				'retcode' => STATUS_EMAIL_INVALID,
				'retdata' => "Your email length should be no more than " . FORM_EMAIL_MAXLENGTH . " characters!" // Front-end must have taken care of the minimum limit
			]);
		}

		$param_password = $this->request->getPost('password');
		if (empty($param_password) || strlen($param_password) < FORM_PASSWORD_MINLENGTH || strlen($param_password) > FORM_PASSWORD_MAXLENGTH) {
			return $this->response->setJSON([
				'retcode' => STATUS_PASSWORD_INVALID,
				'retdata' => "Password length should be between " . FORM_PASSWORD_MINLENGTH . " and " . FORM_PASSWORD_MAXLENGTH . " characters!"
			]);
		}

		$param_rpw_code = $this->request->getPost('rpw_code');
		if (empty($param_rpw_code) || (strcmp($this->request->getPost('rpw_code', FILTER_SANITIZE_STRING), $param_rpw_code) != 0)) {
			return $this->response->setJSON([
				'retcode' => STATUS_RESETPWCODE_INVALID,
				'retdata' => "Invalid password reset code.\r\nPlease use the link in your email and try again."
			]);
		}

		/* OK, verification done */

		// Overwrite the user password (its hash), and delete the reset_code from the db
		$result = $db_users->reset_password($param_email, $param_password, $param_rpw_code);
		if ($result['retcode'] != STATUS_SUCCESS) {
			// Will return either STATUS_EMAIL_NOTFOUND or STATUS_RESETPWCODE_INVALID, and both are viewed to the user using a message box (swal)
			return $this->response->setJSON(['retcode' => $result['retcode'], 'retdata' => MSG_UNSECSESSFUL]);
		} elseif (($result['retcode'] == STATUS_SUCCESS) && ($result['retdata'] == 0)) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => MSG_UNSECSESSFUL]);
		}

		// Now, sign the user out
		user_signout($this);
		
		// Return the message to display "success" (and then redirect)
		return $this->response->setJSON([
			'retcode' => STATUS_SUCCESS,
			'retdata' => "Your password was reset successfully.\r\nYou can login now using your credentials."
		]);
	}
}