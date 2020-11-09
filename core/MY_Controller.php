<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
abstract class MY_Controller extends CI_Controller
{
	// view data
	protected $msg;
	protected $loadJs    = [];
	protected $viewData  = [];
	protected $loadStyle = [];
	protected $pageTitle = '';

	public $viewBackend;
	public $isLogin;
	public $isAdmin;

	public $admUrl;
 	public $userId;
 	// public $pesertaId;
 	public $userData;
 	// public $pesertaData;

	public function __construct()
	{
		parent::__construct(); //persiapan agar fungsi Load bisa digunakan oleh turunannya, dan agar autoload di autoload.php bisa jalan.

		//$this->load->library('session'); //ini penyebab error, karena tidak diload librraynya maka baris 43 error, session tidak dikenal
		//tapi sudah saya sekalian load secara global dalam lingkup aplikasi, dengan setting di autoload yaitu $autoload['libraries'] = array('session');
		$this->_set_property();
		$this->_set_session(); //ketika diconstruct oleh turunannya maka session sudah otomatis dijalankan.
	}

	
	private function _set_property()
	{
		$this->pageTitle 	= 'bidang_kkpegawai';
		$this->admUrl 		= base_url('admin'); //cek ulangnanti ini, karena semestinya disesuaikan dengan path yang kamu buat
	}

	private function _set_session()
	{
		$this->userId = ($this->session->userdata('_userId') == NULL)? FALSE : $this->session->userdata('_userId');
		// $this->pesertaId = ($this->session->userdata('_pesertaId') == NULL)? FALSE : $this->session->userdata('_pesertaId');
		$this->userData = ($this->session->userdata('_userData') == NULL)? FALSE : $this->session->userdata('_userData');
		// $this->pesertaData = ($this->session->userdata('_pesertaData') == NULL)? FALSE : $this->session->userdata('_pesertaData');
		$this->viewBackend = ($this->session->userdata('_viewBackend') == NULL)? FALSE : $this->session->userdata('_viewBackend');
		$this->isLogin 	= ($this->session->userdata('_isLogin') == NULL)? FALSE : $this->session->userdata('_isLogin');
		$this->isAdmin 	= ($this->session->userdata('_isAdmin') == NULL)? FALSE : $this->session->userdata('_isAdmin');
	}

	// user data
	
	protected function _load_data($data)
	{
		$this->viewData = array_merge($this->viewData, $data);
		if (isset($data['pageTitle'])) {
			$this->pageTitle = $data['pageTitle'].' | Bidang_KKPegawai';
		}
		if(isset($data['cssFile'])){
			// var_dump($data['cssFile']);
			// die();
			$this->loadStyle = array_merge($this->loadStyle,$data['cssFile']);
		}
		if(isset($data['jsFile'])){
			$this->loadStyle = array_merge($this->loadJs,$data['jsFile']);
		}

		$this->viewData['pageTitle'] 	= $this->pageTitle;
	}

	// _post
	protected function _post($index) {
		return $this->input->post($index, TRUE); //dari mana method input? apakah turunan? sepertinya ini turunan. ini seprtinya dari kelas form yang sudah diload. 
		
	}

}

/**
 * admin controller
 */
abstract class Admin_Controller extends MY_Controller
{

	public function __construct()
    {
				parent::__construct();
				$this->load->model('Model_user', 'Muser');
				// $this->load->model("Msetting");
				// $admin = $this->Muser->getUserById();
				

				if (!true) {
					$user = [
						'fullname' => 'admin',
						'password' => 'admin',
						'email' => 'admin@admin.com',
						'tgl_buat' => date('Y-m-d H:i:s'),
						'tgl_edit' => date('Y-m-d H:i:s')
					];
					// $this->Muser->save($user);				
				}
		// bypass 
		$this->isLogin = true;
		$this->viewBackend = true;


        if (!$this->isLogin || !$this->viewBackend) {
        	redirect(base_url('login'),'refresh');
        }
    }  


	//ini perlu jika Nadia menggunakan ini untuk konstruk view
	public function _load_view($view, $data = [])
	{
		$data['msg'] = $this->session->flashdata('msg');
		// $data['site_name'] = $this->Msetting->getData('site_name');		
		$this->load->view('admin/layouts/header', $data);
		$this->load->view('admin/layouts/'.$data['sidebar'].'/sidebar', $data);
		$this->load->view($view, $data);
		// $this->load->view('admin/layouts/modal', $data);
		$this->load->view('admin/layouts/footer', $data);
	}

	
}

//ini perlu jika Nadia menggunakan ini untuk konstruk view
/**
 * frontpage controller
 */
abstract class Frontpage_Controller extends MY_Controller
{
	public function _load_view($view, $data = [])
	{
		$data['msg'] = $this->session->flashdata('msg');
		$this->load->view('frontend/layouts/header', $data);
		$this->load->view('frontend/layouts/'.$data['sidebar'].'/sidebar', $data);
		$this->load->view($view, $data);
		$this->load->view('frontend/layouts/footer', $data);
	}
}