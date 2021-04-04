<?php namespace App\Controllers;

use App\Models\Users;
use App\Models\ProdItems;

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
		$row = $db_users->where('email', $param_email)->where('pw_hash', $pwhash)->first();

		// Check that the user account is activated
		$is_active = empty($row['is_active']) ? FALSE : TRUE;

		// Save the rowid (after being encoded) as the user_id session variable
		$rowid = $row['rowid'];
		$user_id = userid_encode($rowid);
		$this->session->set([SESSION_USERID => $user_id]);

		// Only if the user requested to 'remember' them, save the user_id in cookies too (also only if the user is activated)
		if (!empty($param_remember) && ($param_remember == 'true') && $is_active) {
			$this->response->setcookie(COOKIE_USERID, $user_id, COOKIE_EXPIRY_TIME);
		}
		
		// Return OK
		$this->bLoggedIn = user_loggedin()['is_loggedin'];
		return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => $is_active]);
	}


	################################################################################################
	# Sign the current user out (clear session + cookies)
	# params: none
	################################################################################################
	public function sign_out() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		$this->bLoggedIn = user_signout();

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
		$this->bLoggedIn = user_signout();
		
		// Return the message to display "success" (and then redirect)
		return $this->response->setJSON([
			'retcode' => STATUS_SUCCESS,
			'retdata' => "Your password was reset successfully.\r\nYou can now use it to login."
		]);
	}


	################################################################################################
	# (Re-)send the activation code to the user's email address.
	# params: -none-
	# NOTE: Error handling: normal (2 levels: text only, and json-encoded), plus success (json) indicator.
	################################################################################################
	public function send_activationcode() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		# Get the (should be already) logged-in user's db row, from the session vars (cookies should not have been set)
		$userid_ss = $this->session->get(SESSION_USERID);
		$rowid_ss = empty($userid_ss) ? NULL : userid_decode($userid_ss);
		if (empty($rowid_ss)) {
			return $this->response->setJSON([
				'retcode' => STATUS_GENERROR,
				'retdata' => "Sorry, your session has been closed.\r\nPlease, refresh the page, re-login, and try activation again."
			]);
		}

		$db_users = new Users();
		$dbrow = $db_users->where('rowid', $rowid_ss)->first();
		
		// Check timeout (min. 5 minutes between requests)
		$allowed = FALSE;
		// $dbrow['activation_datetime'] should not be NULL. Every registration is accompanied by sending at least one activation email
		$nexttimeallowed = strtotime($dbrow['activation_datetime'] . " +0000 + " . strval(ACTIVATION_EMAIL_GAP_MINUTES) . " minutes");
		$remaining = $nexttimeallowed - time(); # time() is timezone independent (=UTC) (in seconds)
		if ($remaining < 0) $allowed = TRUE; # Remaining = negative, this means time has passed the gap deadline

		if (!$allowed) {
			return $this->response->setJSON([
				'retcode' => STATUS_GENERROR,
				'retdata' => "Please, wait for $remaining second" . ($remaining == 1 ? "" : "s") . " before sending the activation code!"
			]);
		}
		
		// Save last activation (required to calculate the allowed time gap)
		$db_users->update($dbrow['rowid'], ['activation_datetime' => gmdate('Y-m-d H:i:s')]);

		// Send the activation code and email
		$user_email = $dbrow['email'];
		$result = sendemail_accountactivation($user_email);
		if (!$result) {
			return $this->response->setJSON([
				'retcode' => STATUS_ACTEMAIL_FAILED,
				'retdata' => "Sorry, we could not send an activation code to your email address ($user_email).\r\n\r\nPlease, contact our support: " . FREEZOZ_EMAIL_SUPPORT
			]);
		}

		// Return the message to display "success" (and then redirect)
		return $this->response->setJSON([
			'retcode' => STATUS_SUCCESS,
			'retdata' => "An email was sent to your address ($user_email).\r\n\r\nPlease, check your inbox (including the spam folder) for instructions to activate your account."
		]);
	}


	################################################################################################
	# Get a list of items (to be shown on the home page) (the FULL list of rowids)
	# params: none (should be some filter)
	################################################################################################
	public function items_getlist() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		// Return the rowids of all items (that match a specific query criteria [categories, serach word, etc])
		$db_proditems = new ProdItems();

		$query = $db_proditems->query('SELECT `rowid` FROM `proditems`');
		$itemslist = $query->getResult();

		return $this->response->setJSON([
			'retcode' => STATUS_SUCCESS,
			'retdata' => [
				'itemlist'				=> $itemslist,
				'maxitemcountpercol'	=> HOMEPAGE_MAXITEMROWCOUNT
			]
		]);
	}


	################################################################################################
	# Get the data that belongs to a single item (differentiate between data to be shown on the home page, or that to be shown on the item's page)
	# params: rid (orig: rowid), dtype (orig: datatype): ["home", "itempage"]
	################################################################################################
	public function item_getdata() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		# Check parameters
		$db_proditems = new ProdItems();

		$param_rid = intval($this->request->getPost('rid', FILTER_SANITIZE_NUMBER_INT));
		if ($db_proditems->where('rowid', $param_rid)->first() == null) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => "Bad internal itemid!"]);
		}
		$param_dtype = $this->request->getPost('dtype', FILTER_SANITIZE_STRING);
		if ((strcmp($param_dtype, "home") != 0) && (strcmp($param_dtype, "itempage") != 0)) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => "Bad item dtype!"]);
		}

		# Get needed data
		$itemdata = [];
		$dbrow = $db_proditems->where('rowid', $param_rid)->first();

		if ($param_dtype == "home") {
			$itemdata['folder'] = $dbrow['folder_name'];
			$itemdata['thumbnail'] = $dbrow['previmg_small'];
			$itemdata['prevvid'] = $dbrow['prevvid_small'];
			$itemdata['prevvidhtmltagtype'] = ((strtoupper(substr($dbrow['prevvid_small'], -4)) == '.MP4') ? "video/mp4" : "");
			$itemdata['title'] = $dbrow['title'];
			$itemdata['rating'] = $dbrow['rating'];
			$itemdata['ratescount'] = $dbrow['ratescount'];
			$itemdata['price'] = $dbrow['price'];
			$itemdata['downsalecount'] = $dbrow['downsalecount'];
		} else
		if ($param_dtype == "itempage") {

		}

		# Return the needed data
		return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => $itemdata]);
	}


	################################################################################################
	# ADMIN: Check that an item's title is unique (could become a folder's name for that item)
	# params: itemtitle
	# CHECK BOTH THE DB AND THE FILESYSTEM (ITEMS STORAGE FOLDER)
	# Returns: plain: error, not displayed. Json with error: will be shown to user. Json with success: not shown.
	################################################################################################
	public function admin_items_checktitle() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		# Filter parameter
		$param_itemtitle = trim($this->request->getPost('itemtitle', FILTER_SANITIZE_STRING));
		if (strlen($param_itemtitle) < 1) return "Bad parameter: itemtitle";

		# Check DB (search is usually case insensitive) and the file system folders
		$db_proditems = new ProdItems();
		clearstatcache();

		if ($db_proditems->itemtitle_exists($param_itemtitle) /*$db_proditems->where('title', $param_itemtitle)->first()*/ || file_exists(ITEMS_ROOT . "/" . filter_foldername($param_itemtitle))) {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => "The entered item title already exists!"]);
		}

		return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => NULL]);
	}


	################################################################################################
	# ADMIN: Add a new product item
	# params: date, title, description, fileItem, tags, imgSmall, imgFull, vidSmall, vidFull,
	#    [addImagesCount], [addVideosCount], [addAudiosCount], price, license
	# RESPONSE TO ANY ERROR OCCURRING IN THIS REQUEST SHOULD CONSIST OF ONLY AN ERROR STRING.
	# ANY JSON RETURNED INDICATE SUCCESS.
	################################################################################################
	public function admin_items_add() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		// Check parameters
		$RequestFiles = $this->request->getFiles();

		$param_date = $this->request->getPost('date', FILTER_SANITIZE_STRING);
		if (strlen($param_date) != 10) return "Bad parameter: date";

		$param_title = trim($this->request->getPost('title', FILTER_SANITIZE_STRING));
		if (strlen($param_title) < 1) return "Bad parameter: title";

		$param_description = trim($this->request->getPost('description'));//, FILTER_SANITIZE_STRING)); // We need html tags!
		if (strlen($param_description) < 1) return "Bad parameter: description";

		if (empty($RequestFiles['fileItem'])) return "Bad parameter: fileItem";
		if (!($RequestFiles['fileItem']->isValid())) return "Invalid: fileItem";
		$param_fileobj_fileItem = $RequestFiles['fileItem'];

		$param_tags = trim($this->request->getPost('tags', FILTER_SANITIZE_STRING));
		if (strlen($param_tags) < 1) return "Bad parameter: tags";

		if (empty($RequestFiles['imgSmall'])) return "Bad parameter: imgSmall";
		if (!($RequestFiles['imgSmall']->isValid())) return "Invalid: imgSmall";
		$param_fileobj_imgSmall = $RequestFiles['imgSmall'];

		if (empty($RequestFiles['imgFull'])) return "Bad parameter: imgFull";
		if (!($RequestFiles['imgFull']->isValid())) return "Invalid: imgFull";
		$param_fileobj_imgFull = $RequestFiles['imgFull'];

		if (empty($RequestFiles['vidSmall'])) return "Bad parameter: vidSmall";
		if (!($RequestFiles['vidSmall']->isValid())) return "Invalid: vidSmall";
		$param_fileobj_vidSmall = $RequestFiles['vidSmall'];

		if (empty($RequestFiles['vidFull'])) return "Bad parameter: vidFull";
		if (!($RequestFiles['vidFull']->isValid())) return "Invalid: vidFull";
		$param_fileobj_vidFull = $RequestFiles['vidFull'];

		$param_addImagesCount = $this->request->getPost('addImagesCount', FILTER_SANITIZE_NUMBER_INT);
		for ($iC = 0; $iC < $param_addImagesCount; $iC++) {
			$param_fileobj_addImage = $RequestFiles["addImages-$iC"];
			if (!($param_fileobj_addImage->isValid())) return "Invalid: addImages";
		}

		$param_addVideosCount = $this->request->getPost('addVideosCount', FILTER_SANITIZE_NUMBER_INT);
		for ($iC = 0; $iC < $param_addVideosCount; $iC++) {
			$param_fileobj_addVideo = $RequestFiles["addVideos-$iC"];
			if (!($param_fileobj_addVideo->isValid())) return "Invalid: addVideos";
		}

		$param_addAudiosCount = $this->request->getPost('addAudiosCount', FILTER_SANITIZE_NUMBER_INT);
		for ($iC = 0; $iC < $param_addAudiosCount; $iC++) {
			$param_fileobj_addAudio = $RequestFiles["addAudios-$iC"];
			if (!($param_fileobj_addAudio->isValid())) return "Invalid: addAudios";
		}

		$param_price = $this->request->getPost('price', FILTER_SANITIZE_NUMBER_INT);

		$param_license = $this->request->getPost('license', FILTER_SANITIZE_NUMBER_INT);

		/*
		Save Uploaded Files (also some checking along..)
		*/
		$DBData = [];
		$DBData["foldername"] = filter_foldername($param_title);

		$FolderName = ITEMS_ROOT . "/" . $DBData["foldername"];

		# Create a new folder for the product item (folder name is the same as product name)
		# Folder name should have been already checked before (DB + FileSystem) - See request "admin_items_checktitle" in this script file
		if (file_exists($FolderName)) return "Error: Folder exists.";
		if (!mkdir($FolderName)) return "Error: Cannot create folder.";

		# Store item's file
		$FileName = NULL;
		do {
			$FileName = $param_fileobj_fileItem->getRandomName();
			# ONLY for rar, CodeIgniter is guessing the file extension as .csv! We should correct that.
			if (strtolower($param_fileobj_fileItem->getClientExtension()) == "rar") {
				$info = pathinfo($FileName);
				$FileName = $info['filename'] . '.rar';
			}
		} while (file_exists($FolderName . "/" . $FileName));
		$param_fileobj_fileItem->move($FolderName, $FileName);
		if (!file_exists($FolderName . "/" . $FileName)) return "Error: Saving fileItem.";
		$DBData["proditem"] = $FileName;

		# Store item's small image
		$FileName = NULL;
		do {
			$FileName = $param_fileobj_imgSmall->getRandomName();
		} while (file_exists($FolderName . "/" . $FileName));
		$param_fileobj_imgSmall->move($FolderName, $FileName);
		if (!file_exists($FolderName . "/" . $FileName)) return "Error: Saving imgSmall.";
		$DBData["imgsmall"] = $FileName;

		# Store item's full image
		$FileName = NULL;
		do {
			$FileName = $param_fileobj_imgFull->getRandomName();
		} while (file_exists($FolderName . "/" . $FileName));
		$param_fileobj_imgFull->move($FolderName, $FileName);
		if (!file_exists($FolderName . "/" . $FileName)) return "Error: Saving imgFull.";
		$DBData["imgfull"] = $FileName;
		
		# Store item's small video
		$FileName = NULL;
		do {
			$FileName = $param_fileobj_vidSmall->getRandomName();
		} while (file_exists($FolderName . "/" . $FileName));
		$param_fileobj_vidSmall->move($FolderName, $FileName);
		if (!file_exists($FolderName . "/" . $FileName)) return "Error: Saving vidSmall.";
		$DBData["vidsmall"] = $FileName;

		# Store item's full video
		$FileName = NULL;
		do {
			$FileName = $param_fileobj_vidFull->getRandomName();
		} while (file_exists($FolderName . "/" . $FileName));
		$param_fileobj_vidFull->move($FolderName, $FileName);
		if (!file_exists($FolderName . "/" . $FileName)) return "Error: Saving vidFull.";
		$DBData["vidfull"] = $FileName;

		# Store additional images (if any)
		$DBData["addimages"] = [];
		for ($iC = 0; $iC < $param_addImagesCount; $iC++) {
			$param_fileobj_addImage = $RequestFiles["addImages-$iC"];

			$FileName = NULL;
			do {
				$FileName = $param_fileobj_addImage->getRandomName();
			} while (file_exists($FolderName . "/" . $FileName));
			$param_fileobj_addImage->move($FolderName, $FileName);
			array_push($DBData["addimages"], $FileName);
		}

		# Store additional videos (if any)
		$DBData["addvideos"] = [];
		for ($iC = 0; $iC < $param_addVideosCount; $iC++) {
			$param_fileobj_addVideo = $RequestFiles["addVideos-$iC"];

			$FileName = NULL;
			do {
				$FileName = $param_fileobj_addVideo->getRandomName();
			} while (file_exists($FolderName . "/" . $FileName));
			$param_fileobj_addVideo->move($FolderName, $FileName);
			array_push($DBData["addvideos"], $FileName);
		}

		# Store additional audios (if any)
		$DBData["addaudios"] = [];
		for ($iC = 0; $iC < $param_addAudiosCount; $iC++) {
			$param_fileobj_addAudio = $RequestFiles["addAudios-$iC"];

			$FileName = NULL;
			do {
				$FileName = $param_fileobj_addAudio->getRandomName();
			} while (file_exists($FolderName . "/" . $FileName));
			$param_fileobj_addAudio->move($FolderName, $FileName);
			array_push($DBData["addaudios"], $FileName);
		}
		
		/*
		Save DB data
		*/
		$db_proditems = new ProdItems();
		$result = $db_proditems->add_item($param_date, $param_title, $param_description, $param_tags, $DBData["foldername"], $DBData["imgsmall"],
			$DBData["imgfull"], $DBData["vidsmall"], $DBData["vidfull"], $DBData["addimages"], $DBData["addvideos"], $DBData["addaudios"],
			$DBData["proditem"], $param_price, $param_license);
		if ($result['retcode'] == STATUS_ITEMTITLE_EXISTS) return "DB: Item's title already exists!";

		return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => NULL]);
	}


	################################################################################################
	# ADMIN: Delete a product item
	# params: rowid
	# NOTE: Logical error: return plain text. Completed the order and needs to show a message: return Json.
	################################################################################################
	public function admin_items_delete() {
		if (!$this->request->isAJAX()) return "Bad request!";
		if ($this->request->getMethod() != 'post') return "Bad method!";

		// Sanitize rowid
		$param_rowid = $this->request->getPost('rowid', FILTER_SANITIZE_NUMBER_INT);
		if ($param_rowid < 1) return "Bad param: rowid==0!";

		// Check that it exists in the DB
		$db_proditems = new ProdItems();
		$dbrow = $db_proditems->where('rowid', $param_rowid)->first();
		if (empty($dbrow)) return "Bad param: rowid does not exist!";

		// Initialize some variables
		$folder_path = ITEMS_ROOT . "/" . $dbrow["folder_name"];

		// Delete files
		$files = glob($folder_path . '/{,.}*', GLOB_BRACE);
		foreach ($files as $file) {
			if (is_file($file)) unlink($file);
		}

		// Delete folder
		rmdir($folder_path);

		// Delete DB row
		$db_proditems->delete(['rowid' => $param_rowid]);

		// Final checking (for DB row and folder) and return
		$allok = TRUE;
		if (!empty($db_proditems->where('rowid', $param_rowid)->first())) $allok = FALSE;
		if (file_exists($folder_path)) $allok = FALSE;

		if ($allok) {
			return $this->response->setJSON(['retcode' => STATUS_SUCCESS, 'retdata' => "Item was deleted successfully."]);
		} else {
			return $this->response->setJSON(['retcode' => STATUS_GENERROR, 'retdata' => "Some components could not be deleted!"]);
		}
	}
}