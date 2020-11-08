<?php namespace App\Controllers;

class Assets extends BaseController {
	##############################################
	public function signin_form() {
    	return view('assets/signinform', array("base_uri" => $this->base_uri));
	}

	##############################################
	public function categories_form() {
		return view('assets/categories');
	}
}
