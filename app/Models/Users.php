<?php namespace App\Models;

/*
All parameters passed to these methods should have been previously sanitized.
*/

use CodeIgniter\Model;

class Users extends Model {
	protected $table = 'users';
	protected $primaryKey = 'rowid';
	protected $allowedFields = [
		'name', 'email', 'pw_hash', 'reg_datetime', 'subscription_type', 'subscription_datetime', 'todaydn_count', 'todaydn_datetime',
		'is_active', 'activation_datetime', 'activation_code', 'is_admin'
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
	# Register a new user
	################################################################################################
	public function add_user($name, $email, $password) {
		// User name and email must be unique
		if ($this->username_exists($name)) return array('retcode' => STATUS_USERNAME_EXISTS);
		if ($this->email_exists($email)) return array('retcode' => STATUS_EMAIL_EXISTS);

		// Prepare the fields ()
		helper('text');
		$pw_hash = $this->hash_password($password);
		$activation_code = random_string('alnum', 13);

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
			"is_admin"					=> FALSE
		]));
	}
}