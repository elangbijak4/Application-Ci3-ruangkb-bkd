<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enkripsi {
    protected $CI;
    protected $chiper='aes-256';
    protected $key='1@@@@@!andisinra';
    protected $mode='ctr';
	public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('alert');
        $this->CI->load->library('encryption');
		//$this->CI->load->library('database');
    }
	
	public function index()
	{
		
	}

    public function initialize($setting)
	{
        $this->set($setting['chiper'],$setting['key'],$setting['mode']);
		$this->CI->encryption->initialize(
            array(
                    'cipher' => $this->chiper,
                    'mode' => $this->mode,
                    'key' => $this->key
            )
        );
	}

    public function set($chiper,$key,$mode)
    {
        $this->chiper=$chiper;
        $this->key=$key;
        $this->mode=$mode;
    }

	public function simplesignature_just_hashing($data,$salt='frontoffice_bkd_sulsel_2020')
	{
		return hash('sha256',hash('ripemd160',serialize($data).$salt));
    }
    
    //Cara menggunakan fungsi ini, temukan dulu data_yang_mau_divalidasi, dengan menemukannya pada tabel surat_masuk
    //berdasarkan idsurat_masuk yang tercantum pada nota unggah atau berdasarkan timestamp_pengiriman jika idsurat_masuk tidak disertakan pada nota.
    public function validation_simplesignature_just_hashing($data_yang_mau_divalidasi,$tandatangan)
	{
		if($this->simplesignature_just_hashing($data_yang_mau_divalidasi)==$tandatangan) {return TRUE;}else{return FALSE;}
    }

    //Fungsi ini menghasilkan representasi hex, karena jika string, ada kemungkin ada karakter yang tidak diperbolehkan dalam aturan URI Codeigniter, saat mendekripnya lewat URI juga
    public function enkripSimetri_data($plain_text)
	{
        $this->initialize($setting=array('chiper'=>$this->chiper,'key'=>$this->key,'mode'=>$this->mode));
        return $this->CI->encryption->encrypt($plain_text);
    }

    //Fungsi ini menerima representasi hex, karena jika string, ada kemungkinan ada karakter yang tidak diperbolehkan dalam aturan URI Codeigniter, saat mendekripnya lewat URI juga
    public function dekripSimetri_data($chiper_text)
	{
        $this->initialize($setting=array('chiper'=>$this->chiper,'key'=>$this->key,'mode'=>$this->mode));
        return $this->CI->encryption->decrypt($chiper_text);
    }

    //Fungsi ditujukan untuk membungkus data agar bisa dilewatkan ke addresbar browser, tetapi terenkripsi. agar bisa semua jenis data maka data terlebih dulu di serialized.
    public function enkapsulasiData($data)
    {
        return $this->strToHex($this->enkripSimetri_data(serialize($data)));
    }

    //Fungsi ini bertujuan untuk membaca data hasil enkapsulasi.
    public function dekapsulasiData($data)
    {
        return unserialize($this->dekripSimetri_data($this->hexToStr($data)));
    }
    
    public function changeDriver($driver)
	{
        try 
        {
            switch ($driver) 
            {
                case "mcrypt":
                    // Switch to the MCrypt driver
                    $this->CI->encryption->initialize(array('driver' => 'mcrypt'));
                    return TRUE;
                    break;
                case "openssl":
                    // Switch back to the OpenSSL driver
                    $this->CI->encryption->initialize(array('driver' => 'openssl'));
                    return TRUE;
                    break;
                default:
                    return NULL;
            } 
        
        } catch(Exception $e )
        {
            alert($e);
        }
	}

    //Fungsi ini dimodifikasi dari dari http://ditio.net/2008/11/04/php-string-to-hex-and-hex-to-string-functions/
    function strToHex($string)
    {
        $string=str_replace('%20',' ',$string);
        $hex='';
        for ($i=0; $i < strlen($string); $i++)
        {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    //Fungsi ini dimodifikasi dari dari http://ditio.net/2008/11/04/php-string-to-hex-and-hex-to-string-functions/
    function hexToStr($hex)
    {
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2)
        {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }

    //KODE UNTUK SANITASI
    /*
	public function sanitasi($string_ut_sanitasi){
        $con=mysqli_connect($this->CI->database->db->hostname,$this->CI->database->db->username,$this->CI->database->db->password,$this->CI->database->db->database);
        // Check connection
        if (mysqli_connect_errno()) {
        echo "Gagal Koneksi ke MySQL: " . mysqli_connect_error();
        }
        $input=sanitize($string_ut_sanitasi);
        return mysqli_real_escape_string($con,$input);
    }
    */

}
