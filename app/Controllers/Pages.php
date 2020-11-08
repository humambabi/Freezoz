<?php namespace App\Controllers;

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

}
