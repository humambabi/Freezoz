<?php namespace App\Controllers;

use App\Models\Users;

class Pages extends BaseController {
	##############################################
	public function home() {
		echo view('assets/header', array("base_uri" => $this->base_uri));
		echo view('assets/navbar', array("base_uri" => $this->base_uri, "is_home" => TRUE));
		echo view('pages/home');
		echo view('assets/footer', array("base_uri" => $this->base_uri, "is_home" => TRUE));
	}

	##############################################
	public function register() {
		// If user is logged-in, redirect to the home page
		if (!empty(session(SESSION_USERID))) {
			header('Location: ' . $this->base_uri);
			exit();
		}

		echo view('assets/header', array("base_uri" => $this->base_uri));
		echo view('assets/navbar', array("base_uri" => $this->base_uri, "is_home" => FALSE));
		echo view('pages/register', array("base_uri" => $this->base_uri));
		echo view('assets/footer', array("base_uri" => $this->base_uri, "is_home" => FALSE));
	}

	##############################################
	public function terms() {
		echo view('assets/header', array("base_uri" => $this->base_uri));
		echo view('assets/navbar', array("base_uri" => $this->base_uri, "is_home" => FALSE));
		echo view('pages/terms', array("base_uri" => $this->base_uri));
		echo view('assets/footer', array("base_uri" => $this->base_uri, "is_home" => FALSE));
	}

	##############################################
	public function privacy() {
		echo view('assets/header', array("base_uri" => $this->base_uri));
		echo view('assets/navbar', array("base_uri" => $this->base_uri, "is_home" => FALSE));
		echo view('pages/privacy', array("base_uri" => $this->base_uri));
		echo view('assets/footer', array("base_uri" => $this->base_uri, "is_home" => FALSE));
	}

	##############################################
	public function activation($email, $act_code) {
		echo view('assets/header', array("base_uri" => $this->base_uri));
		echo view('assets/navbar', array("base_uri" => $this->base_uri, "is_home" => FALSE));

		while(TRUE) {
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

		echo view('assets/footer', array("base_uri" => $this->base_uri, "is_home" => FALSE));
	}

	##############################################
	public function recovery() {
		
	}
}
