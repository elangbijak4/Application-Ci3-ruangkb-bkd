<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
        {
         parent::__construct();
		 $this->load->model('Model_user', 'Muser');
		 //$this->load->model("agama_model");
		}
		
	public function index()
	{
		//cek session, jika ada langsung arahkan ke agamas
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');
        if (($user!==FALSE)&&($str==$hash))
        {   
			redirect(site_url('index'));
		} 
		
		//cek cookie, jika ada langsung arahkan ke agamas
		$cookie_user=get_cookie('munirah_muslim');
		$cookie_tersimpan = $this->Muser->get_by_cookie($cookie_user)->row();

		//$cookietersimpan = $cookie_tersimpan->cookie;
		//print_r($cookietersimpan);
		//echo "INI COOKIE DARI BASISDATA: ".$cookietersimpan;
		//if (($cookie_user!==FALSE)&&($cookie_user==$cookietersimpan))
		if($cookie_tersimpan)
        {   
			//redirect(site_url('agamas'));
			//$data["agamas"] = $this->agama_model->getAll();
            $this->load->view("index");
        } else {
			$this->load->view('Frontoffice/index');
		}
		
	}
  
	public function process()
	{
		//terima kiriman data dari halaman login
		$data = array(
			'username' => $this->_post('username')
		);
		$this->form_validation->set_rules('username', 'Username', 'required'); 
		$this->form_validation->set_rules('password', 'Password', 'required'); 

		if ($this->form_validation->run() == FALSE)
		{   
			$this->session->set_userdata('form_error', 'error');
			//echo "OK BRO";
			//$this->load->view('Frontoffice/index');
			redirect( site_url('Frontoffice/index') ); 
			
		}
		else
		{   
			$check = $this->Muser->auth_check($data);
			if ($check) {$oke='TRUE';} else {$oke='FALSE';}

			
			if(($check != false) && (password_verify($this->_post('password'),$check->password))){
				$user = array (
				'email' => $check->email,
				'username' => $check->nama_lengkap
				);
			
			$str = $check->email.$check->nama_lengkap."1@@@@@!andisinra";
			$str = hash("sha256", $str );
			$this->session->set_userdata('hash',$str);
			$this->session->set_userdata('user_ruangkaban',$user);

			//pembuatan cookie:
			$ok_cookie=$this->_post('remember_me');
			if($ok_cookie){
				//set cookie
				$cookie = array(
					'name'   => 'munirah_muslim',
					'value'  => 'Cookie dari BKD Provinsi Sulsel',
					'expire' => '3600',
					'domain' => $this->config->item('base_domain').'/ruangkaban-bkd',
					'path'   => '/',
					'secure' => TRUE
				 );
				$random = random_int(10000,90000);
				$str_cookie = $cookie['name'].$cookie['value'].$cookie['expire'].$random."1@@@@@!andisinra";
				$str_cookie = hash("sha256", $str_cookie );
				$data_cookie = array(
					'cookie' => $str_cookie,
					'username' => $user['username']
				);
				
				set_cookie('munirah_muslim',$str_cookie,3600*24);
				$this->Muser->update_cookie($data_cookie);
			} 
			redirect( site_url('Frontoffice/frontoffice_admin') ); 
			}
			$this->session->set_userdata('login_salah', 'salah');
			redirect( site_url('Frontoffice/index') );
		}
		
	} 

	public function logout()
		{
			$this->session->unset_userdata('user_ruangkaban');
			$this->session->set_userdata('keluar', 'keluar');
			delete_cookie('munirah_muslim');
			redirect(site_url('Frontoffice/index'));
		}

}
