<?php namespace App\Models;

/*
All parameters passed to these methods should have been previously sanitized.
*/

use CodeIgniter\Model;

class ProdItems extends Model {
	protected $table = 'proditems';
	protected $primaryKey = 'rowid';
	
	protected $returnType     = 'array';
	protected $useSoftDeletes = false;
	
	protected $allowedFields = [
		'added_on', 'title', 'description', 'tags', 'folder_name', 'previmg_small', 'previmg_full', 'prevvid_small', 'prevvid_full',
		'add_audios', 'add_previmgs', 'add_prevvids', 'proditem', 'price', 'license', 'rating', 'ratescount', 'downsalecount'
	];


	################################################################################################
	# Register a new user
	################################################################################################
//	public function query_rowids($q_where) {
//		$query = $this->query("SELECT * FROM `{$this->table}`" . ($q_where ? " WHERE $q_where" : "") . ";");
//		return $query->num_rows();
//	}


	################################################################################################
	# Register a new user
	################################################################################################
//	public function add_proditem($added_on, $title, $prod_by, $desc, $tags, $path_previmg_small, $path_previmg_full, $path_prevvid_small, $path_prevvid_full,
//			$path_add_audio, $path_add_previmgs, $path_add_prevvids, $path_proditem, $price, $lic, $rating, $ratescount, $downsalecount) {
		// User name and email must be unique
		//if ($this->username_exists($name)) return array('retcode' => STATUS_USERNAME_EXISTS);
		//if ($this->email_exists($email)) return array('retcode' => STATUS_EMAIL_EXISTS);

		// Prepare the fields ()
		//helper('text');
		//$pw_hash = $this->hash_password($password);
		//$activation_code = random_string('alnum', VALIDATION_EMAILCODE_LENGTH);

		// Save the user
		//return array('retcode' => STATUS_SUCCESS, 'retdata' => $this->insert([
		//	"name"						=> $name,
		//	"email"						=> $email,
		//	"pw_hash"					=> $pw_hash,
		//	"reg_datetime"				=> gmdate('Y-m-d H:i:s'),
		//	"subscription_type"		=> SUBSCRIPTION_FREE,
		//	"subscription_datetime"	=> gmdate('Y-m-d H:i:s'),
		//	"todaydn_count"			=> 0,
		//	"todaydn_datetime"		=> gmdate('Y-m-d H:i:s'),
		//	"is_active"					=> FALSE,
		//	"activation_datetime"	=> gmdate('Y-m-d H:i:s'),
		//	"activation_code"			=> $activation_code,
		//	"resetpw_datetime"		=> NULL,
		//	"resetpw_code"				=> NULL,
		//	"is_admin"					=> FALSE
		//]));
//		return TRUE;
//	}
}