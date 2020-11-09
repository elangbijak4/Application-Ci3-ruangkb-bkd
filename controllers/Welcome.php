<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function index()
	{
		redirect( site_url('Frontoffice') );
		//$this->load->view('welcome_message');
	}

	public function about()
	{
		$this->load->view('about');
	}

	public function contact()
	{
		$this->load->view('contact');
	}

	public function team()
	{
		$this->load->view('team');
	}
}
