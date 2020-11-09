<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_user extends MY_Model {
	public function __construct()
	{
		$this->load->database(); //Siapkan saja inijangan sampai error nanti, tapi kan semestinya instance db sudah global
		$table = "user"; //ini karena dikatakan sebagai kelas Model_user, yaitu kelas untuk CRUID tabel user
		$kolom_cookie = "cookie";
        parent::__construct($table);
	}

	//-------------------------------------------------------------------------------------------------
	//tambahan dari luar
	//-------------------------------------------------------------------------------------------------
	public function auth_check($data)
    {
		$query = $this->db->get_where('user', $data);
		
        if($query){   
		 return $query->row();
		}
		
		
        return false;
	}

	public function update_cookie($data)
    {
        $this->db->where('username', $data['username']);
        $this->db->update($this->table, array('cookie'=>$data['cookie']));
	}
	
    public function insert_user($data)
    {
        $insert = $this->db->insert('user', $data);
        if ($insert) {
           return $this->db->insert_id();
        } else {
            return false;
        }
    }

	public function get_by_cookie($cookie)
    {
        $this->db->where('cookie', $cookie);
		return $this->db->get($this->table);
    }
	//-------------------------------------------------------------------------------------------------


	//-------------------------------------------------------------------------------------------------
	//Asli dari nadia
	//-------------------------------------------------------------------------------------------------
	//method ini jika bertemu nama yang sma dalam username (nama_lengkap) maka nama tersebut diberi tambahan dibelakangnya sehingga dia menjadi unik. 
	private function setUniqueUsername($name, $inc = '')
	{
		$uname = $name.$inc;//slugify($name . $inc);
		$q = $this->db->where('username', $uname)->get($this->table);
		if ($q->num_rows() > 0) {
			return $this->setUniqueUsername($uname, $q->num_rows() + 1);
		}
		return $uname;
	}
		
	//ambil semua rekord dari tabel user
	public function getUser()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		// $this->db->where_in('systype', [1,2,3]); // not peserta
		$q = $this->db->get();
		return $q->result();
	}

	//menarik rekord untuk nama tertentu di kolom nama_lengkap
	public function getUserById($id = 'admin', $col = 'username')
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($col, $id); // not peserta
		$q = $this->db->get();
		return $q->result();
	}

	//Ambil rekord yang sesuai dalam 4 kolom
	public function getUserByIdByStatusByBidangByPass($where = array('username' => 'admin', 'status_admin' => 'operator', 'bidang' => 'kk_pegawai', 'password' => NULL))
	{
		
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($where); 
		$q = $this->db->get();
		return $q->result();
	}

	public function save($data)
	{
			// $user = [
		// 	'nama' => $data['nama'],
		// 	'sysusername' => $this->setUniqueUsername($data['sysusername']),
		// 	'syspass' => password_hash($data['syspass'], PASSWORD_BCRYPT),
		// 	'systype' => $data['systype'],
		// 	'sysemail' => $data['sysemail'],
		// 	'parent' => (isset($data['parent'])) ? $data['parent'] : null ,
		// 	'status' => (isset($data['status'])) ? $data['status'] : 1 ,
		// ];
		$user = [
			'username' => $this->setUniqueUsername($data['username']),
			'password' => password_hash($data['password'], PASSWORD_BCRYPT),
			'email' => $data['email'],
			'bidang' => (isset($data['bidang'])) ? $data['bidang'] : 'bidang_kkpegawai',
			'status_admin' => (isset($data['status'])) ? $data['status'] : 'operator',
			'pass_berlaku_mulai' => (isset($data['pass_berlaku_mulai'])) ? $data['pass_berlaku_mulai'] : date('Y-m-d H:i:s'),
			'pass_sampai_tgl' => (isset($data['pass_sampai_tgl'])) ? $data['pass_sampai_tgl'] : date('Y-m-d H:i:s')
		];
		$this->db->insert($this->table, $user);
	}

	public function updatePassword($password, $index, $key = 'id')
	{
		$data['password'] = password_hash($password, PASSWORD_BCRYPT);
		$this->db->where($key, $index);
		$this->db->update($this->table, $data);
	}

	public function updateEmail($email, $index, $key = 'id')
	{
		$data['email'] = $email;
		$this->db->where($key, $index);
		$this->db->update($this->table, $data);
	}

	//fungsi ini ga perlu ada disini. mestinya adanya di controller.
  	public function login($email, $password)
    {

    	$datalogin['valid'] 	= false;
    	$datalogin['redirect']	= 'login';
    	$datalogin['msg']		= '';

      	// $user = $this->db->where('email',  $email)->get($this->table)->row();
      	$user = (object) ['password' => password_hash($password, PASSWORD_BCRYPT), 'id' => 1];
		
      // var_dump($user->password);die;
    	if (!$user) {
    		$datalogin['msg'] =  'Email dan password tidak cocok dengan data kami.';
    		return $datalogin;
    	}

    	if (!password_verify($password, $user->password)) {
    		$datalogin['msg'] = 'Password anda tidak cocok dengan data kami.';
    		return $datalogin;
		}
		
		unset($user->password);

		// session
		if ($user) {
			$datalogin['redirect'] = 'backoffice/dashboard';
			$this->session->set_userdata('_userId', $user->id);
			$this->session->set_userdata('_isLogin', TRUE);
			$this->session->set_userdata('_isAdmin', TRUE);
			$this->session->set_userdata('_viewBackend', TRUE);
			$this->session->set_userdata('_userData', $user);
		}

    	$datalogin['valid']	= true;
    	$datalogin['msg']	= 'Login berhasil ...';
    	return $datalogin;
	}

	//ini perlu ada di model.
	public function delete($id)
	{
		if ($id == 1) {
			return "Cannot delete user default admin";
			// die();
		}
		$this->db->trans_start();

		$this->db->where('id_user', $id);
		$q = $this->db->get($this->tblUser);
		$user = $q->row();

		// $this->db->where('email', $user->email);
		// $this->db->delete($this->tblUser);

		$this->db->where('id_user', $id);
		$del = $this->db->delete($this->tblUser);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return log_message('error', 'Error query transaction database table on model ' . get_class() . ' method ' . __FUNCTION__.' ()');
		}

		return $del;
	}

}