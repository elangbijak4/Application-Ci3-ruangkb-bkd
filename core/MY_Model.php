<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	protected $table;
	protected $cols;
	protected $prefix 			= ''; //?
	protected $tblPost		 	= 'posts'; //?
	protected $tblUser 			= 'users'; //?
	protected $tblReader 		= 'readers'; //?
	protected $tblSetting 		= 'settings'; //?

	
	public function __construct($table)
	{
		if ($this->prefix) {
			$table = $this->prefix . $table;
		}
		$this->table 		= $table;
		$this->tblPost 		= $this->prefix . $this->tblPost;
		$this->tblUser 		= $this->prefix . $this->tblUser;
		$this->tblSetting 	= $this->prefix . $this->tblSetting;
		$this->tblReader 	= $this->prefix . $this->tblReader;

		parent::__construct();
	}

	//query ke tabel untuk mengambil seluruhnya, tetapi jika $limit != NULL maka dia menjadi query terbatas
	public function get($limit = null) //get all
	{
		$q = $this->db->get($this->table, $limit);
		return $q->result();
	}

	//mengambil nilai rekord yang nilainya di sebuah kolom ($column) adalah $id (sebarang nilai dan tipe nilai yang sesuai) 
	public function getById($id, $coloumn = 'id_user') //get index
	{
		$q = $this->db->where($coloumn, $id)->get($this->table);
		return $q->row();
	}

	//memeriksa apakah sebuh nilai ($index) adalah unik di dalam kolom ($colom)?
	public function isUnique($coloumn, $index)
	{
		$q = $this->db->where($coloumn, $index)->get($this->table);
		if ($q->num_rows() > 0) {
			return false;
		}

		return true;
	}

	//memeriksa apakah sebuah nilai $index adalah unik dimana $id nya tidak perlu unik di $columnId??
	public function isUniqueExceptId($coloumn, $index, $id, $coloumnId = 'id_user')
	{
		$q = $this->db->where_not_in($coloumnId, $id)
					   ->where($coloumn, $index)
					   ->get($this->table);
		if ($q->num_rows() > 0) {
			return false;
		}

		return true;
	}

	public function getByColoumn($coloumn, $value, $retRow = true)
	{
		$q = $this->db->where($coloumn, $value)->get($this->table);
		if ($retRow) {
			return $q->row();
		}
		return $q->result();
	}

	public function getByColoumnIn($coloumn = [], $values)
	{
		$q = $this->db->where_in($coloumn, $values)->get($this->table);
		return $q->result();
		
	}

	public function countTotal()
	{
		$q = $this->db->get($this->table);
		return $q->num_rows();
	}

	public function save($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function saveAndGetId($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function updateById($data, $id, $columnId = 'id_user')
	{
		$this->db->set($data);
		$this->db->where($columnId, $id);
		return $this->db->update($this->table);
	}

	public function updateByColoumn($data, $value, $column)
	{
		$this->db->set($data);
		$this->db->where($column, $value);
		return $this->db->update($this->table);
	}

	public function deleteById($id, $columnId = 'id_user')
	{
		$this->db->where($columnId, $id);
		return $this->db->delete($this->table);
	}

	public function deleteMultiple($ids, $column = 'id_user')
	{
		$this->db->where_in($column, $ids);
		$this->db->delete($this->table);
	}

	public function insertBatch($data)
	{
		$this->db->insert_batch($this->table, $data);
	}

	public function updateBatchById($data, $key)
	{
		$this->db->update_batch($this->table, $data, $key);
	}

}
