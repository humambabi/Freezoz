<?php namespace App\Controllers;

use App\Models\Users;

class Pages extends BaseController {
	################################################################################################
	public function err404() {
		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => FALSE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));
		echo view('pages/error404');
		echo view('assets/footer', array("is_home" => FALSE));
	}

	################################################################################################
	public function home() {
		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => TRUE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));

		$Items = array(
			[
				'title'	=> "New item for 2021!"
			],
			[
				'title'	=> "Another item for test!"
			],
			[
				'title'	=> "Another item for test!"
			],
			[
				'title'	=> "Another item for test!"
			],
			[
				'title'	=> "Another item for test!"
			],
			[
				'title'	=> "Another item for test!"
			],
			[
				'title'	=> "Another item for test!"
			],
			[
				'title'	=> "New item for 2021!"
			],
			[
				'title'	=> "Another item for test!"
			],
			[
				'title'	=> "Another item for test!"
			]
		);
		echo view('pages/home', array("items" => $Items));

		echo view('assets/footer', array("is_home" => TRUE));
	}

	################################################################################################
	public function register() {
		// If user is logged-in, redirect to the home page
		if ($this->bLoggedIn) {
			header('Location: ' . base_url());
			exit();
		}

		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => FALSE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));
		echo view('pages/register');
		echo view('assets/footer', array("is_home" => FALSE));
	}

	################################################################################################
	public function terms() {
		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => FALSE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));
		echo view('pages/terms');
		echo view('assets/footer', array("is_home" => FALSE));
	}

	################################################################################################
	public function privacy() {
		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => FALSE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));
		echo view('pages/privacy');
		echo view('assets/footer', array("is_home" => FALSE));
	}

	################################################################################################
	public function activation($email, $act_code) {
		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => FALSE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));

		while(TRUE) { // (Only to use 'break' when needed)
			$db_users = new Users();
			$row = $db_users->where('email', $email)->first();

			// Email doesn't exist in db
			if (empty($row)) {
				echo view('pages/activation', array("bad_link" => TRUE));
				break;
			}

			// Wrong activation code (doesn't exist for that email)
			if ($row['activation_code'] !== $act_code) { // This handles upper/lower case characters too
				echo view('pages/activation', array("bad_link" => TRUE));
				break;
			}

			// Check for an already activated acoount (before checking for an expired link)
			if ($row['is_active']) {
				echo view('pages/activation', array("bad_link" => FALSE, "already_activated" => TRUE));
				break;
			}

			// Check for activation code expiry
			$expired = TRUE;
			$deadline = strtotime($row['activation_datetime'] . " +0000 + " . strval(ACTIVATION_EMAIL_VALIDITY_MINUTES) . " minutes");
			$remaining = $deadline - time(); # time() is timezone independent (=UTC)
			if ($remaining >= 0) $expired = FALSE; # Not expired yet (when there are positive number of seconds remaining)
			if ($remaining > (ACTIVATION_EMAIL_VALIDITY_MINUTES * 60)) {
				// Remaining MUST not exceed the valid period itself - that would be a programming error!
				echo view('pages/activation', array("bad_link" => TRUE));
				break;
			}

			if ($expired) {
				echo view('pages/activation', array("bad_link" => FALSE, "already_activated" => FALSE, "expired" => TRUE));
				break;
			}

			// If we reached here, it means that: link is good (email + actcode), account is not yet activated, and link has not expired yet
			$act_status = $db_users->update($row['rowid'], ['is_active' => TRUE]); // Activate the account
			echo view('pages/activation', array("bad_link" => FALSE, "already_activated" => FALSE, "expired" => FALSE, "act_status" => $act_status));
			break; // Only one iteration intended
		}

		echo view('assets/footer', array("is_home" => FALSE));
	}

	################################################################################################
	public function forgot_pw() {
		// If user is logged-in, redirect to the home page
		if ($this->bLoggedIn) {
			header('Location: ' . base_url());
			exit();
		}

		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => FALSE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));
		echo view('pages/forgot_pw');
		echo view('assets/footer', array("is_home" => FALSE));
	}

	################################################################################################
	public function reset_pw($email, $resetpw_code) {
		echo view('assets/header');
		echo view('assets/navbar', array("is_home" => FALSE, "user_loggedin" => $this->bLoggedIn, "is_admin" => $this->bAdmin));

		while(TRUE) { // (Only to use 'break' when needed)
			$db_users = new Users();
			$row = $db_users->where('email', $email)->first();

			// Email doesn't exist in db
			if (empty($row)) {
				echo view('pages/reset_pw', array("bad_link" => TRUE));
				break;
			}

			// Wrong reset_pw code (doesn't exist for that email)
			if ($row['resetpw_code'] !== $resetpw_code) { // This handles upper/lower case characters too
				echo view('pages/reset_pw', array("bad_link" => TRUE));
				break;
			}

			// Check for reset_pw code expiry
			$expired = TRUE;
			$deadline = strtotime($row['resetpw_datetime'] . " +0000 + " . strval(RESETPW_EMAIL_VALIDITY_MINUTES) . " minutes");
			$remaining = $deadline - time(); # time() is timezone independent (=UTC)
			if ($remaining >= 0) $expired = FALSE; # Not expired yet (when there are positive number of seconds remaining)
			if ($remaining > (RESETPW_EMAIL_VALIDITY_MINUTES * 60)) {
				// Remaining MUST not exceed the valid period itself - that would be a programming error!
				echo view('pages/reset_pw', array("bad_link" => TRUE));
				break;
			}

			if ($expired) {
				echo view('pages/reset_pw', array("bad_link" => FALSE, "expired" => TRUE));
				break;
			}

			// If we reached here, it means that: link is good (email + resetpw code), and link has not expired yet
			// If the user is already logged in, show warning (only after all other checks are done)
			echo view('pages/reset_pw', array("bad_link" => FALSE, "expired" => FALSE, "email" => $email, "resetpw_code" => $resetpw_code, "user_loggedin" => $this->bLoggedIn));
			break; // Only one iteration intended
		}

		echo view('assets/footer', array("is_home" => FALSE));
	}

	################################################################################################
	public function admincp() {
		// If user is NOT logged-in, or logged-in but NOT an admin, show the 404 erro page
		if (!$this->bLoggedIn || !$this->bAdmin) {
			// This will not change the browser's url
			return $this->err404();
		}

		
		echo view('assets/admin_header');
		echo view('assets/admin_navbar');

		echo "Humam";

		echo view('assets/admin_footer');
	}
}