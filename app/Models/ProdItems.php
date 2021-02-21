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

}