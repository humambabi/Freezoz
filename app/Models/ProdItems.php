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
	# Check item's title availability
	################################################################################################
	public function itemtitle_exists($title) {
		if ($this->where('title', $title)->first()) return TRUE; else return FALSE;
	}


	################################################################################################
	# Add a new ProductItem
	################################################################################################
	public function add_item($datetime, $title, $description, $tags, $foldername, $imgsmall, $imgfull, $vidsmall, $vidfull,
			$addimgs, $addvids, $addauds, $proditem, $price, $license) {
		# Item's title must be unique
		if ($this->itemtitle_exists($title)) return array('retcode' => STATUS_ITEMTITLE_EXISTS);

		// Prepare the fields ()

		// Save the user
		return array('retcode' => STATUS_SUCCESS, 'retdata' => $this->insert([
			"added_on"					=> $datetime, //gmdate('Y-m-d H:i:s')
			"title"						=> $title,
			"description"				=> $description,
			"tags"						=> $tags,
			"folder_name"				=> $foldername,
			"previmg_small"			=> $imgsmall,
			"previmg_full"				=> $imgfull,
			"prevvid_small"			=> $vidsmall,
			"prevvid_full"				=> $vidfull,
			"add_audios"				=> (count($addauds) > 0) ? json_encode($addauds) : NULL,
			"add_previmgs"				=> (count($addimgs) > 0) ? json_encode($addimgs) : NULL,
			"add_prevvids"				=> (count($addvids) > 0) ? json_encode($addvids) : NULL,
			"proditem"					=> $proditem,
			"price"						=> $price,
			"license"   				=> $license,
			"rating"						=> 0,
			"ratescount" 				=> 0,
			"downsalecount" 			=> 0
		]));
	}
}