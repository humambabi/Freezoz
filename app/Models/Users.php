<?php namespace App\Models;

/*
All parameters passed to these methods should have been previously sanitized.
*/

use CodeIgniter\Model;

class Users extends Model {
	protected $table = 'users';
	protected $primaryKey = 'rowid';
	
	protected $returnType     = 'array';
	protected $useSoftDeletes = false;
	
	protected $allowedFields = [
		'name', 'email', 'pw_hash', 'reg_datetime', 'subscription_type', 'subscription_datetime', 'todaydn_count', 'todaydn_datetime',
		'is_active', 'activation_datetime', 'activation_code', 'resetpw_datetime', 'resetpw_code', 'is_admin'
	];


	################################################################################################
	# Check user name availability
	################################################################################################
	public function username_exists($name) {
		if ($this->where('name', $name)->first() == null) return FALSE; else return TRUE;
	}


	################################################################################################
	# Check email availability
	################################################################################################
	public function email_exists($email) {
		if ($this->where('email', $email)->first() == null) return FALSE; else return TRUE;
	}


	################################################################################################
	# Hash password
	################################################################################################
	public function hash_password($password) {
		// sha1 is significantly more secure but slower than md5. crc32 is fast
		return hash('sha1', $password) . hash('crc32', $password);
	}


	################################################################################################
	# Get the activation code for an email
	################################################################################################
	public function get_activationcode($email) {
		$row = $this->where('email', $email)->first();
		return $row['activation_code'];
	}


	################################################################################################
	# Create, save in the DB, and return a "reset password" code
	################################################################################################
	public function createSaveGet_resetpwcode($email) {
		helper('text');
		$code = random_string('alnum', VALIDATION_EMAILCODE_LENGTH);

		$ret = $this->where('email', $email)->set([
			'resetpw_datetime' 	=> gmdate('Y-m-d H:i:s'),
			'resetpw_code'			=> $code
		])->update();
		
		return $code;
	}


	################################################################################################
	# Reset password: saves a new password (its hash) associating it with an email, and erasing the pw_reset code
	################################################################################################
	public function reset_password($email, $password, $resetpw_code) {
		$row = $this->where('email', $email)->first();
		
		if (empty($row)) return array('retcode' => STATUS_EMAIL_NOTFOUND);
		if (empty($row['resetpw_code']) || (strcmp($row['resetpw_code'], $resetpw_code) != 0) || empty($row['resetpw_datetime'])) return array('retcode' => STATUS_RESETPWCODE_INVALID);

		$pw_hash = $this->hash_password($password);

		/*
		After saving the new password, nullify rpw_code (leave rpw_datetime to detect pw reset & datetime later) in the db
		(in order to allow resetting the password only ONCE per SUCCESSFUL request)
		*/
		$ret = $this->update($row['rowid'], [
			"pw_hash"				=> $pw_hash,
			"resetpw_datetime"	=> NULL,
			"resetpw_code"			=> NULL
		]);

		return array('retcode' => STATUS_SUCCESS, 'retdata' => $ret);
	}

	
	################################################################################################
	# Register a new user
	################################################################################################
	public function add_user($name, $email, $password) {
		// User name and email must be unique
		if ($this->username_exists($name)) return array('retcode' => STATUS_USERNAME_EXISTS);
		if ($this->email_exists($email)) return array('retcode' => STATUS_EMAIL_EXISTS);

		// Prepare the fields ()
		helper('text');
		$pw_hash = $this->hash_password($password);
		$activation_code = random_string('alnum', VALIDATION_EMAILCODE_LENGTH);

		// Save the user
		return array('retcode' => STATUS_SUCCESS, 'retdata' => $this->insert([
			"name"						=> $name,
			"email"						=> $email,
			"pw_hash"					=> $pw_hash,
			"reg_datetime"				=> gmdate('Y-m-d H:i:s'),
			"subscription_type"		=> SUBSCRIPTION_FREE,
			"subscription_datetime"	=> gmdate('Y-m-d H:i:s'),
			"todaydn_count"			=> 0,
			"todaydn_datetime"		=> gmdate('Y-m-d H:i:s'),
			"is_active"					=> FALSE,
			"activation_datetime"	=> gmdate('Y-m-d H:i:s'),
			"activation_code"			=> $activation_code,
			"resetpw_datetime"		=> NULL,
			"resetpw_code"				=> NULL,
			"is_admin"					=> FALSE
		]));
	}
}