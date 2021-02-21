<?php namespace App\Controllers;

use App\Models\Users;

class Pages extends BaseController {
	################################################################################################
	public function err404() { // It's safer to load the public components even if the error originated from the admin side
		echo view('assets/public/header', array('add_css' => ["/css/error404.css"]));
		echo view('assets/public/navbar', array('is_home' => FALSE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));
		echo view('pages/error404');
		echo view('assets/public/footer', array('is_home' => FALSE, 'add_js' => ["/js/error404.js"]));
	}


	################################################################################################
	public function home() {
		echo view('assets/public/header', array('add_css' => ["/css/public/home.css"]));
		echo view('assets/public/navbar', array('is_home' => TRUE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));
		echo view('pages/public/home');
		echo view('assets/public/footer', array('is_home' => TRUE, 'add_js' => ["/js/public/home.js"]));
	}

	
	################################################################################################
	public function register() {
		// If user is logged-in, redirect to the home page
		if ($this->bLoggedIn) {
			header('Location: ' . base_url());
			exit();
		}

		echo view('assets/public/header', array('add_css' => ["/css/public/register.css"]));
		echo view('assets/public/navbar', array('is_home' => FALSE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));
		echo view('pages/public/register');
		echo view('assets/public/footer', array('is_home' => FALSE, 'add_js' => ["/js/public/register.js"]));
	}


	################################################################################################
	public function terms() {
		echo view('assets/public/header', array('add_css' => []));
		echo view('assets/public/navbar', array('is_home' => FALSE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));
		echo view('pages/public/terms');
		echo view('assets/public/footer', array('is_home' => FALSE, 'add_js' => []));
	}


	################################################################################################
	public function privacy() {
		echo view('assets/public/header', array('add_css' => []));
		echo view('assets/public/navbar', array('is_home' => FALSE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));
		echo view('pages/public/privacy');
		echo view('assets/public/footer', array('is_home' => FALSE, 'add_js' => []));
	}


	################################################################################################
	public function activation($email, $act_code) {
		echo view('assets/public/header', array('add_css' => ["/css/public/activation.css"]));
		echo view('assets/public/navbar', array('is_home' => FALSE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));

		while(TRUE) { // (Only to use 'break' when needed)
			$db_users = new Users();
			$row = $db_users->where('email', $email)->first();

			// Email doesn't exist in db
			if (empty($row)) {
				echo view('pages/public/activation', array("bad_link" => TRUE));
				break;
			}

			// Wrong activation code (doesn't exist for that email)
			if ($row['activation_code'] !== $act_code) { // This handles upper/lower case characters too
				echo view('pages/public/activation', array("bad_link" => TRUE));
				break;
			}

			// Check for an already activated acoount (before checking for an expired link)
			if ($row['is_active']) {
				echo view('pages/public/activation', array("bad_link" => FALSE, "already_activated" => TRUE));
				break;
			}

			// Check for activation code expiry
			$expired = TRUE;
			$deadline = strtotime($row['activation_datetime'] . " +0000 + " . strval(ACTIVATION_EMAIL_VALIDITY_MINUTES) . " minutes");
			$remaining = $deadline - time(); # time() is timezone independent (=UTC)
			if ($remaining >= 0) $expired = FALSE; # Not expired yet (when there are positive number of seconds remaining)
			if ($remaining > (ACTIVATION_EMAIL_VALIDITY_MINUTES * 60)) {
				// Remaining MUST not exceed the valid period itself - that would be a programming error!
				echo view('pages/public/activation', array("bad_link" => TRUE));
				break;
			}

			if ($expired) {
				echo view('pages/public/activation', array("bad_link" => FALSE, "already_activated" => FALSE, "expired" => TRUE));
				break;
			}

			// If we reached here, it means that: link is good (email + actcode), account is not yet activated, and link has not expired yet
			$act_status = $db_users->update($row['rowid'], ['is_active' => TRUE]); // Activate the account
			echo view('pages/public/activation', array("bad_link" => FALSE, "already_activated" => FALSE, "expired" => FALSE, "act_status" => $act_status));
			break; // Only one iteration intended
		}

		echo view('assets/public/footer', array('is_home' => FALSE, 'add_js' => ["/js/public/activation.js"]));
	}


