<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_frommyframework extends CI_Model
{
    //private $data_koneksi_awal=array('host'=>'localhost','database'=>'dbbankdataver1.0','charset'=>'utf8','username'=>'root','password'=>'');
    //private $data_koneksi_awal=array($this->db->hostname,$this->db->database,$this->db->char_set,$this->db->username,$this->db->password);

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('alert');
        $this->load->library('enkripsi');
    }

    //======PERUBAHAN FUNGSI general_insertion_model VERSI BARU UNTUK SEKRETARIAT MENERIMA DARI FRONTOFFICE==========
    /**
     * Ini versi baru yang menghilangkan alert di akhir fungsi, karena adanya kesalahan atau error di hostingan.
     */
    public function general_insertion_model_baru($kiriman,$tabel) {
        $kiriman=penafsir_NULL($kiriman);
        $field=$this->penarik_key_model($tabel);
        $kiriman=penyamaUkuranArray($kiriman,$field);
        $query_insert = "INSERT INTO $tabel("; 
        $query_insert .="$field[1]";
        for ($i=2;$i<count($field);$i++) {$query_insert .=",$field[$i]";}
        /*foreach ($field as $isi) {if(!($field[0]) && !($field[1])){$query_insert .=",$isi";}} */
        $query_insert .=")VALUES (";
        $query_insert .="'".addslashes($kiriman[1])."'";
        for ($i=2;$i<count($field);$i++) {$query_insert .=",'".addslashes($kiriman[$i])."'";}
        /*foreach ($kiriman as $isi) {if(!($isi==$kiriman[0]) && !($isi==$kiriman[1]))$query_insert .=",'$isi'";}*/
        $query_insert .=")";
        $oke=$this->user_defined_query_model($query_insert,$token='andisinra');
        //if($oke){alert("Penambahan data sukses");} else {alert("Penambahan gagal. Silahkan ulangi kembali");}
        return $oke;
    }
    //======END PERUBAHAN FUNGSI general_insertion_model VERSI BARU UNTUK SEKRETARIAT MENERIMA DARI FRONTOFFICE======

    //===========================TAMBAHAN BARU 1 ROUND 2=================================

    public function query_dengan_limit($table,$mulai_rekord,$jumlah_rekord,$field_for_order,$order){
        $this->db->order_by($field_for_order, $order);
        return $this->db->get($table, $jumlah_rekord, $mulai_rekord);
    }
    
    public function pemeriksa_tabel_ada_kolom_tertentu($kolom='password'){
        $listTable=array();
        $tables = $this->db->list_tables();
        foreach ($tables as $table)
        {
            $fields = $this->db->list_fields($table);
            in_array($kolom,$fields)?array_push($listTable,$table):NULL;
        }
        return $listTable;
    }

    public function penarik_semua_nama_tabel(){
        return $this->db->list_tables();
    }

    public function penarik_semua_nama_kolom_sebuah_tabel($table){
        return $this->db->list_fields($table);
    }

    public function penarik_semua_nama_kolom_sebuah_query($query){
        return $this->db->query($query);
    }

    public function update_style_CI($tabel,$kolom_rujukan,$data){ //inshaa Allah letakkan handler error disini lan.
        //format $data adalah: $data[$kolom_target]=$nilai_kolom_target;
        $this->db->where($kolom_rujukan['nama_kolom'], $kolom_rujukan['nilai']);
        ($this->db->update($tabel, $data))?alert("Data sukses diubah"):alert("Data gagal diubah");
    }

    public function update_style_CI_no_alert($tabel,$kolom_rujukan,$data){ //inshaa Allah letakkan handler error disini lan.
        //format $data adalah: $data[$kolom_target]=$nilai_kolom_target;
        $this->db->where($kolom_rujukan['nama_kolom'], $kolom_rujukan['nilai']);
        ($this->db->update($tabel, $data))? $ok=TRUE: $ok=FALSE;
        return $ok;
    }

    public function pembaca_nilai_kolom_tertentu($table,$kolom_rujukan,$kolom_target){
        $this->db->select($kolom_target);
        $this->db->where($kolom_rujukan['nama_kolom'], $kolom_rujukan['nilai']);
        $query = $this->db->get($table);
        $ok=array();
        $i=0;
        foreach ($query->result() as $row){
            $ok[$i]=$row->$kolom_target;
            $i++;
        }
        return $ok;
    }

    public function pembaca_nilai_baris_tertentu($table,$kolom_rujukan){
        //$this->db->select($kolom_target);
        $this->db->where($kolom_rujukan['nama_kolom'], $kolom_rujukan['nilai']);
        $query = $this->db->get($table);
        return $query;
    }

    public function naikkan_counter_notifikasi($counter_table,$kolom_rujukan,$kolom_target){
        //baca counter terakhir
        $nilai_counter_terakhir=array();
        $nilai_counter_terakhir=$this->pembaca_nilai_kolom_tertentu($counter_table,$kolom_rujukan,$kolom_target);
        $nilai_counter_terakhir_berikut=$nilai_counter_terakhir[0]+1;

        //masukkan nilai counter berikut
        $data[$kolom_target]=$nilai_counter_terakhir_berikut;
        $ok=$this->update_style_CI_no_alert($counter_table,$kolom_rujukan,$data);
        return $ok;
    }

    public function reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter'){
        //reset counter ke 0:
        $data[$kolom_target]=0;
        $ok=$this->update_style_CI_no_alert($counter_table,$kolom_rujukan,$data);
        return $ok;
    }

    
    //KODE UNTUK SANITASI
	public function sanitasi($string_ut_sanitasi){
        $con=mysqli_connect($this->db->hostname,$this->db->username,$this->db->password,$this->db->database);
        // Check connection
        if (mysqli_connect_errno()) {
        echo "Gagal Koneksi ke MySQL: " . mysqli_connect_error();
        }
        $input=sanitize($string_ut_sanitasi);
        return mysqli_real_escape_string($con,$input);
		
	}


    //=====================END TAMBAHAN BARU 1========================

    //ALHAMDULILLAH FUNGSI INI DITUJUKAN UNTUK MENGAMBIL SEMUA KEY DARI TABEL ATAU SEMBARANG QUERY YANG MENGHASILKAN TABEL UNTUK DITAMPILKAN
    //CONTOH PENGGUNAAN: print_r($this->penarik_key_string_ut_sebarang_query_model($query='select * from admin'));
    public function penarik_key_string_ut_sebarang_query_model($query){
        $Recordset=$this->user_defined_query_model($query,$token='andisinra');
			foreach ($Recordset as $isi) {
                $key_query=array_keys($isi);break;
            }
            $key_string=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($key_string,$index_array);
                }
            }
            unset($Recordset);
            return $key_string;
    }
    //ALHAMDULILLAH SUKSES MIGRASI, INI HANYA UNTUK MEMELIHARA KOMPATIBILITAS
	//Fungsi Query per halaman:  (SUDAH DITES, OK)
    public function page_row_Recordset1($pageNum_Recordset1,$maxRows_Recordset1,$tabel) 
    {
		$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;
        $query = $this->db->get($tabel,$startRow_Recordset1,$maxRows_Recordset1);
		$row_Recordset1 = $query->result();
        return $row_Recordset1;
        //gunakan $row_Recordset1->nama_kolom untuk menggunakan result $row_Recordset1
    }
    
    //ALHAMDULILLAH OK SUKSES MIGRASI, INI HANYA UNTUK MEMELIHARA KOMPATIBILITAS
    //Fungsi Query per halaman ($Recordset1):  (SUDAH DITES, OK)
    public function page_Recordset1($pageNum_Recordset1,$maxRows_Recordset1,$tabel,$order) 
    {
        $field=array();
        $field=$this->penarik_key_model($tabel);
        $startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;
        $this->db->order_by($field[0], $order);
        $Recordset1 = $this->db->get($tabel,$startRow_Recordset1,$maxRows_Recordset1);
        return $Recordset1;
        //gunakan $Recordset1->result() untuk menggunakan objek $Recordset1 yang dihasilkannya.
    }

    //ALHAMDULILLAH SUKSES MIGRASI
    //Fungsi Query per halaman ($Recordset1):  (SUDAH DITES, OK)
    public function page_Recordset1_byquery($pageNum_Recordset1,$maxRows_Recordset1,$query_Recordset1) {
        $startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;
        $query_Recordset1=$query_Recordset1." LIMIT ".$startRow_Recordset1.",".$maxRows_Recordset1;
        $hasil_query=$this->user_defined_query_model($query_Recordset1,$token='andisinra');
        return $hasil_query;
        //gunakan $recordset['nama _kolom'] untuk akses karena hasilnya berupa array.
    }

    //ALHAMDULILLAH SUKSES MIGRASI
    //Fungsi Cari berdasar per halaman: //Alhamdulillah dah di tes OK
    public function page_Recordset1_search($pageNum_Recordset1,$maxRows_Recordset1,$tabel,$kolom_cari,$key_cari) {
        $startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;
        $this->db->like($kolom_cari, $key_cari);
        $Recordset1 = $this->db->get($tabel,$startRow_Recordset1,$maxRows_Recordset1);
        return $Recordset1;
    }

    //ALHAMDULILLAH OK SUKSES MIGRASI, INI HANYA UNTUK MEMELIHARA KOMPATIBILITAS, nama fungsi sudah terlanjur dimana-mana
    //Fungsi penarik nama2 key dari tabel
    public function penarik_key_model($table)
    {
        return $this->db->list_fields($table); 
    }

    //ALHAMDULILLAH OK, SUKSES MIGRASI.
    //Fungsi penghitung jumlah rekord
    public function jumlah_rekord($tabel) 
    {    
        return $this->db->count_all($tabel);
    }

    //ALHAMDULILLAH OK, SUKSES MIGRASI.
    //Fungsi yg mengembalikan jumlah halaman (SUDAH DITES, OK)
    public function total_halaman($maxRows_Recordset1,$tabel) 
    {
        return ceil($this->jumlah_rekord($tabel)/$maxRows_Recordset1)-1;
    }

    //ALHAMDULILLAH OK SUSKSES MIGRASI.
    //Fungsi penarik dengan query user defined
    public function user_defined_query_model($query,$token)
    {
        //if($data_koneksi===NULL){$data_koneksi=$this->$data_koneksi_awal;echo "OK MASUK";}

        if($token!=='andisinra') {alert($e='maaf anda tidak menunjukkan otoritas akses');} else 
        {
            try
            {
                $query=str_replace('%20',' ',$query);
                //$db = new PDO("mysql:host=".$this->data_koneksi_awal['host'].";dbname=".$this->data_koneksi_awal['database'].";charset=".$this->data_koneksi_awal['charset'],$this->data_koneksi_awal['username'],$this->data_koneksi_awal['password']);
                $db = new PDO("mysql:host=".$this->db->hostname.";dbname=".$this->db->database.";charset=".$this->db->char_set,$this->db->username,$this->db->password);
                $hasil_query=$db->query ($query,PDO::FETCH_BOTH);
                $db=NULL;
                return $hasil_query;
                //UNTUK MENGGUNAKAN HASIL INI, agar menjadi array, GUNAKAN method fetch. Contoh: $hasil_query->fetch() atau $hasil_query->fetch(PDO::FETCH_ASSOC) 
            }
            catch(PDOException  $e )
            {
                alert($e);
            }
        }
          
    }

    //ALHAMDULILLAH SUKSES.
    //CONTOH TES DI CONTROLLER:
    /*
    	public function tes3(){
		$this->header_lengkap_bootstrap_controller();
		//$this->user_defined_query_controller_as_array_terenkripsi($query_terenkripsi,$token_terenkripsi);
		$tes=$this->user_defined_query_controller_as_array($query='select * from admin',$token="andisinra");
		echo "is array? ".is_array($tes)."<br>";
		print_r($tes);
	}
    */
    public function user_defined_query_model_as_array($query,$token)
    {
        //if($data_koneksi===NULL){$data_koneksi=$this->$data_koneksi_awal;echo "OK MASUK";}

        if($token!=='andisinra') {alert($e='maaf anda tidak menunjukkan otoritas akses');} else 
        {
            try
            {
                $query=str_replace('%20',' ',$query);
                //$db = new PDO("mysql:host=".$this->data_koneksi_awal['host'].";dbname=".$this->data_koneksi_awal['database'].";charset=".$this->data_koneksi_awal['charset'],$this->data_koneksi_awal['username'],$this->data_koneksi_awal['password']);
                $db = new PDO("mysql:host=".$this->db->hostname.";dbname=".$this->db->database.";charset=".$this->db->char_set,$this->db->username,$this->db->password);
                $hasil_query=$db->query ($query,PDO::FETCH_BOTH);
                $Recordset=array();
                //ambil nama-nama key dari hasil query
                foreach ($hasil_query as $isi) {
                    array_push($Recordset,$isi);
                }
                unset($hasil_query);
                $db=NULL;
                return $Recordset;
                //UNTUK MENGGUNAKAN HASIL INI, agar menjadi array, GUNAKAN method fetch. Contoh: $hasil_query->fetch() atau $hasil_query->fetch(PDO::FETCH_ASSOC) 
            }
            catch(PDOException  $e )
            {
                alert($e);
            }
        }
          
    }

    //BUAT JAGA JIKA ORANG INGIN MEMASUKKAN ARGUMEN YANG TERENKRIPSI, tapi mesti sepakat setingan enkripsinya terlebih dulu.
    public function user_defined_query_model_as_array_dekrip($query,$token)
    {
        //if($data_koneksi===NULL){$data_koneksi=$this->$data_koneksi_awal;echo "OK MASUK";}
        $query=$this->enkripsi->penerima_terenkripsi_simetri($query,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'));
        $token=$this->enkripsi->penerima_terenkripsi_simetri($token,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'));
        if($token!=='andisinra') {alert($e='maaf anda tidak menunjukkan otoritas akses');} else 
        {
            try
            {
                $query=str_replace('%20',' ',$query);
                //$db = new PDO("mysql:host=".$this->data_koneksi_awal['host'].";dbname=".$this->data_koneksi_awal['database'].";charset=".$this->data_koneksi_awal['charset'],$this->data_koneksi_awal['username'],$this->data_koneksi_awal['password']);
                $db = new PDO("mysql:host=".$this->db->hostname.";dbname=".$this->db->database.";charset=".$this->db->char_set,$this->db->username,$this->db->password);
                $hasil_query=$db->query ($query,PDO::FETCH_BOTH);
                $Recordset=array();
                //ambil nama-nama key dari hasil query
                foreach ($hasil_query as $isi) {
                    array_push($Recordset,$isi);
                }
                unset($hasil_query);
                $db=NULL;
                return $Recordset;
                //UNTUK MENGGUNAKAN HASIL INI, agar menjadi array, GUNAKAN method fetch. Contoh: $hasil_query->fetch() atau $hasil_query->fetch(PDO::FETCH_ASSOC) 
            }
            catch(PDOException  $e )
            {
                alert($e);
            }
        }
          
    }

    //ALHAMDULILLAH OK SUSKSES MIGRASI.
    //Fungsi penghitung jumlah rekord dari query userdefined.
    public function jumlah_rekord_query ($query,$token='andisinra') {
        return $this->user_defined_query_model($query,$token)->rowCount();
    }

    //ALHAMDULILLAH OK SUSKSES MIGRASI.
    //Fungsi insersi universal:
    public function general_insertion_model($kiriman,$tabel) {
        $kiriman=penafsir_NULL($kiriman);
        $field=$this->penarik_key_model($tabel);
        $kiriman=penyamaUkuranArray($kiriman,$field);
        $query_insert = "INSERT INTO $tabel("; 
        $query_insert .="$field[1]";
        for ($i=2;$i<count($field);$i++) {$query_insert .=",$field[$i]";}
        /*foreach ($field as $isi) {if(!($field[0]) && !($field[1])){$query_insert .=",$isi";}} */
        $query_insert .=")VALUES (";
        $query_insert .="'".addslashes($kiriman[1])."'";
        for ($i=2;$i<count($field);$i++) {$query_insert .=",'".addslashes($kiriman[$i])."'";}
        /*foreach ($kiriman as $isi) {if(!($isi==$kiriman[0]) && !($isi==$kiriman[1]))$query_insert .=",'$isi'";}*/
        $query_insert .=")";
        $oke=$this->user_defined_query_model($query_insert,$token='andisinra');
        if($oke){alert("Penambahan data sukses");} else {alert("Penambahan gagal. Silahkan ulangi kembali");}
        return $oke;
    }

    public function general_insertion_model_no_alert($kiriman,$tabel) {
        $kiriman=penafsir_NULL($kiriman);
        $field=$this->penarik_key_model($tabel);
        $kiriman=penyamaUkuranArray($kiriman,$field);
        $query_insert = "INSERT INTO $tabel("; 
        $query_insert .="$field[1]";
        for ($i=2;$i<count($field);$i++) {$query_insert .=",$field[$i]";}
        /*foreach ($field as $isi) {if(!($field[0]) && !($field[1])){$query_insert .=",$isi";}} */
        $query_insert .=")VALUES (";
        $query_insert .="'".addslashes($kiriman[1])."'";
        for ($i=2;$i<count($field);$i++) {$query_insert .=",'".addslashes($kiriman[$i])."'";}
        /*foreach ($kiriman as $isi) {if(!($isi==$kiriman[0]) && !($isi==$kiriman[1]))$query_insert .=",'$isi'";}*/
        $query_insert .=")";
        $oke=$this->user_defined_query_model($query_insert,$token='andisinra');
        //if($oke){alert("Penambahan data sukses");} else {alert("Penambahan gagal. Silahkan ulangi kembali");}
        return $oke;
    }


    //BELUM DITES
    //ALHAMDULILLAH SUDAH DITES.
    //Alhamdulillah dah di tes OK
    public function general_update_model($kiriman,$tabel) {
        //mysql_select_db($database) or die ("<script>alert(\"Info: ".mysql_error()."\")</script>");
        $field=$this->penarik_key_model($tabel);
        //foreach ($kiriman as $isi) {if ($isi="") {$isi=NULL;}}
        $kiriman=penafsir_NULL($kiriman);
        for ($i=1;$i<count($kiriman);$i++) {
        if($kiriman[$i]==NULL) {} else 
        {
            $query_update="UPDATE ".$tabel." SET ".$field[$i]."='".addslashes($kiriman[$i])."' WHERE ".$field[0]."=".addslashes($kiriman[0]);
            $oke=$this->user_defined_query_model($query_update,$token='andisinra');
        }
        }
        if($oke){alert("Update data sukses");} else {alert("Update gagal");}
    }

    //ALAHAMDULILLAH SUDAH DITES.
    //Fungsi ini berguna untuk menyediakan nilai <select><option value=$kolom_value>$kolom_label</option></select>
    public function penarik_kolom_model($kolom_value,$kolom_label,$tabel)
    {
        $query_Recordset1 = "SELECT DISTINCT $kolom_value, $kolom_label FROM $tabel";
        $Recordset1=$this->user_defined_query_model($query_Recordset1,$token='andisinra');
        return $Recordset1;
        /**
         * Contoh penggunaan hasilnya
           foreach ($Recordset1 as $k) {
            if($k[$kolom[1]]==$selected){
                echo "<option value=\"".$k[$kolom[0]]."\" selected >".$k[$kolom[1]]."</option>";
            }else{
                echo "<option value=\"".$k[$kolom[0]]."\">".$k[$kolom[1]]."</option>";
            }
            $i++;
            }
         */
    }

    //ALAHMDULILLAH SUDAH DITES SUKSES
    //Fungsi ini bertujuan menarik semua nama kolom dari tabel_panel.
    //tabel_panel = tabel yang memuat nama-nama tabel, strukturnya: array('index'=>'nama_tabel'), itu saja
    //penarik_key_model_panel = menghasilkan semua nama kolom dari daftar tabel di tabel_panel.
    //dikatakan tabel_panel karena ada sebuah panel yang menggunakan n buah tabel untuk tampil pada frontend.
    //sehingga perlu menarik informasi nama kolom semua n buah tabel tersebut.
    public function penarik_key_model_panel($tabel_panel)
    {
        $kolom=array();
        foreach($tabel_panel as $key => $k){
            $fields[$key] = $this->penarik_key_model($k);
            $columns[$key] = sizeof($fields[$key]);
            for ($i = 0; $i < $columns[$key]; $i++) { 
            $kolom[$key][$i]=$fields[$key][$i];
            }
            
    }
        return $kolom;//hasilnya sebuah vektor yang memetakan indeks ke nama kolom.
    }

    //ALHAMDULILLAH SUDAH DITES.
    public function hapus_rekord($tabel,$id) {
        //mysql_select_db($database) or die ("<script>alert(\"Info3: ".mysql_error()."\")</script>");
        $field=$this->penarik_key_model($tabel);
        $deleteSQL = "DELETE FROM $tabel WHERE $field[0]=$id";
        $oke=$this->user_defined_query_model($deleteSQL,$token='andisinra');
        if($oke){alert("Penghapusan data sukses");} else {alert("Penghapusan data gagal");}
    }

    public function konvers_recordset_PDOStatement_to_array($recordset){
        foreach ($recordset as $isi) {
            array_push($array,$isi);
        }
        unset($recordset);
        return $array;
    }
    
    //Konvers Recordset dari hasil perintah $this->db() milik CI
    public function konvers_recordset_CI_to_array($Recordset1,$nama_kolom){
        $array=array();
        $j=0;
        foreach($Recordset1->result() as $isi){
            echo $isi->$nama_kolom[2];
            /*
            foreach($nama_kolom as $k){
                $array[$j][$k]=$isi->$k;
            }
            $j++;*/
        }
        return $array;
    }

    //penarik key untuk query yang dihasilkan oleh perintah $this->db() milik CI
    public function penarik_key_query_CI($query){
        $query = $this->db->query($query);
        $kolom=array();
        $i=0;
        foreach ($query->list_fields() as $field){
                $kolom[$i]=$field;
        }
        return $kolom;
    }

}