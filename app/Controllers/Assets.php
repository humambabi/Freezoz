<?php namespace App\Controllers;

class Assets extends BaseController {
	##############################################
	public function signin_form() {
    	return view('assets/signinform');
	}

	##############################################
	public function categories_form() {
		return view('assets/categories');
	}
}