	################################################################################################
	public function forgot_pw() {
		// If user is logged-in, redirect to the home page
		if ($this->bLoggedIn) {
			header('Location: ' . base_url());
			exit();
		}

		echo view('assets/public/header', array('add_css' => ["/css/public/forgot_pw.css"]));
		echo view('assets/public/navbar', array('is_home' => FALSE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));
		echo view('pages/public/forgot_pw');
		echo view('assets/public/footer', array('is_home' => FALSE, 'add_js' => ["/js/public/forgot_pw.js"]));
	}


	################################################################################################
	public function reset_pw($email, $resetpw_code) {
		echo view('assets/public/header', array('add_css' => ["/css/public/reset_pw.css"]));
		echo view('assets/public/navbar', array('is_home' => FALSE, 'user_loggedin' => $this->bLoggedIn, 'is_admin' => $this->bAdmin));

		while(TRUE) { // (Only to use 'break' when needed)
			$db_users = new Users();
			$row = $db_users->where('email', $email)->first();

			// Email doesn't exist in db
			if (empty($row)) {
				echo view('pages/public/reset_pw', array("bad_link" => TRUE));
				break;
			}

			// Wrong reset_pw code (doesn't exist for that email)
			if ($row['resetpw_code'] !== $resetpw_code) { // This handles upper/lower case characters too
				echo view('pages/public/reset_pw', array("bad_link" => TRUE));
				break;
			}

			// Check for reset_pw code expiry
			$expired = TRUE;
			$deadline = strtotime($row['resetpw_datetime'] . " +0000 + " . strval(RESETPW_EMAIL_VALIDITY_MINUTES) . " minutes");
			$remaining = $deadline - time(); # time() is timezone independent (=UTC)
			if ($remaining >= 0) $expired = FALSE; # Not expired yet (when there are positive number of seconds remaining)
			if ($remaining > (RESETPW_EMAIL_VALIDITY_MINUTES * 60)) {
				// Remaining MUST not exceed the valid period itself - that would be a programming error!
				echo view('pages/public/reset_pw', array("bad_link" => TRUE));
				break;
			}

			if ($expired) {
				echo view('pages/public/reset_pw', array("bad_link" => FALSE, "expired" => TRUE));
				break;
			}

			// If we reached here, it means that: link is good (email + resetpw code), and link has not expired yet
			// If the user is already logged in, show warning (only after all other checks are done)
			echo view('pages/public/reset_pw', array("bad_link" => FALSE, "expired" => FALSE, "email" => $email, "resetpw_code" => $resetpw_code, "user_loggedin" => $this->bLoggedIn));
			break; // Only one iteration intended
		}

		echo view('assets/public/footer', array('is_home' => FALSE, 'add_js' => ["/js/public/reset_pw.js"]));
	}


	################################################################################################
	public function admincp($page) {
		# If user is NOT logged-in, or logged-in but NOT an admin, show the 404 error page
		if (!$this->bLoggedIn || !$this->bAdmin) {
			$PagesClass = new Pages;
			return $PagesClass->err404(); # This will not change the browser's url
		}

		# If the 'page' parameter is invalid, also show the 404 page
		if (!in_array($page, ['dashboard', 'items'])) {
			$PagesClass = new Pages;
			return $PagesClass->err404(); # This will not change the browser's url
		}
		
		# Set the special page body, css & js
		$page_body = "";
		$add_css = array();
		$add_js = array();

		switch ($page) {
			case "dashboard": {
				$page_body = "dashboard";
				break;
			}

			case "items" : {
				$page_body = "items";
				break;
			}
		}

		# Output the html
		echo view('assets/admin/header', array('add_css' => $add_css));
		echo view('assets/admin/navbar', array('page' => $page_body));
		echo view('pages/admin/' . $page_body);
		echo view('assets/admin/footer', array('add_js' => $add_js));
	}
}