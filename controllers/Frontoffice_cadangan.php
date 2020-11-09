<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * catatan path unggah berkas saat memanggil?
 * start: index.php di view
 * [1]:Frontoffice/frontoffice_unggahberkas/
 * [2]:Frontoffice/frontoffice_index/
 * end: index.php di view
 * 
 * catatan path teruskan surat?
 * start: Frontoffice/gerbang/rincian_penampil_tabel, path selanjutnya disimpan setelah tombol "verifikasi" diklik, di dalam tombol "Teruskan ke Sekretariat".
 * [1]:Frontoffice/teruskan_surat/, surat dan berkas dipersiapkan untuk dikirim.
 * [2]:admin_frontoffice/dashboard di view, data surat dan berkas yang hendak dikirim, diberikan ke halaman ini untuk diteruskan.
 * [3]:Frontoffice/coba_kirim, jika tombol "kirim" di klik pada modal yang muncul maka data surat dan berkas yang hendak dikirim + data2 lain, dikirim sebagai data $_POST ke Frontoffice/coba_kirim. 
 *  
 */

 //===============KHUSUS UNTUK OFFICE==================================
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
 use PhpOffice\PhpWord\PhpWord;
 use PhpOffice\PhpWord\Writer\Word2007;

 //===============END KHUSUS UNTUK OFFICE==============================

class Frontoffice extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model("model_frommyframework");
		$this->load->helper('alert');
		$this->load->library('form_validation');
		$this->load->library('enkripsi');
		$this->load->library('viewfrommyframework');

	}
	
	public function index()
	{
		$this->load->view('loginpage');
	}
	
	//===========================================FUNGSI AGENDA NEW==========================================================================
	public function tambah_file(){
		$i=$this->session->userdata('i');
		$class=$this->enkripsi->HexToStr($_POST['class']);
		$id=$this->enkripsi->HexToStr($_POST['id']);
		$nama_komponen=$this->enkripsi->HexToStr($_POST['nama_komponen']);
		$nama_komponen_tambahan_total=array();
		$this->session->userdata('data_nama_tambahan')!==NULL?$nama_komponen_tambahan_total=$this->session->userdata('data_nama_tambahan'):NULL;
		array_push($nama_komponen_tambahan_total,$nama_komponen.$i);
		$this->session->set_userdata('data_nama_tambahan',$nama_komponen_tambahan_total);
		$this->viewfrommyframework->buat_komponen_form('multi-file',$nama_komponen.$i,$class,$id.$i,'','','','','','','','','','');
        //deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
	}
	public function tampilkan_tabel_agenda_new(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		//$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>2),$kolom_target='nilai_counter');
		$table='tbagenda_kerja';
		$nama_kolom_id='idagenda_kerja';
		$this->tampil_tabel_cruid_agenda_new($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL);
	}
	
	public function tampil_tabel_cruid_agenda_new($table='surat_masuk',$nama_kolom_id='idsurat_masuk',$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL){
		//echo "INI NILAI LIMIT: ".$limit;
		$kolom_cari_new=array('acara_kegiatan','tempat','tanggal','status_kegiatan');
		$nama_kolom_direktori_surat=array('surat'=>'direktori_surat_pendukung','foto'=>'direktori_foto_yg_menyertai');
		//inshaa Allah tambhkan kolom-kolom ini di tabel tbagenda_kerja.
		$this->tampil_tabel_cruid_new_core_agenda($table,$nama_kolom_id,$order,$limit,$currentpage,$page_awal,$jumlah_page_tampil,$mode,$kolom_cari,$nilai_kolom_cari,$kolom_cari_new,$nama_kolom_direktori_surat);
		//inshaa Allah buat pada saat mode CRUID_agenda, dia mengeksekusi penampil cruid agend yang khusus agenda, inshaa Allah buat yang penampil khusus tersebut.
	}
	
	public function tampil_tabel_cruid_new_core_agenda($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL,$kolom_cari_new,$nama_kolom_direktori_surat){
		//echo "INI NILAI LIMIT DALAM: ".$limit;
		$awal=($currentpage-1)*$limit;
		$numrekord=$this->db->count_all($table);
		$jumlah_halaman=ceil($numrekord/$limit);

		//echo "<br>INI JUMLAH HALAMAN: ".$jumlah_halaman;
		//echo "<br>INI mode: ".$mode;
		//echo "<br>INI kolom_cari: ".$kolom_cari;
		//echo "<br>INI nilai_kolom_cari: ".$nilai_kolom_cari;

		echo "<div align=left>".ucwords(implode(' ',explode('_',$table)))." >> Halaman ".$currentpage."</div>";
		//echo "<h4 id=\"h4_atas\"><i class=\"fas fa-envelope fa-lg text-white-100\"></i> ".ucwords(implode(' ',explode('_',$table)))."</h4>";
		
		echo "<h4>Kelola Acara ".ucwords(implode(' ',explode('_',$table)))."</h4>";
		echo "<hr><div align=right>";
		echo "<button style=\"position:absolute; left:11px;\" id=\"tambah_data\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#modal_tambah_data\"><i class='fas fa-plus-circle text-white-100'></i> Tambahkan Acara</button>";
		echo "<button id=\"pencarian_lanjut_atas\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#searchmodal\">Pencarian Lanjut</button>";
		echo "</div><hr>";
		//tttt
		//Kode untuk tambah data:
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tambah_data\").click(function(){
                  var loading = $(\"#pra_modal_tambah_data\");
				  var tampilkan = $(\"#penampil_modal_tambah_data\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tambah_data_new_agenda/".$table)."',{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
        ";

		echo "
			<!-- Modal Tambah Data -->
			<div class='modal fade' id='modal_tambah_data' role='dialog' style='z-index:100000;'>
				<div class='modal-dialog modal-lg'>
				
				<!-- Modal content-->
				<div class='modal-content'>
					<div class='modal-header'>
					<h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_modal_tambah_data' style='width:65%;' align='center' >
					<i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
					<!--
					<div class='progress' style='margin-top:50px; height:20px'>
						<div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
						mohon tunggu...
						</div>
					</div>
					-->
					</center>
					<div id=penampil_modal_tambah_data align='center' style='width:100%;'></div>
					</div>
					<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
					</div>
				</div>
				
				</div>
			</div>
		";

		echo "
			<style>
				#myInput1{
					width:30%;
				}
				#h4_atas{
					display:none;
				}
				#h4_bawah{
					display:block;
				}
				#quantity{
					margin-left:5px;
					width:70px;
				}
				#tampilbaris{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#myInput1{
						width:100%;
					}
					#h4_atas{
						display:block;
						margin-top:20px;
					}
					#pencarian_lanjut_atas{
						visibility:hidden;
					}
					#h4_bawah{
						display:none;
					}
					#quantity{
						margin-left:0px;
						width:40%;
					}
					#tampilbaris{
						margin-left:0px;
						width:59%;
					}
				  }
			</style>
			<script>
				$(document).ready(function(){
				$(\"#myInput1\").on(\"keyup\", function() {
					var value = $(this).val().toLowerCase();
					$(\"#myTable1 tr\").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
				});
			</script>
				<div align=left> 
				<label for=\"quantity\" style=\"float:left;line-height:2.2;\">Tampilkan jumlah maksimal surat: </label>
				<input type=\"number\" class=\"form-control\" id=\"quantity\" name=\"quantity\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-info\" id=\"tampilbaris\" style=\"height:35px;\">Tampilkan</button>
				<input type=\"text\" class=\"form-control\" id=\"myInput1\" style=\"float:right;height:35px;min-width:100px;\" placeholder=\"Filter...\">
				</div>
		";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tampilbaris\").click(function(){
                  var loading = $(\"#pra_tabel\");
				  var tampilkan = $(\"#penampil_tabel\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tampil_tabel_cruid_agenda_new/".$table."/".$nama_kolom_id."/desc/")."'+limit,{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
		";

		$mode==NULL?$query=$this->sanitasi_controller("select * from $table order by $nama_kolom_id $order limit $awal,$limit"):$query=$this->sanitasi_controller("select * from $table where $kolom_cari LIKE ")."'%".$this->sanitasi_controller($nilai_kolom_cari)."%'".$this->sanitasi_controller(" order by $nama_kolom_id $order limit 0,$limit");
		//echo "<br>INI query: ".$query;
		//$query=$this->sanitasi_controller($query);
		//echo "<br> INI sehabis disanitasi: ".$query;
		//$this->penampil_tabel_no_foto_controller($table,$nama_kolom_id,$array_atribut=array("","id=\"myTable\" class=\"table table-condensed table-hover table-striped\"",""),$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		//$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_masuk_frontoffice_surat_masuk ($kolom_cari,$nama_kolom_direktori_surat,$array_atribut,$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		$this->viewfrommyframework->penampil_tabel_untuk_agenda_new($table,$kolom_cari_new,$nama_kolom_direktori_surat,$array_atribut=array("","id=\"myTable1\" class=\"table table-striped\"",""),$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		echo "
			<style>
				#blokpage{
					display:flex; justify-content:center;
				}
				@media screen and (max-width: 480px) {
					#blokpage{
						justify-content:left;
					}
				}
			</style>
			<div id=\"blokpage\">
			<nav aria-label='...'>
			<ul class='pagination'>";

			//Siapkan nomor-nomor page yang mau ditampilkan
			$array_page=NULL;
			$j=0;
			for($i=$page_awal;$i<=($page_awal+($jumlah_page_tampil-1));$i++){
				$array_page[$j]=$i;
				if($limit*$i>$numrekord)break;
				$j++;
			}
			//print_r($array_page);;
				
			if($currentpage<=$jumlah_page_tampil){
				echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
			}else{
				echo "<li class='page-item' id='Previous'><a class='page-link' href='#'>Previous</a></li>";
				$current_pagePrevious=$array_page[0]-1;
				$page_awalPrevious=$current_pagePrevious-($jumlah_page_tampil-1);
				echo "
						<script>
						$(document).ready(function(){
							$(\"#Previous\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							var limit=$(\"#quantity\").val();
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url("/Frontoffice/tampil_tabel_cruid_agenda_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_pagePrevious+'/'+$page_awalPrevious+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
							function(data,status){
								loading.fadeOut();
								tampilkan.html(data);
								tampilkan.fadeIn(2000);
							});
							});
							});
						</script>
				";
			}

			
			//echo "<br>INI current_page: ".$currentpage;
			//echo "<br>INI page_awal: ".$page_awal;

			//Tampilkan nomor-nomor halaman di paging
			for($i=$array_page[0];$i<=$array_page[sizeof($array_page)-1];$i++){
				if($currentpage==$i){
					//echo "<br>INI DALAM currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item active' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_agenda_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";				
				}else{
					//echo "<br>INI LUAR currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_agenda_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";
				}
				//if($i==$jumlah_page_tampil){break;}
			}
		
		//echo "<br>INI jumlah_halaman: ".$jumlah_halaman;
		//echo "<br>INI jumlah_page_tampil: ".$jumlah_page_tampil;
		//echo "<br>INI currentpage: ".$currentpage;
		//echo "<br>INI TOTAL HITUNG: ".($array_page[0]+$jumlah_page_tampil-1);
		//if($jumlah_halaman>$jumlah_page_tampil && !($currentpage==$jumlah_halaman)){

		//Kode untuk tombol Next:
		if(($array_page[0]+$jumlah_page_tampil-1)<$jumlah_halaman){
			echo "<li class='page-item' id=\"Next\"><a class='page-link' href='#'>Next</a></li>";
			$current_page=$array_page[sizeof($array_page)-1]+1;
			$page_awal=$current_page;
			echo "
					<script>
					$(document).ready(function(){
						$(\"#Next\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_agenda_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_page+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
			";
		}
		else{
			echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
		}

		echo "
			<li class='page-item disabled'><a class='page-link' href='#'>$jumlah_halaman page</a></li>
			<li class='page-item disabled'><a class='page-link' href='#'>$numrekord rekord</a></li>
			</ul>
			</nav>
			</div>
		";

		//go to page:
		echo "
			<style>
				#gotopage{
					margin-left:5px;
					width:70px;
				}
				#go{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#pencarianlanjut{
						width:100%;
					}
					#gotopage{
						margin-left:0px;
						width:40%;
					}
					#go{
						margin-left:3px;
					}
				}
			</style>
				<div align=left>
				<div style=\"float:left;\">
				<label for=\"gotopage\" style=\"float:left;line-height:2.2;\">Page: </label>
				<input type=\"number\" class=\"form-control\" id=\"gotopage\" name=\"gotopage\" min=\"1\" value=\"".$currentpage."\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-primary\" id=\"go\" style=\"height:35px;width:40px;\">go</button>
				</div>
				<button class=\"btn btn-xs btn-primary\" id=\"pencarianlanjut\" data-toggle=\"modal\" data-target=\"#searchmodal\" style=\"height:35px;float:right;\">Pencarian Lanjut</button>
				</div>
			";

			//Kode untuk id=gotopage dan id=go 
			echo "
					<script>
					$(document).ready(function(){
						$(\"#go\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_agenda_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";
			
			//Modal untuk pencarian lanjut:
			$fields = $this->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			echo "
				<!-- Modal Searching-->
				<div class=\"modal fade\" id=\"searchmodal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
					<div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
						<div class=\"modal-header\">
						<h5 class=\"modal-title\" id=\"exampleModalLabel\">Mode Pencarian Lanjut</h5>
						<button class=\"close\" type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\">
							<span aria-hidden=\"true\">×</span>
						</button>
						</div>
						<div class=\"modal-body\" style=\"display:flex; justify-content:center;flex-wrap: wrap;\">
						
						<input class=\"form-control\" type=\"text\" id=\"nilai_kolom_cari\" placeholder=\"Search...\"> 
						<button class=\"btn btn-xs\" disabled>Berdasarkan</button> 
						<select class=\"form-control\" id=\"kolom_cari\" name=\"kolom_cari\">";
						echo "<option value=".$fields[0].">Pilih nama kolom tabel</option>";
						foreach ($fields as $field){
							echo "<option value=\"$field\">".ucwords(implode(' ',explode('_',$field)))."</option>";
						}
						echo "
						</select>
						</div>
						<hr>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<label for=\"limicari\" style=\"float:left;line-height:2.2;\">Jumlah maksimal rekord: </label>
							<input type=\"number\" class=\"form-control\" id=\"limicari\" name=\"limicari\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;width:75px;\">
						</div>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<button class=\"btn btn-xs btn-danger\" id=\"lakukanpencarian\" data-dismiss=\"modal\">Lakukan pencarian</button>
						</div>
						<div class=\"modal-footer\">
						<button class=\"btn btn-secondary\" type=\"button\" data-dismiss=\"modal\">Cancel</button>
						</div>
					</div>
					</div>
				</div>
			";

			//Kode untuk id=lakukanpencarian
			echo "
					<script>
					$(document).ready(function(){
						$(\"#lakukanpencarian\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#limicari\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						var kolom_cari=$(\"#kolom_cari\").val();
						var nilai_kolom_cari=$(\"#nilai_kolom_cari\").val();

						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_agenda_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil+'/TRUE/'+kolom_cari+'/'+nilai_kolom_cari,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";

	}

	//rrrr
	public function tambah_data_new_agenda($tabel)
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">Tambahkan Data Baru</span>";
		$fields = $this->db->list_fields($tabel);
		$coba=array();
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($fields[0],$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($fields[0],$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		foreach($coba as $key=>$k){
			//reset dulu semua komponen form
			$coba[$key][7]='';

			//ok mulai pengisian standar
			if($key==0) {
				$coba[$key][0]='hidden';
			}else{
				$coba[$key][0]='text';
	
				//jika nama kolom mengandung kata timestamp atau tanggal atau tgl:
				if(preg_grep("#timestamp#i",array($fields[$key])) || preg_grep("#tanggal#i",array($fields[$key])) || preg_grep("#tgl#i",array($fields[$key]))){
					$coba[$key][0]='date';
				}

				//jika nama kolom mengandung kata keterangan:
				if(preg_grep("#keterangan#i",array($fields[$key]))){
					$coba[$key][0]='area';
				}

				//jika nama kolom mengandung kata target_penerima:
				if(preg_grep("#target_penerima#i",array($fields[$key])) || preg_grep("#disposes_ke#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Kepala BKD';
				}

				//jika nama kolom mengandung kata status_pengirim:
				if(preg_grep("#status_pengirim#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='ASN internal';
				}

				//jika nama kolom mengandung kata dari_satker:
				if(preg_grep("#dari_satker#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='BADAN KEPEGAWAIAN DAERAH';
				}

				//jika nama kolom mengandung kata dari_bidang:
				if(preg_grep("#dari_bidang#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Kesejahteraan dan Kinerja Pegawai';
				}

				//jika nama kolom mengandung kata dari_bidang:
				if(preg_grep("#dari_bidang#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Kesejahteraan dan Kinerja Pegawai';
				}

				//jika nama kolom mengandung kata status_surat:
				if(preg_grep("#status_surat#i",array($fields[$key]))){
					$coba[$key][8]='masuk';
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
				}

				//jika nama kolom mengandung kata harapan_respon_hari:
				if(preg_grep("#harapan_respon_hari#i",array($fields[$key]))){
					$coba[$key][8]='3';
					$coba[$key][0]='number';
				}

				//jika nama kolom mengandung kata urgensi_surat:
				if(preg_grep("#urgensi_surat#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Yang Lain (Others)';
				}

				//jika nama kolom mengandung kata file:
				if(preg_grep("#nama_file#i",array($fields[$key]))){
					$coba[$key][0]='multi-file';
					$coba[$key][6]='<b>'.implode(' ',array('Unggah',implode(' ',explode('_',explode('nama_file_',$fields[$key])[1])))).'</b>';
				}

				//jika nama kolom mengandung kata file:
				if(preg_grep("#direktori#i",array($fields[$key]))){
					$coba[$key][0]='hidden';
				}
				//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		
			}
		}
		
		$target_action="Frontoffice/tambahkan_data_new_agenda/".$tabel;
		$komponen=$coba;
		$atribut_form=" id=\"form_unggah_berkas\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" ";
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Tambahkan','');
		//$tombol[0]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Proses penambahan...','Tambahkan data',"Frontoffice/tambahkan_data/".$tabel);
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		$value_selected_combo='';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//echo "OK BRO SIAP-SIAP";
		
		$this->viewfrommyframework->form_general_2_vertikal_non_iframe_new_agenda($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
		//echo "<iframe name='targetkosong' width='0' height='0' frameborder='0'></iframe>";
	}

	//BISMILLAH:
	public function tambahkan_data_new_agenda($table){
		//alert("OK BRO MASUK");
		if(isset($_POST['data_nama'])){
			//$data_post=array();
			$nama_komponen_tambahan_total=$this->session->userdata('data_nama_tambahan');
			//print_r($nama_komponen_tambahan_total);
			$directory_relatif_file_upload_surat='./public/arsip_surat_agenda/';
			$directory_relatif_file_upload_foto='./public/arsip_foto_agenda/';		

			$upload_array=array();
			$upload_array['nama_file_surat_pendukung']=upload('nama_file_surat_pendukung', $folder=$directory_relatif_file_upload_surat, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");//9999
			$upload_array['nama_file_foto']=upload('nama_file_foto', $folder=$directory_relatif_file_upload_foto, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
			foreach($nama_komponen_tambahan_total as $key=>$nama){
				preg_grep("#nama_file_surat_pendukung#i",array($nama))?$upload_array[$nama]=upload($nama, $folder=$directory_relatif_file_upload_surat, $types="pdf,jpeg,gif,png,doc,docs,bbc,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html")
				:$upload_array[$nama]=upload($nama, $folder=$directory_relatif_file_upload_foto, $types="pdf,jpeg,gif,png,doc,docs,docx,xls,bbc,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
			}

			/*
			echo "<br><br>";
			print_r($upload_array);
			echo "<br><br>";
			*/
			$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
			//$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');

			//Buat daftar nama file yang hendak disimpan:
			$list_nama_file_surat=array();
			$list_nama_file_foto=array();
			array_push($list_nama_file_surat,$upload_array['nama_file_surat_pendukung'][0]);
			array_push($list_nama_file_foto,$upload_array['nama_file_foto'][0]);
			foreach($nama_komponen_tambahan_total as $key=>$nama){
				if(preg_grep("#nama_file_surat_pendukung#i",array($nama))) {
					array_push($list_nama_file_surat,$upload_array[$nama][0]);
				}else{
					array_push($list_nama_file_foto,$upload_array[$nama][0]);
				}
			}

			//Buat daftar jejak direktori:
			$list_direktori_surat=array();
			$list_direktori_foto=array();
			array_push($list_direktori_surat,$directory_relatif_file_upload_surat.$upload_array['nama_file_surat_pendukung'][0]);
			array_push($list_direktori_foto,$directory_relatif_file_upload_foto.$upload_array['nama_file_foto'][0]);
			foreach($nama_komponen_tambahan_total as $key=>$nama){
				preg_grep("#nama_file_surat_pendukung#i",array($nama))? array_push($list_direktori_surat,$directory_relatif_file_upload_surat.$upload_array[$nama][0])
				:array_push($list_direktori_foto,$directory_relatif_file_upload_foto.$upload_array[$nama][0]);
			}

			/*
			//tes:
			print_r($list_nama_file_surat);
			echo "<br><br>";
			print_r($list_nama_file_foto);
			echo "<br><br>";
			print_r($list_direktori_surat);
			echo "<br><br>";
			print_r($list_direktori_foto);
			echo "<br><br>";
			*/
			//pindahkan isi $data_post ke $kiriman:
			
			$kiriman=array();
			foreach($data_nama_masuk as $key=>$k){
				if($k=='password'){
					array_push($kiriman,password_hash($_POST[$k], PASSWORD_BCRYPT));
				}else if(($k=='tanggal') || ($k=='sampai_tanggal')){
					array_push($kiriman,konversi_format_tgl_ttttbbhh_ke_hhbbtttt($_POST[$k]));
				}else if($k=='nama_file_surat_pendukung') {
					array_push($kiriman,implode(';',$list_nama_file_surat));
				}else if($k=='direktori_surat_pendukung') {
					array_push($kiriman,implode(';',$list_direktori_surat));
				}else if($k=='nama_file_foto') {
					array_push($kiriman,implode(';',$list_nama_file_foto));
				}else if($k=='direktori_foto_yg_menyertai') {
					array_push($kiriman,implode(';',$list_direktori_foto));
				}else{
					array_push($kiriman,$_POST[$k]);
				}
			}
			
			
			$oke=$this->general_insertion_controller($kiriman,$table);
			//print_r($kiriman);
			
			$this->session->set_userdata('modal','ok_new');
			$this->session->set_userdata('tabel',$table);;
			$this->load->view('admin_frontoffice/dashboard');
			
		} else {
			//alert("Data gagal terkirim");
			$this->session->set_userdata('modal','ok_new');
			$this->session->set_userdata('tabel',$table);;
			$this->load->view('admin_frontoffice/dashboard');
		}
	}

	public function tampilkan_list_surat_agenda($list_direktori){
		$list_direktori_dekrip=explode(';',$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($list_direktori)));
		echo "
		<style>
		.ok-hover:hover{
			background:rgb(217,237,247);
			font-weight:bold;
		}
		</style>
		<div class='container'>
		<h5>Pilih file surat yang hendak ditampilkan</h5>
		<div class='list-group' align=left>";
		foreach($list_direktori_dekrip as $key=>$isi){
			$ok=explode('/',$isi);
			echo "<a style='cursor:pointer;' class='list-group-item ok-hover' id='tombolfilesurat$key'>".$ok[sizeof($ok)-1]."</a>";
			$direktori_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($isi));
			$surat=explode('.',$isi);
			echo "
				<script>
				$(document).ready(function(){
					$(\"#tombolfilesurat$key\").click(function(){
					var loading = $(\"#pra_baca_surat_new\");
					var tampilkan = $(\"#penampil_baca_surat_new\");
					var loading1 = $(\"#pra_baca_surat_new1\");
					var tampilkan1 = $(\"#penampil_baca_surat_new1\");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('".site_url("/Frontoffice/buka_file_surat_pendukung_agenda/$list_direktori/$direktori_surat")."',{ data:\"okbro\"},
					function(data,status){
						loading.fadeOut();
						tampilkan.html(data);
						tampilkan.fadeIn(2000);
						loading1.fadeOut();";
						if(in_array($surat[sizeof($surat)-1],array('pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
			echo "		
					});
					});
					});
				</script>
			";
		}	
		echo "
		</div>
		</div>
		";
		
	}

	public function buka_file_surat_pendukung_agenda($list_direktori=NULL,$direktori_surat=NULL){
		$list_direktori_dekrip=explode(';',$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($list_direktori)));
		$direktori_surat_terpilih=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($direktori_surat));
		$ok1=explode('/',$direktori_surat_terpilih);
		$nama_file=$ok1[sizeof($ok1)-1];
		$tipe_file=explode('.',$nama_file);
		echo "
		<form>
			<div class='form-group' align=left>
			<label for='sel1'>Pilih file berikut untuk ditampilkan atau diunduh:</label>
			<select class='form-control' id='sel1' onchange='ok(this);'>";
			foreach($list_direktori_dekrip as $key=>$isi){
				$ok=explode('/',$isi);
				if($ok[sizeof($ok)-1]==$nama_file) echo "<option selected>";else echo "<option>";
				echo $ok[sizeof($ok)-1]."</option>";
			}
		echo "
			</select>      
			</div>
		</form>
		";

		//DISINI BATAS PEKERJAAN KITA LANG. FUNGSI AJAX DI BAWAH INI BELUM SELESAI. 
		echo "
				<script>
					function ok(sel) {
						var loading = $(\"#pra_baca_surat_new\");
						var tampilkan = $(\"#penampil_baca_surat_new\");
						var loading1 = $(\"#pra_baca_surat_new1\");
						var tampilkan1 = $(\"#penampil_baca_surat_new1\");
						var nilai=sel.value;
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/buka_file_surat_pendukung_agenda_select/$list_direktori")."',{ data:nilai},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
							loading1.fadeOut();";
							if(in_array($tipe_file[sizeof($tipe_file)-1],array('pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
				echo "		
						});
					}
				</script>
			";
		echo "<iframe name='iframe_editor_note' src=\"".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."\" width='100%' height='500px' frameborder='0'></iframe>";
	}

	public function tesbro($list_direktori=NULL,$nama_file=NULL){
		echo "OK INIMI BRO<br>";
		echo "NILAI list_direktori: ".$list_direktori;
		echo "<br>NILAI nama_file: ".$_POST['data'];
	}

	public function buka_file_surat_pendukung_agenda_select($list_direktori=NULL){
		$nama_file=$_POST['data'];
		$list_direktori_dekrip=explode(';',$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($list_direktori)));
		//$direktori_surat_terpilih=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($direktori_surat));
		//$ok1=explode('/',$direktori_surat_terpilih);
		//$nama_file=$ok1[sizeof($ok1)-1];
		$tipe_file=explode('.',$nama_file);
		print_r($tipe_file);
		echo "
		<form>
			<div class='form-group' align=left>
			<label for='sel1'>Pilih file berikut untuk ditampilkan atau diunduh:</label>
			<select class='form-control' id='sel1' onchange='ok(this);'>";
			foreach($list_direktori_dekrip as $key=>$isi){
				$ok=explode('/',$isi);
				if($ok[sizeof($ok)-1]==$nama_file) {
					echo "<option selected>";
					$direktori_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($isi));
				}else echo "<option>";
				echo $ok[sizeof($ok)-1]."</option>";
			}
		echo "
			</select>      
			</div>
		</form>
		";

		//DISINI BATAS PEKERJAAN KITA LANG. FUNGSI AJAX DI BAWAH INI BELUM SELESAI. 
		echo "
				<script>
					function ok(sel) {
						var loading = $(\"#pra_baca_surat_new\");
						var tampilkan = $(\"#penampil_baca_surat_new\");
						var loading1 = $(\"#pra_baca_surat_new1\");
						var tampilkan1 = $(\"#penampil_baca_surat_new1\");
						var nilai=sel.value;
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/buka_file_surat_pendukung_agenda_select/$list_direktori")."',{ data:nilai},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
							loading1.fadeOut();";
							if(in_array($tipe_file[sizeof($tipe_file)-1],array('pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
				echo "		
						});
					}
				</script>
			";
		echo "<iframe name='iframe_editor_note' src=\"".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."\" width='100%' height='500px' frameborder='0'></iframe>";
	}

	public function hapus_data_cruid_agenda(){
		$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
		$kolom=$json->nama_kolom_id;
		echo "<h6><span style=\"color:red;\"><i class='fas fa-exclamation fa-lg text-white-100'></i></span> Apakah anda benar-benar ingin menghapus data?</h6>";
		echo "
			<form action=\"".site_url('Frontoffice/hapus_data_new_agenda/ok_new')."\" method='post'>
			<input type='hidden' name='id_hapus' id='id_hapus' value=".$json->$kolom.">
			<input type='hidden' name='nama_tabel' id='nama_tabel' value=".$json->nama_tabel.">
			<button type=\"submit\" class=\"btn btn-danger\" style=\"width:100%;\" id=\"tombol_hapus\"><i class='fas fa-trash text-white-100'></i> Hapus</button>
			</form> 
		";
	}

	public function terima_surat_masukx()
	{
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');

		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/surat_dan_berkas_masuk/';	
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						//}else if($key=='posisi_surat_terakhir'){
						//	array_push($kiriman,"Sekretariat BKD");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman[12]=$upload1[0];
					$kiriman[13]=$upload2[0];
					if($kiriman[12]) {$kiriman[14]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[14]=NULL;}
					if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[15]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[29]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_masuk';
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
					//print_r($kiriman);
					//Persiapan notifikasi
					
					if($hasil_insersi_surat_berkas){
						$counter_table='tbcounter_notifikasi';
						$kolom_rujukan['nama_kolom']='idcounter_notifikasi';
						$kolom_rujukan['nilai']=1;//untuk nama_counter: counter surat masuk
						$kolom_target='nilai_counter';
						$this->model_frommyframework->naikkan_counter_notifikasi($counter_table,$kolom_rujukan,$kolom_target);
						/*
						//baca counter terakhir
						$nilai_counter_terakhir=array();
						$nilai_counter_terakhir=$this->model_frommyframework->pembaca_nilai_kolom_tertentu($counter_table,$kolom_rujukan,$kolom_target);
						$nilai_counter_terakhir_berikut=$nilai_counter_terakhir[0]+1;
						//alert("NILAI COUNTER TERAKHIR: ".implode('  '.$nilai_counter_terakhir));

						//masukkan nilai counter berikut
						$data[$kolom_target]=$nilai_counter_terakhir_berikut;
						//alert("NILAI COUNTER TERAKHIR BERIKUT: ".$nilai_counter_terakhir_berikut);
						$this->model_frommyframework->update_style_CI_no_alert($counter_table,$kolom_rujukan,$data);
						*/
					}
	
				}
	
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Sekretariat BKD Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			}

		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
		*/
	
	}

	public function edit_cruid_agenda(){ 
		$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
		//print_r($json);
		$kolom=$json->nama_kolom_id;
		$surat=$this->user_defined_query_controller_as_array($query="select * from ".$json->nama_tabel." where ".$json->nama_kolom_id."=".$json->$kolom,$token="andisinra");
		//echo "INI PASSWORD: ".$surat[0]['password'];
		if(!$surat){
			alert('Data yang dimaksud tidak tercatat');
		}else{
			$judul="<span style=\"font-size:20px;font-weight:bold;\">EDIT DATA</span>";
			$tabel=$json->nama_tabel;
			$coba=array();
			$id=$json->nama_kolom_id;
			$aksi='tambah';
			if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
			//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
			$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
			//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected
			$j=0;
			foreach($surat[0] as $key=>$unit){
				is_string($key)?$surat_keyNo_isiString_buffer[$j]=$key:NULL;
				$j++;
			}
			$j=0;
			foreach($surat_keyNo_isiString_buffer as $key=>$unit){
				$surat_keyNo_isiString[$j]=$unit;
				$j++;
			}

			//reset form sebelum dibuka:
			//print_r($surat_keyNo_isiString);

			foreach($coba as $key=>$k){
				$coba[$key][7]=$surat[0][$key];
				//$coba[$key][7]=$surat_keyNo_isiString[$key];
				$surat_keyNo_isiString[$key]=='password'?$coba[$key][4]=' readonly ':NULL;
			}

			/*
			$coba[6][0]='combo_database';
			$coba[6][8]=$coba[6][7];
			$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
			
			$coba[7][0]='combo_database';
			$coba[7][8]=$coba[7][7];
			$coba[7][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
			

			$coba[17][0]='area';
			$coba[18][7]='dibaca';
			$coba[20][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
			*/

			$komponen=$coba;
			$atribut_form='';
			$array_option='';
			$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
			//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
			/*
			$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[14][7]));
			$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
			*/
			//$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
			//$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
			$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Perbaharui data','');
			//$tombol[3]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Unggah Surat Balasan...','Unggah Surat Balasan',"Frontoffice/frontoffice_unggahberkas_surat_masuk");
			
			//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
			//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
			//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
			$value_selected_combo='';
			$target_action="Frontoffice/update_data_cruid_new_agenda/".$tabel."/ok_new";//general_update_controller($kiriman,$tabel)
			$submenu='submenu';
			$aksi='tambah';
			$perekam_id_untuk_button_ajax='';
			$class='form-control';
			//$this->session->set_userdata('modal','ok_new');
			//$this->session->set_userdata('tabel','tbagenda_kerja');
			//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
			$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
			
			//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
		}
	}
	public function hapus_data_new_agenda($modal=TRUE){
		
		//$this->load->view('admin_frontoffice/dashboard');
		//cccc
		if(isset($_POST['nama_tabel'])){
			$fields = $this->db->list_fields($_POST['nama_tabel']);
			foreach ($fields as $field){
				if($field=='direktori_surat_masuk' || $field=='direktori_berkas_yg_menyertai' || preg_grep("#direktori#i",array($field))){
					//baca dulu 
					$this->db->select($field);
					$this->db->from($_POST['nama_tabel']);
					$this->db->where($fields[0], $_POST['id_hapus']);
					$query = $this->db->get();//pppp
					//print_r($direktori_hapus);
					//print_r($query->result());
					foreach($query->result() as $row){
						$direktori_hapus=$row->$field;
					}
					//echo $direktori_hapus."<br>";
					$direktori_hapus_item=explode(';',$direktori_hapus);
					//print_r($direktori_hapus_item);
					//echo "<br><br>";
					
					foreach($direktori_hapus_item as $key=>$isi){
						$nama_file_array=explode('/',$isi);
						$nama_file=$nama_file_array[sizeof($nama_file_array)-1];
						//print_r($nama_file_array);
						//echo "<br>";
						//echo $nama_file;
						//echo "<br>";
						//echo $isi;
						//echo "<br><br>";
						if($isi!==''){
							try {
								if(@unlink($isi)==TRUE){
									alert("File $nama_file yang terkait rekord juga sukses terhapus");
								}else{
									throw new Exception("File $nama_file yang terkait rekord tidak dapat dihapus, mungkin file yang bersangkutan tidak dalam direktori yang tercatat di rekord, atau file sedang terbuka");
									//alert("File yang terkait rekord tidak dapat dihapus, mungkin file yang bersangkutan tidak dalam direktori yang tercatat di rekord, atau file sedang terbuka");
								}
							}
							catch (Exception $e) {
								alert($e->getMessage()); // will print Exception message defined above.
							} 
						}
						
					}
					
					
				}
			}
			$this->hapus_rekord($_POST['nama_tabel'],$_POST['id_hapus']);
			$this->session->set_userdata('modal',$modal);
			$this->session->set_userdata('tabel',$_POST['nama_tabel']);;
			$this->load->view('admin_frontoffice/dashboard');
			//	redirect(site_url('Frontoffice/frontoffice_admin'));
		}else{
			$this->load->view('admin_frontoffice/dashboard');
		}
		
	}

	public function update_data_cruid_new_agenda($table=NULL,$modal=TRUE){
		//$user = $this->session->userdata('user_ruangkaban');
        //$str = $user['email'].$user['username']."1@@@@@!andisinra";
        //$str = hash("sha256", $str );
		//$hash=$this->session->userdata('hash');
		//if(($user!==FALSE)&&($str==$hash)){
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
				$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');
				//print_r($data_post);

				$kiriman=array();
					foreach($data_post as $key=>$k){
							//if($key=='password'){
							//	array_push($kiriman,password_hash($k['nilai'], PASSWORD_BCRYPT));
							//else{
								array_push($kiriman,$k['nilai']);
							//} //xx1
						}

					//print_r($kiriman);
					//print_r($data_post);
					//$tabel='surat_masuk';
					$this->general_update_controller($kiriman,$table);
					//$this->general_insertion_controller($kiriman,$table);
					//if($hasil_insersi_surat_berkas){alert('Perubahan data sukses');}else{alert('Perubahan data gagal');}
					$this->session->set_userdata('modal',$modal);
					$this->session->set_userdata('tabel',$table);;
					$this->load->view('admin_frontoffice/dashboard');
			} else {
				!$table?alert('Nama Tabel yang hendak dirubah tidak ada'):NULL;//alert('Data berhasil ditambahkan');				
				$this->load->view('admin_frontoffice/dashboard');
			}
		//}else{
		//	alert('Maaf Session anda kadaluarsa');
		//	redirect('Frontoffice/index');
		//}
	}
	//===========================================END FUNGSI AGENDA NEW======================================================================

	//======================================FUNGSI UNTUK EDITOR NOTE BUKAN DOKUMEN=========================================================
	public function iframe_editor_note(){
		echo "<iframe name='iframe_editor_note' src=\"".site_url('Frontoffice/buat_surat_baru_tinymce_note')."\" width='100%' height='600px' frameborder='0'></iframe>";
	}
	public function buat_surat_baru_tinymce_note(){
		echo "
		<link href=\"".base_url('/dashboard/vendor/fontawesome-free/css/all.min.css')."\" rel=\"stylesheet\" type=\"text/css\">
  		<link href=\"https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\">
		<link href=\"".base_url('/dashboard/css/sb-admin-2.min.css')."\" rel=\"stylesheet\">
		<script src=\"".base_url('/public/vendor3.4.1/jquery/3.4.1/jquery.min.js')."\"></script>
		<script src=\"".base_url('/public/vendor3.4.1/bootstrap/3.4.1/js/bootstrap.min.js')."\"></script>
		<!-- Bootstrap core JavaScript-->
		<script src=\"".base_url('/dashboard/vendor/jquery/jquery.min.js')."\"></script>
		<script src=\"".base_url('/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js')."\"></script>
		<!-- Core plugin JavaScript-->
		<script src=\"".base_url('/dashboard/vendor/jquery-easing/jquery.easing.min.js')."\"></script>
		<!-- Custom scripts for all pages-->
		<script src=\"".base_url('/dashboard/js/sb-admin-2.min.js')."\"></script>
		<!-- Page level plugins -->
		<script src=\"".base_url('/dashboard/vendor/chart.js/Chart.min.js')."\"></script>
		<!-- Page level custom scripts -->
		<script src=\"".base_url('/dashboard/js/demo/chart-area-demo.js')."\"></script>
		<script src=\"".base_url('/dashboard/js/demo/chart-pie-demo.js')."\"></script>
		";
		echo "
		<script src=\"".base_url('/public/tinymce/js/tinymce/tinymce.min.js')."\"></script>
		<script src=\"".base_url('/public/tinymce/js/tinymce/jquery.tinymce.min.js')."\"></script>
		";
		echo "
			<script type='text/javascript'>
			/* 
				tinymce.init({
					selector: '#mytextarea',
					plugins: 'table',
					menubar: 'table', 
					toolbar: \"insertdatetime table bold italic\"
				  });
				  */
				
				/*tinymce.init({ selector:'#mytextarea',plugins: 'table', theme: 'modern'});*/

				tinymce.init({
					selector: \"#mytextarea\",  // change this value according to your HTML
					base_url: '/public/tinymce/js/tinymce',
					plugins : 'insertdatetime table visualblocks advlist autolink link image lists charmap print preview anchor autoresize autosave bbcode code codesample colorpicker contextmenu directionality emoticons example fullpage fullscreen hr imagetools importcss layer legacyoutput media nonbreaking noneditable pagebreak paste save searchreplace spellchecker tabfocus template textcolor textpattern toc visualchars wordcount ',
					menubar: \"favs file edit view format insert tools table help\",
					//contextmenu: \"link image imagetools table spellchecker\",
					draggable_modal: true,
					mobile: {
						plugins: [ 'autosave', 'lists', 'autolink' ],
						toolbar: [ 'undo', 'bold', 'italic', 'styleselect' ]
					  },
					toolbar1: 'undo redo | fontsizes formats insertfile styleselect fontselect fontsizeselect| bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent ',
					toolbar2: \"visualblocks insertdatetime table advlist autolink link image lists charmap print preview anchor autoresize bbcode code codesample forecolor backcolor contextmenu directionality emoticons\",
					toolbar3: \"example fullpage fullscreen hr imagetools importcss layer legacyoutput media nonbreaking noneditable pagebreak paste searchreplace spellchecker tabfocus template textcolor textpattern toc visualchars wordcount\",
					menu: {
						file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
						edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
						view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
						insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
						format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' },
						tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
						table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
						help: { title: 'Help', items: 'help' },
						favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | emoticons'}
					  }

				});
				
				
				  
			</script>
		";
		//target=\"target_buat_surat_baru\" 
		echo "
			<div >
			<form target=\"target_buat_surat_baru\"  method='post' action=\"".site_url('Frontoffice/terima_hasil_ketikan_surat')."\">
			<textarea id='mytextarea' name='mytextarea' style=\"width:100%; height:60%\"></textarea>";
		
			echo "
			<!-- Modal Simpan dan Buka File -->
			<div class='modal fade' id='modal_nama_file' role='dialog''>
				<div class='modal-dialog'>
				<!-- Modal content-->
				<div class='modal-content'>
					<div class='modal-header'>
					<h7 class='modal-title'>BKD Provinsi Sulawesi Selatan</h7>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_buka_simpan' style='width:65%;' align='center' >
					<label for=nama_file>Nama file simpan:</label>
					<input type=text id='nama_file' class=\"form-control\" name='nama_file' placeholder='nama file...'>
					<input type=text id='direktori_file_simpan' class=\"form-control\" name='direktori_file_simpan' placeholder='masukkan direktori file (opsional)...'>
					<button type='submit' name='simpan' class=\"btn btn-sm btn-success shadow-sm\" id=\"simpan_file\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-save fa-sm text-white-100\"></i> Simpan</button>
					<button type='submit' id=\"export2word\" name='export2word' formaction=\"".site_url('Frontoffice/export2word_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke Word</button>
					<button type='submit' id=\"export2pdf\" name='export2pdf' formaction=\"".site_url('Frontoffice/export2pdf_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke PDF</button>
					<button type='submit' id=\"export2excel\" name='export2excel' formaction=\"".site_url('Frontoffice/export2excel_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke Excel</button>
					<button type='submit' id=\"export2html\" name='export2html' formaction=\"".site_url('Frontoffice/export2html_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Simpan ke HTML</button>
					<button type='submit' id=\"export2pp\" name='export2pp' formaction=\"".site_url('Frontoffice/export2pp_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke PowerPoint</button>
					</div>
					</center>
					</div>
					<div class='modal-footer'>
					<!--<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>-->
					</div>
				</div>
				</div>
			</div>
		";

		echo "
			</form>
			</div>
		";

		echo "
			<div >
			<form target=\"target_buat_surat_baru\" method='post' action=\"".site_url('Frontoffice/buka_surat')."\">";
			echo "
			<!-- Modal Simpan dan Buka File -->
			<div class='modal fade' id='modal_buka_file' role='dialog''>
				<div class='modal-dialog'>
				<!-- Modal content-->
				<div class='modal-content'>
					<div class='modal-header'>
					<h7 class='modal-title'>BKD Provinsi Sulawesi Selatan</h7>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_buka_file' style='width:65%;' align='center' >
					<label for=nama_file_buka>Nama file buka:</label>
					<input type=text id='nama_file_buka' class=\"form-control\" name='nama_file_buka' placeholder='nama file...'>
					<input type=text id='direktori_file' class=\"form-control\" name='direktori_file' placeholder='masukkan direktori file (opsional)...'>
					<button type='button' name='buka_file' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"buka_file\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-folder-open fa-sm text-white-100\"></i> Buka</button>
					</div>
					</center>
					</div>
					<div class='modal-footer'>
					<!--<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>-->
					</div>
				</div>
				</div>
			</div>
		";

		echo "
			<div>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='simpan_file1' class=\"d-sm-inline-block btn btn-sm btn-primary shadow-sm\" id=\"simpan_file1\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-save fa-sm text-white-100\"></i> Simpan</button>
				<button type=button data-toggle=\"modal\" data-target=\"#modal_buka_file\" name='buka_file1' class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm\" id=\"buka_file1\"  style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-folder-open fa-sm text-white-100\"></i> Buka</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttopdf' class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm\" id=\"exporttopdf\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-pdf fa-sm text-white-100\"></i> Export PDF</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttohtml' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"exporttohtml\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-html fa-sm text-white-100\"></i> Simpan HTML</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttoword' class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm\" id=\"exporttoword\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-word fa-sm text-white-100\"></i> Export Word</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttoexcel' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"exporttoexcel\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-excel fa-sm text-white-100\"></i> Export Excel</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttopp' class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm\" id=\"exporttopp\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-powerpoint fa-sm text-white-100\"></i> Ex PowerPoint</button>
				<!--<input style=\"float:right\" type=text class='form-control' name='nama_file'><label for=nama_file style=\"float:right;\">Masukkan nama file: </label>-->
			</div>
		";

		echo "
			<style>
				#simpan_file{
					display:none;
				}
				#export2word{
					display:none;
				}
				#export2pdf{
					display:none;
				}
				#export2excel{
					display:none;
				}
				#export2html{
					display:none;
				}
				#export2pp{
					display:none;
				}
				#direktori_file_simpan{
					display:block;
				}
			</style>
			<script>
			$(document).ready(function(){
                $(\"#simpan_file1\").click(function(){
					$('#simpan_file').show();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').show();
				});
				$(\"#exporttoword\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').show();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
                $(\"#exporttopdf\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').show();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
				$(\"#exporttoexcel\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').show();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
                $(\"#exporttohtml\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').show();
					$('#export2pp').hide();
					$('#direktori_file_simpan').show();
				});
				$(\"#exporttopp\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').show();
					$('#direktori_file_simpan').hide();
				});
				
				});
			</script>
		";
		echo "<iframe name='target_buat_surat_baru' width='0' height='0' frameborder='0'></iframe>";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#buka_file\").click(function(){
				  var tampilkan = $(\"#mytextarea\");
				  var nama_file = $(\"#nama_file_buka\").val();
				  var direktori_file = $(\"#direktori_file\").val();
                  $.post('".site_url("/Frontoffice/buka_surat")."',{ nama_file_buka:nama_file, direktori_file:direktori_file},
                  function(data,status){
					tinymce.activeEditor.setContent(data);

                  });
                });
				});
			</script>
        ";
		
	}
	//===========================================END FUNGSI UNTUK EDITOR NOTE BUKAN DOKUMEN=================================================
	
	//===============================================UNTUK NEW CRUID========================================================================
	public function tampilkan_tabel_surat_keluar_new(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>2),$kolom_target='nilai_counter');
		$table='surat_keluar';
		$nama_kolom_id='idsurat_keluar';
		$this->tampil_tabel_cruid_new($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL);
	}	

	public function tampilkan_tabel_surat_balasan_new(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>4),$kolom_target='nilai_counter');
		$table='surat_balasan_tamupegawai';
		$nama_kolom_id='idsurat_balasan';
		$this->tampil_tabel_cruid_new($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL);
		
	}

	public function tampilkan_tabel_surat_terusan_new(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>3),$kolom_target='nilai_counter');
		$table='surat_terusan';
		$nama_kolom_id='idsurat_terusan';
		$this->tampil_tabel_cruid_new($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL);
		//$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_terusan($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_terusan order by idsurat_terusan desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}	
	
	public function tampilkan_tabel_new(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter');
		$table='surat_masuk';
		$nama_kolom_id='idsurat_masuk';
		$this->tampil_tabel_cruid_new($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL);
		//$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_masuk_frontoffice_surat_masuk($kolom_cari,$nama_kolom_direktori_surat,$array_atribut=array(""," class=\"table table-striped\"",""),$query='select * from surat_masuk order by idsurat_masuk desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}	

	public function tampil_tabel_cruid_new($table='surat_masuk',$nama_kolom_id='idsurat_masuk',$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL){
		//echo "INI NILAI LIMIT: ".$limit;
		$kolom_cari_new=array('perihal_surat','nomor_surat_masuk','ditujukan_ke','pengirim');
		$nama_kolom_direktori_surat=array('surat'=>'direktori_surat_masuk','berkas'=>'direktori_berkas_yg_menyertai');
		$this->tampil_tabel_cruid_new_core($table,$nama_kolom_id,$order,$limit,$currentpage,$page_awal,$jumlah_page_tampil,$mode,$kolom_cari,$nilai_kolom_cari,$kolom_cari_new,$nama_kolom_direktori_surat);
	}
	
	public function tampil_tabel_cruid_new_core($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL,$kolom_cari_new,$nama_kolom_direktori_surat){
		//echo "INI NILAI LIMIT DALAM: ".$limit;
		$awal=($currentpage-1)*$limit;
		$numrekord=$this->db->count_all($table);
		$jumlah_halaman=ceil($numrekord/$limit);

		//echo "<br>INI JUMLAH HALAMAN: ".$jumlah_halaman;
		//echo "<br>INI mode: ".$mode;
		//echo "<br>INI kolom_cari: ".$kolom_cari;
		//echo "<br>INI nilai_kolom_cari: ".$nilai_kolom_cari;

		echo "<div align=left>".ucwords(implode(' ',explode('_',$table)))." >> Halaman ".$currentpage."</div>";
		echo "<h4 id=\"h4_atas\"><i class=\"fas fa-envelope fa-lg text-white-100\"></i> ".ucwords(implode(' ',explode('_',$table)))."</h4>";
		
		echo "<hr><div align=right>";
		echo "<h4 id=\"h4_bawah\" style=\"position:absolute; left:11px;\"><i class=\"fas fa-envelope fa-lg text-white-100\"></i> ".ucwords(implode(' ',explode('_',$table)))."</h4>";
		echo "<button id=\"pencarian_lanjut_atas\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#searchmodal\">Pencarian Lanjut</button>";
		echo "</div><hr>";
		
		echo "
			<style>
				#myInput1{
					width:30%;
				}
				#h4_atas{
					display:none;
				}
				#h4_bawah{
					display:block;
				}
				#quantity{
					margin-left:5px;
					width:70px;
				}
				#tampilbaris{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#myInput1{
						width:100%;
					}
					#h4_atas{
						display:block;
						margin-top:20px;
					}
					#h4_bawah{
						display:none;
					}
					#quantity{
						margin-left:0px;
						width:40%;
					}
					#tampilbaris{
						margin-left:0px;
						width:59%;
					}
				  }
			</style>
			<script>
				$(document).ready(function(){
				$(\"#myInput1\").on(\"keyup\", function() {
					var value = $(this).val().toLowerCase();
					$(\"#myTable1 tr\").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
				});
			</script>
				<div align=left> 
				<label for=\"quantity\" style=\"float:left;line-height:2.2;\">Tampilkan jumlah maksimal surat: </label>
				<input type=\"number\" class=\"form-control\" id=\"quantity\" name=\"quantity\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-info\" id=\"tampilbaris\" style=\"height:35px;\">Tampilkan</button>
				<input type=\"text\" class=\"form-control\" id=\"myInput1\" style=\"float:right;height:35px;min-width:100px;\" placeholder=\"Filter...\">
				</div>
		";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tampilbaris\").click(function(){
                  var loading = $(\"#pra_tabel\");
				  var tampilkan = $(\"#penampil_tabel\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tampil_tabel_cruid_new/".$table."/".$nama_kolom_id."/desc/")."'+limit,{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
		";

		$mode==NULL?$query=$this->sanitasi_controller("select * from $table order by $nama_kolom_id $order limit $awal,$limit"):$query=$this->sanitasi_controller("select * from $table where $kolom_cari LIKE ")."'%".$this->sanitasi_controller($nilai_kolom_cari)."%'".$this->sanitasi_controller(" order by $nama_kolom_id $order limit 0,$limit");
		//echo "<br>INI query: ".$query;
		//$query=$this->sanitasi_controller($query);
		//echo "<br> INI sehabis disanitasi: ".$query;
		//$this->penampil_tabel_no_foto_controller($table,$nama_kolom_id,$array_atribut=array("","id=\"myTable\" class=\"table table-condensed table-hover table-striped\"",""),$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		//$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_masuk_frontoffice_surat_masuk ($kolom_cari,$nama_kolom_direktori_surat,$array_atribut,$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_masuk_frontoffice_surat_masuk($kolom_cari_new,$nama_kolom_direktori_surat,$array_atribut=array("","id=\"myTable1\" class=\"table table-striped\"",""),$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		echo "
			<style>
				#blokpage{
					display:flex; justify-content:center;
				}
				@media screen and (max-width: 480px) {
					#blokpage{
						justify-content:left;
					}
				}
			</style>
			<div id=\"blokpage\">
			<nav aria-label='...'>
			<ul class='pagination'>";

			//Siapkan nomor-nomor page yang mau ditampilkan
			$array_page=NULL;
			$j=0;
			for($i=$page_awal;$i<=($page_awal+($jumlah_page_tampil-1));$i++){
				$array_page[$j]=$i;
				if($limit*$i>$numrekord)break;
				$j++;
			}
			//print_r($array_page);;
				
			if($currentpage<=$jumlah_page_tampil){
				echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
			}else{
				echo "<li class='page-item' id='Previous'><a class='page-link' href='#'>Previous</a></li>";
				$current_pagePrevious=$array_page[0]-1;
				$page_awalPrevious=$current_pagePrevious-($jumlah_page_tampil-1);
				echo "
						<script>
						$(document).ready(function(){
							$(\"#Previous\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							var limit=$(\"#quantity\").val();
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url("/Frontoffice/tampil_tabel_cruid_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_pagePrevious+'/'+$page_awalPrevious+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
							function(data,status){
								loading.fadeOut();
								tampilkan.html(data);
								tampilkan.fadeIn(2000);
							});
							});
							});
						</script>
				";
			}

			
			//echo "<br>INI current_page: ".$currentpage;
			//echo "<br>INI page_awal: ".$page_awal;

			//Tampilkan nomor-nomor halaman di paging
			for($i=$array_page[0];$i<=$array_page[sizeof($array_page)-1];$i++){
				if($currentpage==$i){
					//echo "<br>INI DALAM currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item active' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";				
				}else{
					//echo "<br>INI LUAR currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";
				}
				//if($i==$jumlah_page_tampil){break;}
			}
		
		//echo "<br>INI jumlah_halaman: ".$jumlah_halaman;
		//echo "<br>INI jumlah_page_tampil: ".$jumlah_page_tampil;
		//echo "<br>INI currentpage: ".$currentpage;
		//echo "<br>INI TOTAL HITUNG: ".($array_page[0]+$jumlah_page_tampil-1);
		//if($jumlah_halaman>$jumlah_page_tampil && !($currentpage==$jumlah_halaman)){

		//Kode untuk tombol Next:
		if(($array_page[0]+$jumlah_page_tampil-1)<$jumlah_halaman){
			echo "<li class='page-item' id=\"Next\"><a class='page-link' href='#'>Next</a></li>";
			$current_page=$array_page[sizeof($array_page)-1]+1;
			$page_awal=$current_page;
			echo "
					<script>
					$(document).ready(function(){
						$(\"#Next\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_page+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
			";
		}
		else{
			echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
		}

		echo "
			<li class='page-item disabled'><a class='page-link' href='#'>$jumlah_halaman page</a></li>
			<li class='page-item disabled'><a class='page-link' href='#'>$numrekord rekord</a></li>
			</ul>
			</nav>
			</div>
		";

		//go to page:
		echo "
			<style>
				#gotopage{
					margin-left:5px;
					width:70px;
				}
				#go{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#pencarianlanjut{
						width:100%;
					}
					#gotopage{
						margin-left:0px;
						width:40%;
					}
					#go{
						margin-left:3px;
					}
				}
			</style>
				<div align=left>
				<div style=\"float:left;\">
				<label for=\"gotopage\" style=\"float:left;line-height:2.2;\">Page: </label>
				<input type=\"number\" class=\"form-control\" id=\"gotopage\" name=\"gotopage\" min=\"1\" value=\"".$currentpage."\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-primary\" id=\"go\" style=\"height:35px;width:40px;\">go</button>
				</div>
				<button class=\"btn btn-xs btn-primary\" id=\"pencarianlanjut\" data-toggle=\"modal\" data-target=\"#searchmodal\" style=\"height:35px;float:right;\">Pencarian Lanjut</button>
				</div>
			";

			//Kode untuk id=gotopage dan id=go 
			echo "
					<script>
					$(document).ready(function(){
						$(\"#go\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";
			
			//Modal untuk pencarian lanjut:
			$fields = $this->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			echo "
				<!-- Modal Searching-->
				<div class=\"modal fade\" id=\"searchmodal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
					<div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
						<div class=\"modal-header\">
						<h5 class=\"modal-title\" id=\"exampleModalLabel\">Mode Pencarian Lanjut</h5>
						<button class=\"close\" type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\">
							<span aria-hidden=\"true\">×</span>
						</button>
						</div>
						<div class=\"modal-body\" style=\"display:flex; justify-content:center;flex-wrap: wrap;\">
						
						<input class=\"form-control\" type=\"text\" id=\"nilai_kolom_cari\" placeholder=\"Search...\"> 
						<button class=\"btn btn-xs\" disabled>Berdasarkan</button> 
						<select class=\"form-control\" id=\"kolom_cari\" name=\"kolom_cari\">";
						echo "<option value=".$fields[0].">Pilih nama kolom tabel</option>";
						foreach ($fields as $field){
							echo "<option value=\"$field\">".ucwords(implode(' ',explode('_',$field)))."</option>";
						}
						echo "
						</select>
						</div>
						<hr>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<label for=\"limicari\" style=\"float:left;line-height:2.2;\">Jumlah maksimal rekord: </label>
							<input type=\"number\" class=\"form-control\" id=\"limicari\" name=\"limicari\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;width:75px;\">
						</div>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<button class=\"btn btn-xs btn-danger\" id=\"lakukanpencarian\" data-dismiss=\"modal\">Lakukan pencarian</button>
						</div>
						<div class=\"modal-footer\">
						<button class=\"btn btn-secondary\" type=\"button\" data-dismiss=\"modal\">Cancel</button>
						</div>
					</div>
					</div>
				</div>
			";

			//Kode untuk id=lakukanpencarian
			echo "
					<script>
					$(document).ready(function(){
						$(\"#lakukanpencarian\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#limicari\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						var kolom_cari=$(\"#kolom_cari\").val();
						var nilai_kolom_cari=$(\"#nilai_kolom_cari\").val();

						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_new/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil+'/TRUE/'+kolom_cari+'/'+nilai_kolom_cari,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";

	}

    //===============================================END UNTUK NEW CRUID========================================================================

	//==============FUNGSI-FUNGSI UNTUK MENAMPILKAN AGENDA====================================================
	public function baca_agenda($table='tbagenda_kerja',$mulai_rekord=0,$jumlah_rekord=20,$order='desc'){

		echo "
			<h5>Agenda Hari Ini</h5>           
			<table class='table table-hover table-striped'>
			<thead>
				<tr>
				<th>id</th>
				<th>Acara</th>
				<th>Tempat</th>
				<th>Tanggal</th>
				<th>Urgensi</th>
				<th>Rincian</th>
				</tr>
			</thead>
			<tbody>";
			$fields=$this->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			$query=$this->model_frommyframework->query_dengan_limit($table,$mulai_rekord,$jumlah_rekord,$fields[0],$order);
			foreach ($query->result() as $row)
			{
					echo "
					<tr>
					<td>".$row->idagenda_kerja."</td>
					<td>".$row->acara_kegiatan."</td>
					<td>".$row->tempat."</td>
					<td>".$row->tanggal."</td>
					<td>".$row->urgensi."</td>
					<td><button class=\"d-sm-inline-block btn btn-lg btn-success shadow-sm kotak\" id=\"rincian_agenda$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-100'></i> Rincian</button></td>
					</tr>
					<tr id='tr$row->idagenda_kerja'>
					<td><i class='fas fa-eye fa-sm text-white-100'></i></td>
					<td colspan=4>
					Rincian:<br>
					Sampai Tanggal: $row->sampai_tanggal<br>
					Lama Kegiatan: $row->lama_kegiatan<br>
					Status Kegiatan: $row->status_kegiatan<br>
					Urgensi Acara: $row->urgensi<br>
					Dasar Surat: $row->nama_file_surat_pendukung<br>
					Direktori Penyimpanan Surat: $row->direktori_surat_pendukung<br>
					Nama File Foto: $row->nama_file_foto<br>
					Direktori Penyimpanan Foto: $row->direktori_foto_yg_menyertai<br>
					Admin: $row->admin
					</td>
					<td><button class=\"d-sm-inline-block btn btn-lg btn-warning shadow-sm kotak\" id=\"tutup_rincian$row->idagenda_kerja\">Tutup</button></td>
					</tr>
					

					<style>
						#tr$row->idagenda_kerja{
							display:none;
						}
					</style>
					<script>
					$(document).ready(function(){
						$(\"#rincian_agenda$row->idagenda_kerja\").click(function(){
							$('#tr$row->idagenda_kerja').fadeIn();
						});
						$(\"#tutup_rincian$row->idagenda_kerja\").click(function(){
							$('#tr$row->idagenda_kerja').fadeOut();
						});
						});
					</script>";
			}
			echo "
			</tbody>
			</table>
		";
	}

	//==============END FUNGSI-FUNGSI AGENDA==================================================================

	//==============FUNGSI-FUNGSI UNTUK BACA COUNTER SURAT MASUK==============================================
	public function notifikasi_surat_total(){
		echo "
				<!-- Nav Item - Messages -->
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_masuk1\">
						  <div class=\"dropdown-list-image mr-3\">
							<i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#2C9FAF\"></i>
							<div class=\"status-indicator bg-success\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox surat masuk
							<span id=\"counter_surat_masuk_masuk1\" class=\"badge badge-danger badge-counter\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_kecil1\"></span></div>
						  </div>
						</a>
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_masuk1\");
							var tampilan_kecil = $(\"#surat_masuk_kecil\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_masuk/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_masuk1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
		
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_terusan1\">
						  <div class=\"dropdown-list-image mr-3\">
							<i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#17A673\"></i>
							<div class=\"status-indicator\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox surat terusan
							<span id=\"counter_surat_masuk_terusan1\" class=\"badge badge-danger badge-counter\" style=\"margin-top:-15px;\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_terusan1\"></span></div>
						  </div>
						</a>
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_terusan1\");
							var tampilan_kecil = $(\"#surat_masuk_terusan1\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_terusan/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_terusan1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel_surat_terusan_new')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
		
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_balasan1\">
						  <div class=\"dropdown-list-image mr-3\">
						  <i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#F4B619\"></i>
							<div class=\"status-indicator bg-warning\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox surat balasan
							<span id=\"counter_surat_masuk_balasan1\" class=\"badge badge-danger badge-counter\" style=\"margin-top:-15px;\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_balasan1\"></span></div>
						  </div>
						</a>
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_balasan1\");
							var tampilan_kecil = $(\"#surat_masuk_balasan1\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_balasan/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_balasan1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel_surat_balasan_new')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
						<!--
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_arsip1\">
						  <div class=\"dropdown-list-image mr-3\">
							<i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#2653D4\"></i>
							<div class=\"status-indicator bg-info\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox arsip surat
							<span id=\"counter_surat_masuk_arsip1\" class=\"badge badge-danger badge-counter\" style=\"margin-top:-15px;\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_arsip1\"></span></div>
						  </div>
						</a>
						-->
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_arsip1\");
							var tampilan_kecil = $(\"#surat_masuk_arsip1\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_arsip/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_arsip1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel_surat_keluar_new')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
		
		";
	}

	public function baca_counter_surat_controller($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter'){
		return $this->model_frommyframework->pembaca_nilai_kolom_tertentu($counter_table,$kolom_rujukan,$kolom_target)[0];
	}

	public function baca_counter_surat_total($mode='fungsi'){
		$counter_surat_total=array();
		$counter_table='tbcounter_notifikasi';
		$kolom_target='nilai_counter';

		for($i=1;$i<5;$i++){
			$counter_surat_total[$i]=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>$i),$kolom_target);
		}
		if($mode=='fungsi'){
			return array_sum($counter_surat_total);
		}else {
			if(array_sum($counter_surat_total)==0)NULL;else echo array_sum($counter_surat_total);
		}
	}

	public function baca_counter_surat_masuk($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}

	public function baca_counter_surat_arsip($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>2),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>2),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}
	
	public function baca_counter_surat_terusan($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>3),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>3),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}

	public function baca_counter_surat_balasan($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>4),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>4),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}
	//==============END FUNGSI-FUNGSI COUNTER SURAT MASUK=====================================================

	//==============FUNGSI UNTUK MENGEKSPORT KE WORD,PDF,HTML DARI TINYMCE====================================
	public function coba_word(){
		set_error_handler("myErrorHandler");
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		//$section->addText('Hello World !');

		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $_POST['mytextarea']);
		
		$filename = $_POST['nama_file'];
		
		header('Content-Type: application/msword');
		//header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename="'. $filename .'.docx"'); 
		header('Cache-Control: max-age=0');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
		myErrorHandler($errno, $errstr, $errfile, $errline);
	}

	public function coba_word2(){
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$section->addText($_POST['mytextarea']);
		
		
		$writer = new Word2007($phpWord);
		
		$filename = $_POST['nama_file'];
		
		header('Content-Type: application/msword');
        header('Content-Disposition: attachment;filename="'. $filename .'.docx"'); 
		header('Cache-Control: max-age=0');
				
		$writer->save('php://output');
		
	}

	public function compiler_untuk_bbc_to_html($string){
		set_error_handler("myErrorHandler");
		//Ubah bbcode menjadi html tag
		$string=preg_replace('#<!DOCTYPE html>#','', $string);

		if(preg_grep("#~#i",array($string))==array()){
			$string2=preg_replace('#\n#','~', $string);
			$ok=explode('~',$string2);
		}else if(preg_grep("#`#i",array($string))==array()){
			$string2=preg_replace('#\n#','`', $string);
			$ok=explode('`',$string2);
		}else if(preg_grep("#|#i",array($string))==array()){
			$string2=preg_replace('#\n#','|', $string);
			$ok=explode('|',$string2);
		}else if(preg_grep("#~#i",array($string))==array()){
			$string2=preg_replace('#\n#','~', $string);
			$ok=explode('~',$string2);
		}else{
			alert("Maaf untuk sementara file anda tidak bisa di compile, anda dapat menyimpan file ini untuk dianalisa admin dan untuk perbaikan kode, terima kasih.");
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
			exit();
		}
		$okbru=array();
		foreach($ok as $key=>$isi){
			//Tahap pemberian pasangan tag untuk <p>, karena bbc tidak memberi pasangan
			(preg_grep("#<p#i",array($isi))!==array())?$okbru[$key]=$isi."</p>":$okbru[$key]=$isi;

			//Tahap menghilangkan semua <br/> yang menyalahi aturan html di dalm bbc
			do{
				(preg_grep("#<br /><html>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /><head>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /></head>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /><body>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /></body>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /></html>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
			}while(preg_grep("#<br /><html>#i",array($okbru[$key]))!==array());
		}
		$okberikut=implode('',$okbru);

		//Tahap mengganti semua tag bbc yaitu [] diganti menjadi <>
		$okberikut=preg_replace('#\[#','<', $okberikut);
		$okberikut=preg_replace('#\]#','>', $okberikut);

		//Perbaiki tag <img> agar sesuai standar, karena phpword tidak menerima bentuk tag bbc untuk img
		if(preg_grep("#<img>#i",array($okberikut))!==array()){
			$okberikut=explode('<img>',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</img>#i",array($isi))!==array()){
					if(preg_grep("#../../#i",array($isi))!==array()){
						//$okberikut[$key]=preg_replace('#../../#','', $isi);
						$isi=trim($isi,'.');
						$isi=trim($isi,'/');
						$isi=trim($isi,'.');
						$okberikut[$key]=preg_replace('#</img>#','"></img>', $isi);
						$okberikut[$key]='<img src=".'.$okberikut[$key];
					}else{
						$okberikut[$key]=preg_replace('#</img>#','"></img>', $isi);
						$okberikut[$key]='<img src="'.$okberikut[$key];
					}
				}
			}
			$okberikut=implode('',$okberikut);
		}

		//Perbaiki tag <color></color> karena phpword tidak mengenali, ubah menjadi <span style="color:....></span>
		if(preg_grep("#<color=#i",array($okberikut))!==array()){
			$okberikut=explode('<color=',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</color>#i",array($isi))!==array()){
					$isi=explode('>',$isi);
					$isi[0]=$isi[0].'"';
					$isi=implode('>',$isi);
					$okberikut[$key]=preg_replace('#</color>#','</span>', $isi);
					$okberikut[$key]='<span style="color:'.$okberikut[$key];
				}
			}
			$okberikut=implode('',$okberikut);
		}

		//Sekarang bagaimana menangkap border="1"?
		//pecah dulu di <table, lalu pecah di ">", lalu ambil array[0] dan tangkap border="1", setelah tangkap, trim border=" dan akhir "
		//lalu baca berapa nilainya, lalu bikin border-width:1 sesuai nilainya, lalu tambahkan ke array[0] untuk pecahan <table.
		if(preg_grep("#<table#i",array($okberikut))!==array()){
			$string_width='';
			$string_border='';
			$string_style='';
			$okberikut=explode('<table',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</table>#i",array($isi))!==array()){
					$isi_baca=array();
					$isi_baca=explode('>',$isi);

					//cek dulu apakah border ada?
					if(preg_grep("#border=#i",array($isi_baca[0]))!==array()){ 
						//jika ada, baca nilainya
						$nilai=0;
						$isi_sub=array();
						$isi_sub2=array();
						$isi_sub=explode('border="',$isi_baca[0]);
						$isi_sub2=explode('"',$isi_sub[1]);
						$nilai=$isi_sub2[0];
						$string_border='border-width:'.$nilai.'px;';
						
						//hilangkan border:
						$isi=preg_replace('#border="[0-9]*"#','', $isi);
					}

					//cek apakah width ada?
					if(preg_grep("#width=#i",array($isi_baca[0]))!==array()){ 
						//jika ada, baca nilainya
						$nilai=0;
						$isi_sub=array();
						$isi_sub2=array();
						$isi_sub=explode('width="',$isi_baca[0]);
						$isi_sub2=explode('"',$isi_sub[1]);
						$nilai=$isi_sub2[0];
						$string_width='width:'.$nilai.'px;';

						//hilangkan width:
						$isi=preg_replace('#width="[0-9]*"#','', $isi);
					}

					$isi='<table'.$isi;
					$string_style='style=" '.$string_border.' '.$string_width.' ';
					if(preg_grep("#style=#i",array($isi))!==array()){
						//tambahkan $string_style:
						$isi=preg_replace('#<table style="#',$string_style, $isi);
					}
					$okberikut[$key]='<table '.$isi;
				}

			}
			$okberikut=implode('',$okberikut);
		}

		//Sekarang bagaimana menerjemahkan kode bbc untuk link url menjadi tag <a></a>? disini tag [] sudah diganti di atas
		if(preg_grep("#<url=#i",array($okberikut))!==array()){
			$okberikut=preg_replace('#</url>#','</a>', $okberikut);
			$okberikut=preg_replace('#<url=#','<a href="', $okberikut);
			$okberikut=explode('<a href="',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</a>#i",array($isi))!==array()){
					$isi=explode('>',$isi);
					$isi[0]=$isi[0].'"';
					$isi=implode('>',$isi);
					$okberikut[$key]='<a href="'.$isi;

				}
			}
			$okberikut=implode('',$okberikut);
		}

		return $okberikut;
	}

	public function export2word_tinymce(){
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$section = $phpWord->addSection();
		
		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $this->compiler_untuk_bbc_to_html($_POST['mytextarea']));
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="'.$_POST['nama_file'].'.docx"');
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
		
	}

	public function export2pdf_tinymce(){
		//alert("Masih dalam rencana konstruksi");
		$file_html=$this->compiler_untuk_bbc_to_html($_POST['mytextarea']);
		export_html_ke_pdf($file_html,$output_dest='D',$_POST['nama_file'],$nama_satker='BKD Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban BKD',$lebar_page=270,$tinggi_page=330,$orientasi='');
	}

	public function export2excel_tinymce(){
		alert("Masih dalam rencana konstruksi");
	}

	public function export2html_tinymce(){
		//alert("OK MASUK export2html_tinymce");
		$file_html=$this->compiler_untuk_bbc_to_html($_POST['mytextarea']);
		set_error_handler("myErrorHandler");
		isset($_POST['nama_file'])?$file=$_POST['nama_file'].".html":alert('Maaf masukkan dulu nama file');
		isset($_POST['direktori_file_simpan'])&&$_POST['direktori_file_simpan']!==''?$direktori="./".$_POST['direktori_file_simpan']."/":$_POST['direktori_file_simpan']='';
		//$okbro=file_put_contents($direktori.$file, $_POST['mytextarea']);
		$_POST['direktori_file_simpan']!==''?$okbro=file_put_contents($direktori.$file, $file_html):$okbro=file_put_contents("./file_tersimpan_html/".$file, $file_html);
		if($okbro){
			//alert("direktori: ".$direktori);
			isset($direktori)?$direktori_trim=trim(trim($direktori,'.'),'/'):NULL;
			isset($direktori)?alert('data tersimpan di folder: '.base_url($direktori_trim)):alert('data tersimpan di folder '.base_url("file_tersimpan_html/"));
			//myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}else{
			alert('Data gagal tersimpan, periksa kembali direktori yang anda masukkan, apakah memang ada?');
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}
	}

	public function export2pp_tinymce(){
		alert("Masih dalam rencana konstruksi");
	}

	public function tes_preg_grep(){
		//$ok=preg_grep("#border#i",array('<table style="height: 36px;" border="1" width="69" cellspacing="0" cellpadding="0">'));
		//print_r($ok);
		$isi='<table style="height: 36px;" border="3000" width="69" cellspacing="0" cellpadding="0">';
		$isi=preg_replace('#border="[0-9]*"#','', $isi);
		echo $isi;
	} 

	public function tes_preg_replace(){
		$string='<!DOCTYPE html><html><head></head><body>jkj kdskdjskdj sdskdm</body></html>';
		echo "INI BRO POTONGNYA? ".preg_replace('#<!DOCTYPE html>#i', '', $string);
	}

	public function tes_preg_replace2(){
		$string='<!DOCTYPE html>%<html>%<head>%</head>%<body>%[i]kssssssssssssssssssssss[/i] kjda [b]skjdkld[/b] [u]kjdskad[/u]%%[color=#FF0000]kjljsk skjdlkjd lskdjldjld[/color]%% %%<table style="height: 73px; border-color: #ad2323;" border="1" width="213">%<tbody>%<tr>%<td style="width: 98.5px;">kjkss</td>%<td style="width: 98.5px;">sffdf</td>%</tr>%<tr>%<td style="width: 98.5px;">dfdf</td>%<td style="width: 98.5px;">dffdf</td>%</tr>%</tbody>%</table>%</body>%</html>';
		$string1=preg_replace('#<!DOCTYPE html>#i','', $string);
		$string2=preg_replace('#\[#','<', $string1);
		$string3=preg_replace('#\]#','>', $string2);
		$string3=preg_replace('#%#','', $string3);
		echo "INI BRO POTONGNYA? ".$string3;
	}

	public function tes_explode_untuk_p(){
		$ok="<!DOCTYPE html>
		<html>
		<head>
		</head>
		<body>
		<p align='justify'>kjskdsd [b]ksdjklasd[/b] [u]kldjlaskd[/u] [i]aslkdl[/i]
		
		<p align='center'>dlasdk ldjlkad aldalskd alsdjsakld
		
		<p align='right'>dalds ldlasd djlaskd
		
		<table style='height: 79px;' border='1' width='217' cellspacing='0' cellpadding='0'>
		<tbody>
		<tr>
		<td style='width: 100.5px;'>skjks</td>
		<td style='width: 100.5px;'>ss</td>
		</tr>
		<tr>
		<td style='width: 100.5px;'>ssxs</td>
		<td style='width: 100.5px;'>ssx</td>
		</tr>
		</tbody>
		</table>
		</body>
		</html>
		";
		print_r(explode('\n',$ok));
	}

	//==============END FUNGSI EXPORT KE WORD===========================================

	//===============FUNGSI UNTUK PERCOBAAN EDITOR=======================================
	public function iframe_editor(){
		echo "<iframe name='iframe_editor' src=\"".site_url('Frontoffice/buat_surat_baru_tinymce')."\" width='100%' height='600px' frameborder='0'></iframe>";
	}
	public function buat_surat_baru_tinymce(){
		echo "
		<link href=\"".base_url('/dashboard/vendor/fontawesome-free/css/all.min.css')."\" rel=\"stylesheet\" type=\"text/css\">
  		<link href=\"https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\">
		<link href=\"".base_url('/dashboard/css/sb-admin-2.min.css')."\" rel=\"stylesheet\">
		<script src=\"".base_url('/public/vendor3.4.1/jquery/3.4.1/jquery.min.js')."\"></script>
		<script src=\"".base_url('/public/vendor3.4.1/bootstrap/3.4.1/js/bootstrap.min.js')."\"></script>
		<!-- Bootstrap core JavaScript-->
		<script src=\"".base_url('/dashboard/vendor/jquery/jquery.min.js')."\"></script>
		<script src=\"".base_url('/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js')."\"></script>
		<!-- Core plugin JavaScript-->
		<script src=\"".base_url('/dashboard/vendor/jquery-easing/jquery.easing.min.js')."\"></script>
		<!-- Custom scripts for all pages-->
		<script src=\"".base_url('/dashboard/js/sb-admin-2.min.js')."\"></script>
		<!-- Page level plugins -->
		<script src=\"".base_url('/dashboard/vendor/chart.js/Chart.min.js')."\"></script>
		<!-- Page level custom scripts -->
		<script src=\"".base_url('/dashboard/js/demo/chart-area-demo.js')."\"></script>
		<script src=\"".base_url('/dashboard/js/demo/chart-pie-demo.js')."\"></script>
		";
		echo "
		<script src=\"".base_url('/public/tinymce/js/tinymce/tinymce.min.js')."\"></script>
		<script src=\"".base_url('/public/tinymce/js/tinymce/jquery.tinymce.min.js')."\"></script>
		";
		echo "
			<script type='text/javascript'>
			/* 
				tinymce.init({
					selector: '#mytextarea',
					plugins: 'table',
					menubar: 'table', 
					toolbar: \"insertdatetime table bold italic\"
				  });
				  */
				
				/*tinymce.init({ selector:'#mytextarea',plugins: 'table', theme: 'modern'});*/

				tinymce.init({
					selector: \"#mytextarea\",  // change this value according to your HTML
					base_url: '/public/tinymce/js/tinymce',
					plugins : 'insertdatetime table visualblocks advlist autolink link image lists charmap print preview anchor autoresize autosave bbcode code codesample colorpicker contextmenu directionality emoticons example fullpage fullscreen hr imagetools importcss layer legacyoutput media nonbreaking noneditable pagebreak paste save searchreplace spellchecker tabfocus template textcolor textpattern toc visualchars wordcount ',
					menubar: \"favs file edit view format insert tools table help\",
					//contextmenu: \"link image imagetools table spellchecker\",
					draggable_modal: true,
					mobile: {
						plugins: [ 'autosave', 'lists', 'autolink' ],
						toolbar: [ 'undo', 'bold', 'italic', 'styleselect' ]
					  },
					toolbar1: 'undo redo | fontsizes formats insertfile styleselect fontselect fontsizeselect| bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent ',
					toolbar2: \"visualblocks insertdatetime table advlist autolink link image lists charmap print preview anchor autoresize bbcode code codesample forecolor backcolor contextmenu directionality emoticons\",
					toolbar3: \"example fullpage fullscreen hr imagetools importcss layer legacyoutput media nonbreaking noneditable pagebreak paste searchreplace spellchecker tabfocus template textcolor textpattern toc visualchars wordcount\",
					menu: {
						file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
						edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
						view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
						insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
						format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' },
						tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
						table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
						help: { title: 'Help', items: 'help' },
						favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | emoticons'}
					  }

				});
				
				
				  
			</script>
		";
		//target=\"target_buat_surat_baru\" 
		echo "
			<div >
			<form target=\"target_buat_surat_baru\"  method='post' action=\"".site_url('Frontoffice/terima_hasil_ketikan_surat')."\">
			<textarea id='mytextarea' name='mytextarea' style=\"width:100%; height:60%\"></textarea>";
		
			echo "
			<!-- Modal Simpan dan Buka File -->
			<div class='modal fade' id='modal_nama_file' role='dialog''>
				<div class='modal-dialog'>
				<!-- Modal content-->
				<div class='modal-content'>
					<div class='modal-header'>
					<h7 class='modal-title'>BKD Provinsi Sulawesi Selatan</h7>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_buka_simpan' style='width:65%;' align='center' >
					<label for=nama_file>Nama file simpan:</label>
					<input type=text id='nama_file' class=\"form-control\" name='nama_file' placeholder='nama file...'>
					<input type=text id='direktori_file_simpan' class=\"form-control\" name='direktori_file_simpan' placeholder='masukkan direktori file (opsional)...'>
					<button type='submit' name='simpan' class=\"btn btn-sm btn-success shadow-sm\" id=\"simpan_file\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-save fa-sm text-white-100\"></i> Simpan</button>
					<button type='submit' id=\"export2word\" name='export2word' formaction=\"".site_url('Frontoffice/export2word_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke Word</button>
					<button type='submit' id=\"export2pdf\" name='export2pdf' formaction=\"".site_url('Frontoffice/export2pdf_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke PDF</button>
					<button type='submit' id=\"export2excel\" name='export2excel' formaction=\"".site_url('Frontoffice/export2excel_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke Excel</button>
					<button type='submit' id=\"export2html\" name='export2html' formaction=\"".site_url('Frontoffice/export2html_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Simpan ke HTML</button>
					<button type='submit' id=\"export2pp\" name='export2pp' formaction=\"".site_url('Frontoffice/export2pp_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke PowerPoint</button>
					</div>
					</center>
					</div>
					<div class='modal-footer'>
					<!--<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>-->
					</div>
				</div>
				</div>
			</div>
		";

		echo "
			</form>
			</div>
		";

		echo "
			<div >
			<form target=\"target_buat_surat_baru\" method='post' action=\"".site_url('Frontoffice/buka_surat')."\">";
			echo "
			<!-- Modal Simpan dan Buka File -->
			<div class='modal fade' id='modal_buka_file' role='dialog''>
				<div class='modal-dialog'>
				<!-- Modal content-->
				<div class='modal-content'>
					<div class='modal-header'>
					<h7 class='modal-title'>BKD Provinsi Sulawesi Selatan</h7>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_buka_file' style='width:65%;' align='center' >
					<label for=nama_file_buka>Nama file buka:</label>
					<input type=text id='nama_file_buka' class=\"form-control\" name='nama_file_buka' placeholder='nama file...'>
					<input type=text id='direktori_file' class=\"form-control\" name='direktori_file' placeholder='masukkan direktori file (opsional)...'>
					<button type='button' name='buka_file' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"buka_file\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-folder-open fa-sm text-white-100\"></i> Buka</button>
					</div>
					</center>
					</div>
					<div class='modal-footer'>
					<!--<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>-->
					</div>
				</div>
				</div>
			</div>
		";

		echo "
			<div>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='simpan_file1' class=\"d-sm-inline-block btn btn-sm btn-primary shadow-sm\" id=\"simpan_file1\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-save fa-sm text-white-100\"></i> Simpan</button>
				<button type=button data-toggle=\"modal\" data-target=\"#modal_buka_file\" name='buka_file1' class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm\" id=\"buka_file1\"  style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-folder-open fa-sm text-white-100\"></i> Buka</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttopdf' class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm\" id=\"exporttopdf\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-pdf fa-sm text-white-100\"></i> Export PDF</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttohtml' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"exporttohtml\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-html fa-sm text-white-100\"></i> Simpan HTML</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttoword' class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm\" id=\"exporttoword\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-word fa-sm text-white-100\"></i> Export Word</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttoexcel' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"exporttoexcel\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-excel fa-sm text-white-100\"></i> Export Excel</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttopp' class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm\" id=\"exporttopp\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-powerpoint fa-sm text-white-100\"></i> Ex PowerPoint</button>
				<!--<input style=\"float:right\" type=text class='form-control' name='nama_file'><label for=nama_file style=\"float:right;\">Masukkan nama file: </label>-->
			</div>
		";

		echo "
			<style>
				#simpan_file{
					display:none;
				}
				#export2word{
					display:none;
				}
				#export2pdf{
					display:none;
				}
				#export2excel{
					display:none;
				}
				#export2html{
					display:none;
				}
				#export2pp{
					display:none;
				}
				#direktori_file_simpan{
					display:block;
				}
			</style>
			<script>
			$(document).ready(function(){
                $(\"#simpan_file1\").click(function(){
					$('#simpan_file').show();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').show();
				});
				$(\"#exporttoword\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').show();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
                $(\"#exporttopdf\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').show();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
				$(\"#exporttoexcel\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').show();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
                $(\"#exporttohtml\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').show();
					$('#export2pp').hide();
					$('#direktori_file_simpan').show();
				});
				$(\"#exporttopp\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').show();
					$('#direktori_file_simpan').hide();
				});
				
				});
			</script>
		";
		echo "<iframe name='target_buat_surat_baru' width='0' height='0' frameborder='0'></iframe>";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#buka_file\").click(function(){
				  var tampilkan = $(\"#mytextarea\");
				  var nama_file = $(\"#nama_file_buka\").val();
				  var direktori_file = $(\"#direktori_file\").val();
                  $.post('".site_url("/Frontoffice/buka_surat")."',{ nama_file_buka:nama_file, direktori_file:direktori_file},
                  function(data,status){
					tinymce.activeEditor.setContent(data);

                  });
                });
				});
			</script>
        ";
		
	}

	public function terima_hasil_ketikan_surat(){
		set_error_handler("myErrorHandler");
		isset($_POST['nama_file'])?$file=$_POST['nama_file'].".bbc":alert('Maaf masukkan dulu nama file');
		isset($_POST['direktori_file_simpan'])&&$_POST['direktori_file_simpan']!==''?$direktori="./".$_POST['direktori_file_simpan']."/":$_POST['direktori_file_simpan']='';
		//$okbro=file_put_contents($direktori.$file, $_POST['mytextarea']);
		$_POST['direktori_file_simpan']!==''?$okbro=file_put_contents($direktori.$file, $_POST['mytextarea']):$okbro=file_put_contents("./file_tersimpan/".$file, $_POST['mytextarea']);
		if($okbro){
			//alert("direktori: ".$direktori);
			isset($direktori)?$direktori_trim=trim(trim($direktori,'.'),'/'):NULL;
			isset($direktori)?alert('data tersimpan di folder: '.base_url($direktori_trim)):alert('data tersimpan di folder '.base_url("file_tersimpan/"));
			//myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}else{
			alert('Data gagal tersimpan, periksa kembali direktori yang anda masukkan, apakah memang ada?');
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}
	}

	public function buka_surat(){
		set_error_handler("myErrorHandler");
		isset($_POST['nama_file_buka'])?$file=$_POST['nama_file_buka'].".bbc":alert('Maaf masukkan dulu nama file');
		isset($_POST['direktori_file'])&&$_POST['direktori_file']!==''?$direktori="./".$_POST['direktori_file']."/":$_POST['direktori_file']='';
		
		//rencanakan disini untuk menyimpna handler error:
		$_POST['direktori_file']!==''?$okbro=file_get_contents($direktori.$file):$okbro=file_get_contents("./file_tersimpan/".$file);
		if($okbro){
			echo $okbro;
			//alert("Sumber file: ".base_url().$direktori);
		}else{
			echo('Data gagal diambil, mungkin namanya salah, coba jangan tambahkan ekstensi file yaitu .html, atau direktori salah<br><br>');
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}
	}

	public function penerima_surat_yang_dibuat($data){
		$file=$_POST['file'];
		$okbro=file_put_contents("./file_tersimpan/".$file,$_POST['mytextarea']);
		if($okbro){
			echo('data tersimpan');}else{
		echo('data gagal tersimpan');}

	}

	public function buat_surat_baru_summernote(){
		echo "
		<link href=\"https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" rel=\"stylesheet\">
		<script src=\"https://code.jquery.com/jquery-3.4.1.min.js\"></script>
		<script src=\"https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
		<link href=\"https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css\" rel=\"stylesheet\">
		<script src=\"https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js\"></script>
		<!--
		<style src=\"".base_url('/public/summernote/summernote.min.css')."\"></style>
		<script src=\"".base_url('/public/summernote/summernote.min.js')."\"></script>
		-->
		";
		echo "
			<script>
			$(document).ready(function() {
				$('#summernote').summernote();
			});
			</script>
		";
		echo "
			<div >
			<h1>TinyMCE Quick Start Guide</h1>
			<form method='post'>
			<textarea id='summernote' style='width:100%; height:800px;'>Hello, World!</textarea>
			</form>
			</div>
		";
	}
	//===============END FUNGSI PERCOBAAN EDITOR=========================================

	//===============FUNGSI UNTUK PERCOBAAN EXCEL========================================
	public function tes_huruf($batas='z'){
		$i='A';
		$rentang=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40);
		foreach($rentang as $ok){
			echo "<br>$i";
			$i++;
		}
	}

	public function tes_preg($input){
		if((preg_grep("#[a-z]#i",array($input)))==array()) echo "bukan huruf";
	}

	public function tes_tambahkan_setiap_tabel_deng_id(){
		$tables = $this->db->list_tables();
		foreach ($tables as $table)
		{
			if((preg_grep("#tbl_#i",array($table)))!==array()) {
				echo "<br>ALTER TABLE `$table` ADD `id_$table` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id_$table`);";
			}
		}
	}

	public function tes_query(){
		$query = $this->db->query($this->sanitasi_controller('SELECT * FROM identpeg limit 0,10'));
		//foreach ($query->list_fields() as $field){
		//		echo "<br>".$field;
		//}

		foreach ($query->result() as $row){
			echo "<br>".$row->nipbaru;
		}
	}

	public function tes_sanitasi_danger(){
		sanitasi_kata_berbahaya($query);
	}

	public function tes_modulo($a,$n){
		echo $a%$n;
	}

	public function export2excel($nama_file_laporan=NULL,$table,$jumlah_rekord,$mulai,$order='asc',$input_query='',$kolom_cetak=array()){
		$spreadsheet = new Spreadsheet;
		$sheet = $spreadsheet->getActiveSheet();

		if($input_query!==''){
			//alert("INI HASI DARI DALAM ".$input_query);
			
			$tes=sanitasi_kata_berbahaya($input_query);
			
			if($tes){
				alert("Maaf query tidak boleh memuat kata yang otoritasnya selain SELECT");
				exit();
			}
			
			$query_ok = $this->db->query($input_query);
			$fields=array();
			$k=0;
			foreach ($query_ok->list_fields() as $field)
			{
				$fields[$k]=$field;
				$k++;
			}
			//alert(implode(' ',$fields));
			//$ok=implode('_',$fields);
			//alert("INI ok: ".$fields[0]);
			
			
			$sheet->setCellValue('A1',"Hasil Query \"".$input_query."\"");
			$i='A';
			foreach ($fields as $field){
				$sheet->setCellValue($i.'3',$field);
				$i++;
			}
			
			$j=4;
			//$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
			$query = $query_ok;//$this->db->get($table, $jumlah_rekord, $mulai);
			foreach ($query->result() as $row){
				$i='A';
				foreach($fields as $field){
					if((preg_grep("#[a-z]#i",array($row->$field)))==array()&&$row->$field!==''){
						$sheet->setCellValue($i.$j,"'".strval($row->$field)."'");
					}else{
						$sheet->setCellValue($i.$j,strval($row->$field));
					}
					$i++;
				}
				$j++;
			}
			
			$nama_file_laporan==NULL?$filename = 'laporan_query_'.'bankdata'.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;
			
		}else {
			$fields=array();
			$i=0;
			if($kolom_cetak!==array()){
				foreach($kolom_cetak as $value){
					$fields[$i]=$value;
					$i++;
				}
				$this->db->select($fields);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);

			}else{
				$fields = $this->db->list_fields($table);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);
			}
			//$fields = $this->db->list_fields($table);
			$sheet->setCellValue('A1','Tabel '.ucwords($table));
			$i='A';
			foreach ($fields as $field){
				$sheet->setCellValue($i.'3',$field);
				$i++;
			}
	
			$j=4;
			//$query = $this->db->get($table, $jumlah_rekord, $mulai);
			foreach ($query->result() as $row){
				$i='A';
				foreach($fields as $field){
					if((preg_grep("#[a-z]#i",array($row->$field)))==array()&&$row->$field!==''){
						$sheet->setCellValue($i.$j,"'".strval($row->$field)."'");
					}else{
						$sheet->setCellValue($i.$j,strval($row->$field));
					}
					$i++;
				}
				$j++;
			}
			$nama_file_laporan==NULL?$filename = 'laporan_tabel_'.$table.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;

		}


		
		$writer = new Xlsx($spreadsheet);
		
		header('Content-Type: application/vnd.ms-ecxel');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		
	}

	public function export2pdf($nama_file_laporan='',$table,$jumlah_rekord,$mulai,$order='asc',$input_query='',$kolom_cetak=array(),$orientasi='P',$tinggi_hal=800,$lebar_hal=210){
		if($input_query==''){
			$fields=array();
			$i=0;
			if($kolom_cetak!==array()){
				foreach($kolom_cetak as $value){
					$fields[$i]=$value;
					$i++;
				}
				$this->db->select($fields);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);

			}else{
				$fields = $this->db->list_fields($table);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);
			}
			

			$i=0;
			foreach ($query->result() as $row){
				$j=0;
				foreach($fields as $field){
					$data[$i][$j]=$row->$field;
					$j++;
				}
				$i++;
			}

			//penentuan panjang tiap-tiap sel:
			$panjang_tiap_sel=array();
			$i=0;
			foreach($fields as $k=>$field){
				//Semua perhitungan disini di dasarkan pada perbandingan untuk 15 karakter ukuran 12 = kira-kira 40 point jarak di pdf.
				strlen($field)>15&&strlen($field)<40?$panjang_tiap_sel[$i]=ceil(strlen($field)*40/15)+ceil(40/15):$panjang_tiap_sel[$i]=40;

				//SETTINGAN INI KHUSUS, TIDAK GENERAL, HANYA BERLAKU UNTUK STRUKTUR DATA BKD PEMPROV SULSEL YANG BERLAKU SEKARANG:
				$field=='NIP'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='KGolRu'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='STMT'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NSTTPP'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='KPej'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NtBAKN'?$panjang_tiap_sel[$i]=22:NULL;
				$field=='gldepan'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kgoldar'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrt'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrw'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='suku'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kskawin'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='kduduk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjpeg'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kstatus'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kagama'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjkel'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='altelp'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='alkoprop'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkokab'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='alkokec'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkodes'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kpos'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='kaparpol'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='npap'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='glblk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='tlahir'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='npap_g'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='nkarpeg'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='naskes'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='ntaspen'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='nkaris_su'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='aljalan'?$panjang_tiap_sel[$i]=30:NULL;

				$i++;
			}

			$panjang_tiap_sel[0]=ceil(7*40/15)+5;
			$fields[0]='id';

			$tinggi_tiap_baris=array();
			$max=1; //1 = ukuran 1 sel.
			$kandidat=0;
			$o=0;
			//$okbro=array();

			//pikirkan bagaimana agar mengikuti ukuran lebar kolom, jangan berpatokan 40
			foreach($data as $k=>$row){
				$max=1;
				$kandidat=0;
				foreach($row as $s=>$isi){
					//obselet:
					//strlen($isi)>15?$kandidat=ceil(strlen($isi)/15):NULL;//kenapa 15? karena untuk panjang sel 40 = kira-kira minimal 15 karakter
					
					//filosofi hitungan ini:
					//satu satuan tinggi sel diambil nilai 6 point.
					//berapa satuan tinggi rekord? = nilai tinggi sel maksimum dari seluruh sel dalam satu rekord.
					//$max =sel dengan tinggi maksimum
					//tinggi aktual sel = $max dikali satuan tinggi sel yaitu 6 point = $max*6
					//cara menghitung $max:
					//hitung $kandidat. strlen($isi)*(40/15) diambil dari perbandingan bahwa (40 panjang aktual sel:15 panjang karakter) sehingga panjang aktual isi sel = (strlen($isi)*(40/15)
					//kemudian $kandidat adalah rasio panjang aktual isi dibagi panjang aktual panjang sel yang ditetapkan sebelumnya, lalu dibulatkan ke atas.
					//menghasilkan $max.
					$kandidat=ceil((strlen($isi)*(40/15))/$panjang_tiap_sel[$s]);
					$kandidat>$max?$max=$kandidat:NULL;
					//$okbro[$k][$s]=strlen($isi);
				}
				$tinggi_tiap_baris[$k]=$max*6;
			}
			//alert("tinggi_tiap_baris: ".implode('  ',$tinggi_tiap_baris));
			$lebar_page=max((array_sum($panjang_tiap_sel)+40),210,$lebar_hal);
			$tinggi_page=$tinggi_hal;

			$nama_file_laporan==''?$filename = 'laporan_tabel_'.$table.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;
			BasicTable_tcpdf($fields,$data,'D',$filename.'.pdf',$nama_satker='BKD Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban BKD',$panjang_tiap_sel,$lebar_page,$tinggi_tiap_baris,$tinggi_page,$orientasi);
		}else{
			//alert('OK MASUK BAGIAN QUERY BRO: '.$input_query);
			$tes=sanitasi_kata_berbahaya($input_query);
			
			if($tes){
				alert("Maaf query tidak boleh memuat kata yang otoritasnya selain SELECT");
				exit();
			}
			
			$query_ok = $this->db->query($input_query);
			$fields=array();
			$k=0;
			foreach ($query_ok->list_fields() as $field){
				$fields[$k]=$field;
				$k++;
			}
			//alert(implode('  ',$fields));
			//$ok=implode('_',$fields);
			//alert("INI ok: ".$fields[0]);

			$i=0;
			foreach ($query_ok->result() as $row){
				$j=0;
				foreach($fields as $field){
					$data[$i][$j]=$row->$field;
					$j++;
				}
				$i++;
			}

			//penentuan panjang tiap-tiap sel:
			$panjang_tiap_sel=array();
			$i=0;
			foreach($fields as $k=>$field){
				//Semua perhitungan disini di dasarkan pada perbandingan untuk 15 karakter ukuran 12 = kira-kira 40 point jarak di pdf.
				strlen($field)>15&&strlen($field)<40?$panjang_tiap_sel[$i]=ceil(strlen($field)*40/15)+ceil(40/15):$panjang_tiap_sel[$i]=40;

				//SETTINGAN INI KHUSUS, TIDAK GENERAL, HANYA BERLAKU UNTUK STRUKTUR DATA BKD PEMPROV SULSEL YANG BERLAKU SEKARANG:
				$field=='NIP'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='KGolRu'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='STMT'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NSTTPP'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='KPej'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NtBAKN'?$panjang_tiap_sel[$i]=22:NULL;
				$field=='gldepan'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kgoldar'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrt'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrw'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='suku'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kskawin'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='kduduk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjpeg'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kstatus'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kagama'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjkel'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='altelp'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='alkoprop'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkokab'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='alkokec'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkodes'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kpos'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='kaparpol'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='npap'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='glblk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='tlahir'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='npap_g'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='nkarpeg'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='naskes'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='ntaspen'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='nkaris_su'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='aljalan'?$panjang_tiap_sel[$i]=30:NULL;

				$i++;
			}

			$panjang_tiap_sel[0]=ceil(7*40/15)+5;
			//$fields[0]='id';

			$tinggi_tiap_baris=array();
			$max=1; //1 = ukuran 1 sel.
			$kandidat=0;
			$o=0;
			//$okbro=array();
			foreach($data as $k=>$row){
				foreach($row as $s=>$isi){
					//obselet:
					//strlen($isi)>15?$kandidat=ceil(strlen($isi)/15):NULL;//kenapa 15? karena untuk panjang sel 40 = kira-kira minimal 15 karakter
					//$kandidat>$max?$max=$kandidat:NULL;
					
					$kandidat=ceil((strlen($isi)*(40/15))/$panjang_tiap_sel[$s]);
					$kandidat>$max?$max=$kandidat:NULL;
					//$okbro[$k][$s]=strlen($isi);
				}
				$tinggi_tiap_baris[$k]=$max*6;
			}
			//alert("tinggi_tiap_baris: ".implode('  ',$tinggi_tiap_baris));
			$lebar_page=max((array_sum($panjang_tiap_sel)+40),210);
			$tinggi_page=800;

			$nama_file_laporan==NULL?$filename = 'laporan_tabel_'.$table.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;
			BasicTable_tcpdf($fields,$data,'D','laporan_pdf.pdf',$nama_satker='BKD Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban BKD',$panjang_tiap_sel,$lebar_page,$tinggi_tiap_baris,$tinggi_page,$orientasi);
		}
	}
	

	public function proses_cetak_laporan(){
		if($_POST['luaran']=='excel'){
			if(isset($_POST['query'])&&$_POST['query']!==''){
				//alert('MASUK ATAS BRO');
				$this->export2excel($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],$_POST['query']);
			}else{
				//alert('MASUK BAWAH BRO');
				$fields = $this->db->list_fields($_POST['pilihan_tabel']);
				$kolom_cetak=array();
				foreach($fields as$k=>$field){
					if(isset($_POST[$field]))$kolom_cetak[$k]=$_POST[$field];
				}
				$this->export2excel($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],'',$kolom_cetak);
			}
		}else if($_POST['luaran']=='pdf'){
			if($_POST['luaran']=='pdf'&&$_POST['query']!==''){
				//alert('MASUK ATAS BRO');
				//alert('ISI QUERY '.$_POST['query']);
				$this->export2pdf($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],$_POST['query'],$kolom_cetak=NULL,$_POST['orientasi'],$_POST['tinggi_hal'],$_POST['lebar_hal']); 
			}else{
				//alert('MASUK BAWAH BRO');
				$fields = $this->db->list_fields($_POST['pilihan_tabel']);
				$kolom_cetak=array();
				foreach($fields as$k=>$field){
					if(isset($_POST[$field]))$kolom_cetak[$k]=$_POST[$field];
				}
				
				//alert("orientasi: ".$_POST['orientasi']."  tinggi_hal: ".$_POST['tinggi_hal']."  lebar_hal: ".$_POST['lebar_hal']);
				//alert(implode('  ',$kolom_cetak));
				$this->export2pdf($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],'',$kolom_cetak,$_POST['orientasi'],$_POST['tinggi_hal'],$_POST['lebar_hal']);
			}
		}else{
			alert('tipe luaran '.$_POST['luaran'].' masih dalam rencana konstruksi');
		}
	}

	public function cetak_laporan($nama_id_tampilan_pdf='tampilan_pdf',$listkolom='list_kolom'){
		echo "
			<style>
				.tampilan_standar$listkolom{
					display:block;
				}
				.tampilan_query$listkolom{
					display:none;
				}
				.$nama_id_tampilan_pdf{
					display:none;
				}
			</style>
		";


		echo "<h5>Cetak Laporan</h5>";//target='targetprosescetaklaporan'
		echo "
		<label style=\"margin-right:1px;\" onclick=\"$('.tampilan_query$listkolom').hide();$('.tampilan_standar$listkolom').show();\"><input type=\"radio\" name=\"luaran\" id=\"standar\" value=\"standar\" checked> <span class=\"badge badge-success\" style=\"margin-top:-21px;\">Laporan Standar</span></label>
		<label style=\"margin-right:1px;\" onclick=\"$('.tampilan_standar$listkolom').hide();$('.tampilan_query$listkolom').show();\"><input type=\"radio\" name=\"luaran\" id=\"lanjut\" value=\"lanjut\"> <span class=\"badge badge-info\" style=\"margin-top:-21px;\">Laporan Lanjut</span></label>
		";
		echo "
			<form  action=\"".site_url('Frontoffice/proses_cetak_laporan')."\" method='post'>
			<div class=\"form-group tampilan_standar$listkolom\" align=\"left\">
			<label for=\"pilihan_tabel\">Pilih tabel yang hendak dicetak</label>
			<select class=\"form-control\" id=\"pilihan_tabel\" name=\"pilihan_tabel\">
				<option value=\"user\">Pilih nama tabel berikut</option>";
				$tables = $this->db->list_tables();
				foreach ($tables as $table)
				{
						echo "<option value=\"$table\">".ucwords(implode(' ',explode('_',$table)))."</option>";
				}
				
		
		echo "
			</select>
			</div>";
		echo "
			<div class=\"form-group tampilan_query$listkolom\" align=\"left\">
			<label for=\"mulai\">Buat query untuk dicetak: </label>
			<input type=\"text\" class=\"form-control\" id=\"query\" name=\"query\" >
			</div>";

		
		echo "
			<div class=\"form-group tampilan_standar$listkolom\" align=\"left\">
			<label for=\"mulai\">Mulai rekord: <input type=\"text\" class=\"form-control\" id=\"mulai\" name=\"mulai\" value=\"0\"></label>
			</div>
			<div class=\"form-group tampilan_standar$listkolom\" align=\"left\">
			<label for=\"jumlah_rekord\">Jumlah rekord: <input type=\"text\" class=\"form-control\" id=\"jumlah_rekord\" name=\"jumlah_rekord\" value=\"20\"></label>
			</div>
			<div class=\"form-group tampilan_standar$listkolom\" align=\"left\">
			<label for=\"nama_file\">Nama file yang diberikan (opsional): <input type=\"text\" class=\"form-control\" id=\"nama_file\" name=\"nama_file\"></label>
			</div>
			<div class=\"form-group tampilan_standar$listkolom\" align=\"left\">
			<label for=\"sampai\">Urutkan tabel sebelum cetak: <select class=\"form-control\" id=\"pilihan_tabel\" name=\"urutan_tabel\">
			<option value=\"asc\">Pilih urutan dalam tabel</option><option value=\"desc\">Descending (Mulai rekord paling akhir)</option><option value=\"asc\">Ascending (Mulai rekord paling pertama)</option></select></label>
			</div>";
		
		echo "
		<div class=\"form-group tampilan_standar$listkolom\" align=\"left\">
			<a style=\"cursor:pointer;color:white;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"$listkolom\" ><i class=\"fas fa-list fa-sm text-white-50\"></i> Pilih kolom yang mau dicetak [opsional]</a>
		</div>
		";

		echo "
			<center>
			<div id='pra_tabel_list_kolom' style='width:40%;display:none;' align='center' >
			<div class=\"progress\" style=\"margin-top:10px;margin-bottom:10px; height:20px\">
				<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
				mohon tunggu...
				</div>
			</div>
			</div>
			</center>
			<div id=penampil_tabel_list_kolom class=\"tampilan_standar$listkolom\" align=\"center\" style='width:100%;overflow:auto;'></div>
		";

		//Kode ajax untuk tampilkan kolom tabel:
		echo "
			<script>
              $(document).ready(function(){
                $(\"#$listkolom\").click(function(){
                  var loading = $(\"#pra_tabel_list_kolom\");
				  var tampilkan = $(\"#penampil_tabel_list_kolom\");
				  var table=$(\"#pilihan_tabel\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/penampil_list_kolom")."',{ data:table},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
        ";

		echo "
		<div class=\"form-group tampilan_standar$listkolom $nama_id_tampilan_pdf\" align=\"left\">
		<label for=\"orientasi\">Orientasi Halaman (Portrait | Landscape): 
			<select class=\"form-control\" id=\"orientasi\" name=\"orientasi\">
			<option value=\"P\" selected>Portrait</option>
			<option value=\"L\">Landscape</option>
			</select></label>
		</div>

		<div class=\"form-group tampilan_standar$listkolom $nama_id_tampilan_pdf\" align=\"left\">
			<label for=\"lebar_hal\">Lebar halaman (mm): <input type=\"text\" class=\"form-control\" id=\"lebar_hal\" name=\"lebar_hal\" value=\"210\"></label>
			</div>

		<div class=\"form-group tampilan_standar$listkolom $nama_id_tampilan_pdf\" align=\"left\">
		<label for=\"tinggi_hal\">Tinggi halaman (mm): <input type=\"text\" class=\"form-control\" id=\"tinggi_hal\" name=\"tinggi_hal\" value=\"800\"></label>
		</div>
		";

		echo "
			<script>
              $(document).ready(function(){
                $(\"#luaran_pdf$listkolom\").click(function(){
					$(\".$nama_id_tampilan_pdf\").show();
				  });
				$(\"#luaran_excel$listkolom\").click(function(){
					$(\".$nama_id_tampilan_pdf\").hide();
				  });
				$(\"#luaran_json$listkolom\").click(function(){
					$(\".$nama_id_tampilan_pdf\").hide();
					alert('Maaf Tipe Json masih dalam rencana konstruksi');
				  });
				$(\"#luaran_csv$listkolom\").click(function(){
					$(\".$nama_id_tampilan_pdf\").hide();
					alert('Maaf Tipe CSV masih dalam rencana konstruksi');
				  });
				$(\"#luaran_xml$listkolom\").click(function(){
					$(\".$nama_id_tampilan_pdf\").hide();
					alert('Maaf Tipe XML masih dalam rencana konstruksi');
				  });
				});
			</script>
        ";

		echo "
			<div class=\"radio\">
			<label style=\"margin-right:1px;\" id=\"luaran_excel$listkolom\"><input type=\"radio\" name=\"luaran\" value=\"excel\" checked> <span class=\"badge badge-primary\" style=\"margin-top:-21px;\">Excel</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_pdf$listkolom\"><input type=\"radio\" name=\"luaran\" value=\"pdf\"> <span class=\"badge badge-warning\" style=\"margin-top:-21px;\">PDF</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_json$listkolom\"><input type=\"radio\" name=\"luaran\" value=\"json\"> <span class=\"badge badge-success\" style=\"margin-top:-21px;\">Json</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_csv$listkolom\"><input type=\"radio\" name=\"luaran\" value=\"csv\"> <span class=\"badge badge-info\" style=\"margin-top:-21px;\">CSV</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_xml$listkolom\"><input type=\"radio\" name=\"luaran\" value=\"xml\"> <span class=\"badge badge-info\" style=\"margin-top:-21px;\">XML</span></label>
			</div>
			<button type=\"submit\" class=\"btn btn-primary\" style=\"width:100%;\"><i class=\"fas fa-paper-plane fa-sm \"></i> Export</button>
		</form> 
		";
		
		echo "<iframe name='targetprosescetaklaporan' width='0' height='0' frameborder='0'></iframe>";
	}

	public function penampil_list_kolom(){
		$fields = $this->db->list_fields($_POST['data']);
		$i=0;
		foreach($fields as $field){
			echo "<div class='checkbox tampilan_standar' align='left'>";
			echo "<label><input type='checkbox' value=\"$field\" name=\"$field\"> <span class=\"badge badge-info\" style=\"margin-top:-20px;\"><i class=\"fas fa-check fa-sm \"></i> $field</span></label>";
			echo "</div>";
			$i++;
		}
		//echo "<input type='hidden' name='jumlah_kolom_cetak' value=\"".($i-1)."\">";
	}

	//===============END FUNGSI PERCOBAAN EXCEL==========================================

	//===============FUNGSI UNTUK SEARCHING GENERAL DI NAVBAR ATAS=======================
	/**
	 * Filosofi dari rencana fungsi ini adalah ketika kita melakukan searching, maka seraching terjadi di sisi server
	 * mencari seluruh tabel dan seluruh kolom yang memuat kata tersebut, lalu me list nya dalam list aktif yang kemudian
	 * bisa menampilkan tabel bersangkutan jika di klik.
	 * Tabel ditampilkan di ruang utama.
	 */

	public function search_general($table='identpeg'){
		echo "<h5>Hasil pencarian terdapat pada tabel dan kolom berikut di basisdata:</h5>";

		//$this->db->select();
		//$this->db->where($dataDek['nama_kolom'], $dataDek['nilai_kolom']);
		//$query = $this->db->get($tableDek);

		$tables = $this->db->list_tables();
		
		//echo $this->db->count_all_results();
		echo "<table class=\"table table-hover table-striped\">";
		$total_count=0;
		foreach ($tables as $table){
			$count=0;
			$fields = $this->db->list_fields($table);
			foreach ($fields as $field){
				$this->db->or_like($field, $_POST['data']);
			}
				$this->db->from($table);
				$count=$this->db->count_all_results();
			if($count>0){
				echo "<tr align='left'>";
				echo "<td style='margin-left:20px;' >Kata pencarian <span class='badge badge-success'>".$_POST['data']."</span> terdapat di dalam tabel $table untuk seluruh kolom sebanyak <span class='badge badge-danger'>".$count."</span> rekord </td>";
				echo "<td><button class='btn btn-xs btn-primary' id='cari_$table'>Rincian</button></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td colspan='2'>
						<center>
							<div id='pra_$table' style='width:40%;display:none;' align='center' >
							<div class='progress' style='margin-top:10px; height:30px'>
							<div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
							mohon tunggu, sedang menghitung data...
							</div>
							</div>
							</div>
						</center>
						<div id=penampil_$table align='center' style='width:100%;overflow:auto;'></div>
					 </td>";
				echo "</tr>";
			}
			echo "
				<script>      
					$(document).ready(function(){
						$(\"#cari_$table\").click(function(){
							var loading = $(\"#pra_$table\");
							var tampilkan = $(\"#penampil_$table\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url("/Frontoffice/lihat_hasil_pencarian/".$table."/".$_POST['data'])."',{ data:\"okbro\"},
							function(data,status){
								loading.fadeOut();
								tampilkan.html(data);
								tampilkan.fadeIn(2000);
							});
						});
					});
				</script>
			";
			$total_count=$total_count+$count;
		}
		if($total_count==0)echo "<tr><td align='center'><span class='badge badge-danger'>Tidak ditemukan</span> hasil pencarian yang sesuai di seluruh tabel basisdata</td></tr>";
		echo "</table>";
		echo "<div>Total hasil pencarian adalah <span class='badge badge-danger'>$total_count</span> rekord di seluruh tabel basisdata</div>";
		
	 }

	 public function lihat_hasil_pencarian($table,$data){
		echo "<table class=\"table\">";
		$fields = $this->db->list_fields($table);
			foreach ($fields as $field){
				$this->db->like($field, $data);
				$this->db->from($table);
				$count=$this->db->count_all_results();
				if($count>0){
					echo "<tr align='left'>";
					echo "<td style='margin-left:20px;' >Kata <span class='badge badge-success'>".$data."</span> pada kolom <span class='badge badge-warning'>$field</span> di tabel <span class='badge badge-info'>$table</span sebanyak <span class='badge badge-danger'>".$count."</span> rekord </td>";
					echo "<td><button class='btn btn-xs btn-success' id=\"cari_".$table."_".$field."\" data-toggle=\"modal\" data-target=\"#myModal_suratbaru\">Lihat</button></td>";
					echo "</tr>";
				}
				//Kode untuk id=lakukanpencarian
				echo "
					<script>
					$(document).ready(function(){
						$(\"#cari_".$table."_".$field."\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=20;
						var page=1;
						var page_awal=1;
						var jumlah_page_tampil=4;
						var kolom_cari=\"".$field."\";
						var nilai_kolom_cari=\"".$data."\";
			
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_search/".$table."/".$fields[0]."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil+'/TRUE/'+kolom_cari+'/'+nilai_kolom_cari,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";
			}
		echo "</table>";//xx7
				
				
	 }

	 public function tampil_tabel_cruid_search($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=TRUE,$kolom_cari=NULL,$nilai_kolom_cari=NULL){
		$awal=($currentpage-1)*$limit;
		$numrekord=$this->db->count_all($table);
		$jumlah_halaman=ceil($numrekord/$limit);

		//echo "INI JUMLAH HALAMAN: ".$jumlah_halaman;
		//echo "<br>INI mode: ".$mode;
		//echo "<br>INI kolom_cari: ".$kolom_cari;
		//echo "<br>INI nilai_kolom_cari: ".$nilai_kolom_cari;

		echo "<div align=left>Basisdata >> ".ucwords(implode(' ',explode('_',$table)))." >> Halaman ".$currentpage."</div>";
		echo "<h4>Kelola Tabel ".ucwords(implode(' ',explode('_',$table)))."</h4>";
		echo "<hr><div align=right>";
		echo "<button style=\"position:absolute; left:11px;\" id=\"tambah_data\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#modal_tambah_data\">Tambahkan data +</button>";
		echo "<button id=\"pencarian_lanjut_atas\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#searchmodal\">Pencarian Lanjut</button>";
		echo "</div><hr>";
		
		//Kode untuk tambah data:
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tambah_data\").click(function(){
                  var loading = $(\"#pra_modal_tambah_data\");
				  var tampilkan = $(\"#penampil_modal_tambah_data\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tambah_data/".$table)."',{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
        ";

		echo "
			<!-- Modal Tambah Data -->
			<div class='modal fade' id='modal_tambah_data' role='dialog' style='z-index:100000;'>
				<div class='modal-dialog modal-lg'>
				
				<!-- Modal content-->
				<div class='modal-content'>
					<div class='modal-header'>
					<h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_modal_tambah_data' style='width:65%;' align='center' >
					<i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
					<!--
					<div class='progress' style='margin-top:50px; height:20px'>
						<div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
						mohon tunggu...
						</div>
					</div>
					-->
					</center>
					<div id=penampil_modal_tambah_data align='center' style='width:100%;'></div>
					</div>
					<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
					</div>
				</div>
				
				</div>
			</div>
		";

		echo "
			<style>
				#myInput{
					width:30%;
				}
				#quantity{
					margin-left:5px;
					width:70px;
				}
				#tampilbaris{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#myInput{
						width:100%;
					}
					#quantity{
						margin-left:0px;
						width:40%;
					}
					#tampilbaris{
						margin-left:0px;
						width:59%;
					}
				  }
			</style>
			<script>
				$(document).ready(function(){
				$(\"#myInput\").on(\"keyup\", function() {
					var value = $(this).val().toLowerCase();
					$(\"#myTable tr\").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
				});
			</script>
				<div align=left>
				<label for=\"quantity\" style=\"float:left;line-height:2.2;\">Tampilkan jumlah maksimal rekord: </label>
				<input type=\"number\" class=\"form-control\" id=\"quantity\" name=\"quantity\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-info\" id=\"tampilbaris\" style=\"height:35px;\">Tampilkan</button>
				<input type=\"text\" class=\"form-control\" id=\"myInput\" style=\"float:right;height:35px;min-width:100px;\" placeholder=\"Filter...\">
				</div>
		";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tampilbaris\").click(function(){
                  var loading = $(\"#pra_myModal_suratbaru\");
				  var tampilkan = $(\"#penampil_myModal_suratbaru\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit,{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
		";

		$mode==NULL?$query=$this->sanitasi_controller("select * from $table order by $nama_kolom_id $order limit $awal,$limit"):$query=$this->sanitasi_controller("select * from $table where $kolom_cari LIKE ")."'%".$this->sanitasi_controller($nilai_kolom_cari)."%'".$this->sanitasi_controller(" order by $nama_kolom_id $order limit 0,$limit");
		//echo "<br>INI query: ".$query;
		//$query=$this->sanitasi_controller($query);
		//echo "<br> INI sehabis disanitasi: ".$query;
		$this->penampil_tabel_no_foto_controller($table,$nama_kolom_id,$array_atribut=array("","id=\"myTable\" class=\"table table-condensed table-hover table-striped\"",""),$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		echo "
			<style>
				#blokpage{
					display:flex; justify-content:center;
				}
				@media screen and (max-width: 480px) {
					#blokpage{
						justify-content:left;
					}
				}
			</style>
			<div id=\"blokpage\">
			<nav aria-label='...'>
			<ul class='pagination'>";

			//Siapkan nomor-nomor page yang mau ditampilkan
			$array_page=NULL;
			$j=0;
			for($i=$page_awal;$i<=($page_awal+($jumlah_page_tampil-1));$i++){
				$array_page[$j]=$i;
				if($limit*$i>$numrekord)break;
				$j++;
			}
			//print_r($array_page);;
				
			if($currentpage<=$jumlah_page_tampil){
				echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
			}else{
				echo "<li class='page-item' id='Previous'><a class='page-link' href='#'>Previous</a></li>";
				$current_pagePrevious=$array_page[0]-1;
				$page_awalPrevious=$current_pagePrevious-($jumlah_page_tampil-1);
				echo "
						<script>
						$(document).ready(function(){
							$(\"#Previous\").click(function(){
							var loading = $(\"#pra_myModal_suratbaru\");
							var tampilkan = $(\"#penampil_myModal_suratbaru\");
							var limit=$(\"#quantity\").val();
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_pagePrevious+'/'+$page_awalPrevious+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
							function(data,status){
								loading.fadeOut();
								tampilkan.html(data);
								tampilkan.fadeIn(2000);
							});
							});
							});
						</script>
				";
			}

			
			//echo "<br>INI current_page: ".$currentpage;
			//echo "<br>INI page_awal: ".$page_awal;

			//Tampilkan nomor-nomor halaman di paging
			for($i=$array_page[0];$i<=$array_page[sizeof($array_page)-1];$i++){
				if($currentpage==$i){
					//echo "<br>INI DALAM currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item active' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";				
				}else{
					//echo "<br>INI LUAR currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";
				}
				//if($i==$jumlah_page_tampil){break;}
			}
		
		//echo "<br>INI jumlah_halaman: ".$jumlah_halaman;
		//echo "<br>INI jumlah_page_tampil: ".$jumlah_page_tampil;
		//echo "<br>INI currentpage: ".$currentpage;
		//echo "<br>INI TOTAL HITUNG: ".($array_page[0]+$jumlah_page_tampil-1);
		//if($jumlah_halaman>$jumlah_page_tampil && !($currentpage==$jumlah_halaman)){

		//Kode untuk tombol Next:
		if(($array_page[0]+$jumlah_page_tampil-1)<$jumlah_halaman){
			echo "<li class='page-item' id=\"Next\"><a class='page-link' href='#'>Next</a></li>";
			$current_page=$array_page[sizeof($array_page)-1]+1;
			$page_awal=$current_page;
			echo "
					<script>
					$(document).ready(function(){
						$(\"#Next\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_page+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
			";
		}
		else{
			echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
		}

		echo "
			<li class='page-item disabled'><a class='page-link' href='#'>$jumlah_halaman page</a></li>
			<li class='page-item disabled'><a class='page-link' href='#'>$numrekord rekord</a></li>
			</ul>
			</nav>
			</div>
		";

		//go to page:
		echo "
			<style>
				#gotopage{
					margin-left:5px;
					width:70px;
				}
				#go{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#pencarianlanjut{
						width:100%;
					}
					#gotopage{
						margin-left:0px;
						width:40%;
					}
					#go{
						margin-left:3px;
					}
				}
			</style>
				<div align=left>
				<div style=\"float:left;\">
				<label for=\"gotopage\" style=\"float:left;line-height:2.2;\">Page: </label>
				<input type=\"number\" class=\"form-control\" id=\"gotopage\" name=\"gotopage\" min=\"1\" value=\"".$currentpage."\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-primary\" id=\"go\" style=\"height:35px;width:40px;\">go</button>
				</div>
				<button class=\"btn btn-xs btn-primary\" id=\"pencarianlanjut\" data-toggle=\"modal\" data-target=\"#searchmodal\" style=\"height:35px;float:right;\">Pencarian Lanjut</button>
				</div>
			";

			//Kode untuk id=gotopage dan id=go 
			echo "
					<script>
					$(document).ready(function(){
						$(\"#go\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";
			
			//Modal untuk pencarian lanjut:
			$fields = $this->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			echo "
				<!-- Modal Searching-->
				<div class=\"modal fade\" id=\"searchmodal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
					<div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
						<div class=\"modal-header\">
						<h5 class=\"modal-title\" id=\"exampleModalLabel\">Mode Pencarian Lanjut</h5>
						<button class=\"close\" type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\">
							<span aria-hidden=\"true\">×</span>
						</button>
						</div>
						<div class=\"modal-body\" style=\"display:flex; justify-content:center;flex-wrap: wrap;\">
						
						<input class=\"form-control\" type=\"text\" id=\"nilai_kolom_cari\" placeholder=\"Search...\"> 
						<button class=\"btn btn-xs\" disabled>Berdasarkan</button> 
						<select class=\"form-control\" id=\"kolom_cari\" name=\"kolom_cari\">";
						echo "<option value=".$fields[0].">Pilih nama kolom tabel</option>";
						foreach ($fields as $field){
							echo "<option value=\"$field\">".ucwords(implode(' ',explode('_',$field)))."</option>";
						}
						echo "
						</select>
						</div>
						<hr>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<label for=\"limicari\" style=\"float:left;line-height:2.2;\">Jumlah maksimal rekord: </label>
							<input type=\"number\" class=\"form-control\" id=\"limicari\" name=\"limicari\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;width:75px;\">
						</div>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<button class=\"btn btn-xs btn-danger\" id=\"lakukanpencarian\" data-dismiss=\"modal\">Lakukan pencarian</button>
						</div>
						<div class=\"modal-footer\">
						<button class=\"btn btn-secondary\" type=\"button\" data-dismiss=\"modal\">Cancel</button>
						</div>
					</div>
					</div>
				</div>
			";

			//Kode untuk id=lakukanpencarian
			echo "
					<script>
					$(document).ready(function(){
						$(\"#lakukanpencarian\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#limicari\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						var kolom_cari=$(\"#kolom_cari\").val();
						var nilai_kolom_cari=$(\"#nilai_kolom_cari\").val();

						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil+'/TRUE/'+kolom_cari+'/'+nilai_kolom_cari,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";

	}

	 

	 //=====================END FUNGSI PENCARIAN=========================================
	
	//===============FUNGSI KHUSUS UNTUK MIGRASI=========================================
	public function migrasi_password_pegawai(){
		//baca semua nipbaru:
		$query = $this->db->get('identpeg');
		$i=0;
		foreach ($query->result() as $row)
		{
				if($i>8790){
					$this->db->set('password',password_hash($row->nipbaru, PASSWORD_BCRYPT));
					$this->db->where('nipbaru',$row->nipbaru);
					$this->db->update('identpeg');
				}
				$i++;
		}
	}

	public function cek(){
		//baca semua nipbaru:
		$query = $this->db->get('identpeg');
		$i=0;
		$tanda=true;
		foreach ($query->result() as $row)
		{
				echo "<br>$i: nipbaru:".$row->nipbaru."   username: ".$row->username."   password: ".$row->password;
				if($tanda && $row->password=='') {$simpan=$i;$tanda=false;}
				$i++;
		}
		echo "<br>INI BATASNYA BRO".$simpan;
	}

	//===============API TAMBAHAN 1======================================================

	public function ubah_data_general($table,$data,$data_password,$token){
		alert("MASUK BRO DI ubah_data_general");
		if($this->enkripsi->dekapsulasiData($token)=='andisinra'){
			$tableDek=$this->enkripsi->dekapsulasiData($table);
			$dataDek=$this->enkripsi->dekapsulasiData($data);
			$data_passwordDek=$this->enkripsi->dekapsulasiData($data_password);
			alert("tabledek: ".$tableDek);
			alert("dataDek nama kolom: ".$dataDek['nama_kolom']);
			alert("dataDek nilai kolom: ".$dataDek['nilai_kolom']);
			//alert("query->conn_id->affected_rows: ".$query->conn_id->affected_rows);

			$this->db->select($dataDek['nama_kolom']);
			$this->db->where($dataDek['nama_kolom'], $dataDek['nilai_kolom']);
			$query = $this->db->get($tableDek);
			alert("query->conn_id->affected_rows: ".$query->conn_id->affected_rows);
		}
	}

	public function cek_data_general($table,$data,$data_password,$data_password_baru,$token){
		if($this->enkripsi->dekapsulasiData($token)=='andisinra'){
			$tableDek=$this->enkripsi->dekapsulasiData($table);//ini data tabel identpeg
			$dataDek=$this->enkripsi->dekapsulasiData($data);//ini data nipbaru 
			$data_passwordDek=$this->enkripsi->dekapsulasiData($data_password);//ini data password lama
			$data_password_baruDek=$this->enkripsi->dekapsulasiData($data_password_baru);//ini data password baru

			$this->db->select($data_passwordDek['nama_kolom']);
			$this->db->where($dataDek['nama_kolom'], $dataDek['nilai']);
			$query = $this->db->get($tableDek);
			if($query->conn_id->affected_rows>0){
				foreach($query->result() as $row){
					if(password_verify($data_passwordDek['nilai'],$row->password)){//ingat untuk kelak menyimpan handling error disini atau di dalam fungsi update_style_CI
						$this->model_frommyframework->update_style_CI($tableDek,$dataDek,array('password'=>password_hash($data_password_baruDek['nilai'],PASSWORD_BCRYPT)));
						//redirect("https://localhost/front-office-depan/index.php/Akuntamupegawai/proses_ubah_password/cocok");
					}else{
						alert("Maaf password lama anda tidak cocok dengan data di bank data, silahkan ulangi submit");
						//redirect("https://localhost/front-office-depan/index.php/Akuntamupegawai/proses_ubah_password/tidakcocok");
					}
				}
			}else{
				alert("Maaf password lama yang anda masukkan tidak ada di bank data, silahkan ulangi submit");
				//redirect("https://localhost/front-office-depan/index.php/Akuntamupegawai/proses_ubah_password/tidakcocok");
			}

		}
	}
	//===============END API TAMBAHAN 1==================================================

	//===============FUNGSI UNTUK UBAH PASSWORD==========================================
	public function tampilkan_form_ubah_password($table,$kolom_rujukan){
		echo "<h5>Form Ubah Password</h5>";

		echo "
		<style>
			.pass_show{position: relative} 
			.pass_show .ptxt { 
				position: absolute; 
				top: 50%; 
				right: 10px;
				color: #f36c01;
				margin-top: -10px;
				cursor: pointer; 
				transition: .3s ease all;
			} 
			.pass_show .ptxt:hover{color: #333333;} 
		</style>
		";

		echo "
		<script>
			$(document).ready(function(){
				$('.pass_show').append('<span class=\"ptxt\">Show</span>');  
				});
				$(document).on('click','.pass_show .ptxt', function(){ 
				$(this).text($(this).text() == \"Show\" ? \"Hide\" : \"Show\"); 
				$(this).prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; }); 
				}); 
		</script> 
		";

		echo "
		<form target=\"targetubahpassword\" action=\"".site_url("Frontoffice/ubah_password/".$kolom_rujukan)."\" method=\"post\" style=\"width:90%;\">
			<div class=\"form-group \" align=left>
			<label for=\"kolom_rujukan\">".ucwords(implode(' ',explode('_',$kolom_rujukan))).":</label>
			<input type=\"text\" class=\"form-control\" id=\"kolom_rujukan\" name=\"kolom_rujukan\">
			</div>
			
			<div align=left><label for=\"pwd\" >Password Baru:</label></div>
			<div class=\"form-group pass_show\" align=left>
			<input type=\"password\" class=\"form-control\" id=\"pwd\" name=\"password\">
			</div>
			<input type=\"hidden\" class=\"form-control\" id=\"table_pwd\" name=\"table\" value=\"".$table."\" >
			<button type=\"submit\" class=\"btn btn-primary\" style=\"width:100%;\">Submit</button>
		</form> 
		";
		echo "<iframe name='targetubahpassword' width='0' height='0' frameborder='0'></iframe>";
	}

	public function ubah_password($nama_kolom){
		$data = array('password' => password_hash($_POST['password'],PASSWORD_BCRYPT));
		$kolom_rujukan['nama_kolom']=$nama_kolom;
		$kolom_rujukan['nilai']=$_POST['kolom_rujukan'];
		$this->db->select($nama_kolom);
		$this->db->where($nama_kolom, $_POST['kolom_rujukan']);
		$query = $this->db->get($_POST['table']);
		if($query->conn_id->affected_rows>0){
			$this->model_frommyframework->update_style_CI($_POST['table'],$kolom_rujukan,$data);
		}else{
			alert("Maaf ".$nama_kolom."=".$_POST['kolom_rujukan']." tidak ada dalam basisdata");
		}
	}
	//===============END UBAH PASSWORD===================================================


	//===============END FUNGSI KHUSUS UNTUK MIGRASI=====================================

	public function ubah_data_pegawai(){
		//echo "OK BRO MASUK";
		if(isset($_POST['data_nama'])){
			$data_post=array();
			$directory_relatif_file_upload='./public/image_pegawai/';	
			
			$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
			$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);//xx6
			//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
			//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
			//jadi biarkan saja demikian.
			//print_r($data_post);echo "<br><br>";
			
			if($data_post['file_bmp']['nilai']){
				$upload=array();
				$upload1=upload('file_bmp', $folder=$directory_relatif_file_upload, $types="jpeg,gif,png,jpg");
			}

			//BISMILLAH:
			//pindahkan isi $data_post ke $kiriman:
			$kiriman=array();
			foreach($data_post as $key=>$k){
				//if($key=='password'){
				//	array_push($kiriman,password_hash($k['nilai'], PASSWORD_BCRYPT));
				//}else 
				if(($key=='pass_berlaku_mulai') || ($key=='pass_sampai_tgl')){
					array_push($kiriman,konversi_format_tgl_ttttbbhh_ke_hhbbtttt($k['nilai']));
				}else{
					array_push($kiriman,$k['nilai']);
				}
			}
			
			if(isset($upload1[0])) {$kiriman[49]=$directory_relatif_file_upload.$upload1[0];}
			//echo "<br> ini kiriman: ";

			$tabel='identpeg';
			//print_r($kiriman);

			//BUAT ERROR HANDLER DISINI LAN.
			$this->general_update_controller($kiriman,$tabel);
			
			//=====================TARIK KEMBALI DATA YANG SUDAH DIUBAH======================
			
			$Recordset[$key]=$this->user_defined_query_controller_as_array($query="select * from ".$tabel." where nipbaru=".$kiriman[1],$token='andisinra');
			$data=$this->enkripsi->enkapsulasiData($Recordset);
			$pesan="Data Sukses Tersimpan";
			redirect($this->config->item('base_domain')."/front-office-depan/index.php/Akuntamupegawai/index_dashboard_pegawai/$data/$pesan");
		}else{
			$pesan="Data gagal terkirim";
			redirect($this->config->item('base_domain')."/front-office-depan/index.php/Akuntamupegawai/index_dashboard_pegawai/NULL/$pesan");
		}
	}

	//===============KELOMPOK METHOD PENERIMA SURAT======================================
	public function terima_arsip_surat_keluar_sekretariat()
	{
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');

		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/arsip_unggah_surat_sekretariat/';	//xx5
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						}else if($key=='posisi_surat_terakhir'){
							array_push($kiriman,"Sekretariat BKD");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman[12]=$upload1[0];
					$kiriman[13]=$upload2[0];
					if($kiriman[12]) {$kiriman[14]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[14]=NULL;}
					if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[15]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[29]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_keluar';
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);//ggg2
					//print_r($kiriman);
					//Persiapan notifikasi
					
					if($hasil_insersi_surat_berkas){
						$counter_table='tbcounter_notifikasi';
						$kolom_rujukan['nama_kolom']='idcounter_notifikasi';
						$kolom_rujukan['nilai']=2;//untuk nama_counter: counter arsip surat terkirim semua bidang
						$kolom_target='nilai_counter';
						$this->model_frommyframework->naikkan_counter_notifikasi($counter_table,$kolom_rujukan,$kolom_target);
					}
	
				}
	
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Sekretariat BKD Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			}

		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
		*/
	
	}

	public function terima_arsip_surat_keluar_bidang()
	{
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');

		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/arsip_unggah_surat_bidang/';	
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						//}else if($key=='posisi_surat_terakhir'){
						//	array_push($kiriman,"Sekretariat BKD");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman[12]=$upload1[0];
					$kiriman[13]=$upload2[0];
					if($kiriman[12]) {$kiriman[14]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[14]=NULL;}
					if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[15]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[28]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_keluar';
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);//ggg
					//print_r($kiriman);
					//Persiapan notifikasi
					
					if($hasil_insersi_surat_berkas){
						$counter_table='tbcounter_notifikasi';
						$kolom_rujukan['nama_kolom']='idcounter_notifikasi';
						$kolom_rujukan['nilai']=2;//untuk nama_counter: counter arsip surat terkirim semua bidang
						$kolom_target='nilai_counter';
						$this->model_frommyframework->naikkan_counter_notifikasi($counter_table,$kolom_rujukan,$kolom_target);
					}
	
				}
	
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Sekretariat BKD Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			}
		

		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
		*/
	
	}

	//===============END KELOMPOK METHOD PENERIMA SURAT==================================

	//===============REST API======================
	public function read_where_loginpegawai($table_terenkapsulasi,$data_terenkapsulasi,$token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$table=$this->enkripsi->dekapsulasiData($table_terenkapsulasi);
			$kiriman=$this->enkripsi->dekapsulasiData($data_terenkapsulasi);
			//echo $table." dan ".$kiriman;

			foreach($kiriman as $key => $unit){
				$Recordset[$key]=$this->user_defined_query_controller_as_array($query="select * from ".$table." where ".$key."=".$unit,$token='andisinra');
			}
			//echo $Recordset['nipbaru'][0]['nipbaru']
			if($Recordset['nipbaru'][0]['nipbaru']){
				$data=$this->enkripsi->enkapsulasiData($Recordset);
				redirect($this->config->item('base_domain')."/front-office-depan/index.php/login/Logintamupegawai/balikan_dari_bankdata/".$data);
			}else{
				redirect($this->config->item('base_domain')."/front-office-depan/index.php/Akuntamupegawai/index_pegawai");
			}
			
			//print_r($Recordset);
		}
		
	}

	public function read_alamat_web($token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$Recordset=$this->user_defined_query_controller_as_array($query="select alamat,pemilik from alamat_web",$token='andisinra');
			//print_r($Recordset);
			
			echo "
			<label>Silahkan pilih target pengiriman:</label>
			<select name=okbro id=select_alamat class=\"form-control\" style=\"width:70%;min-width:250px;\">
			<option value=\"".$this->config->item('bank_data')."/index.php\" style=\"color:red;\">Klik disini untuk memilih alamat tujuan</option>
			";
			foreach($Recordset as $key=>$unit){
				echo "<option value=".$unit['alamat']." >".$unit['pemilik']."::  ".$unit['alamat']."</option>";
			}
			echo "</select>
			";

			echo "
				<!-- Script untuk pemanggilan ajax -->
				<script>      
						$(document).ready(function(){
							$(\"#select_alamat\").click(function(){
							var selectedVal = $(\"#select_alamat option:selected\").val();
							var select_form=selectedVal+'/Frontoffice/coba_kirim';
							$('#kirim_terusan').attr('action', select_form);
							});
							});
							
						</script> 
			";
		}
		
	}

	public function read_alamat_web_balasan($token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$Recordset=$this->user_defined_query_controller_as_array($query="select alamat,pemilik from alamat_web",$token='andisinra');
			//print_r($Recordset);
			
			echo "
			<label>Silahkan pilih target pengiriman:</label>
			<select name=okbro id=select_alamat class=\"form-control\" style=\"width:55%;min-width:250px;\">
			<option value=\"".$this->config->item('bank_data')."/index.php\" style=\"color:red;\">Klik disini untuk memilih alamat tujuan</option>
			";
			foreach($Recordset as $key=>$unit){
				echo "<option value=".$unit['alamat']." >".$unit['pemilik']."::  ".$unit['alamat']."</option>";
			}
			echo "</select>
			<br>";

			echo "
				<!-- Script untuk pemanggilan ajax -->
				<script>      
						$(document).ready(function(){
							$(\"#select_alamat\").click(function(){
							var selectedVal = $(\"#select_alamat option:selected\").val();
							var select_form=selectedVal+'/Frontoffice/frontoffice_index_balasan';
							$('#form_unggah_berkas').attr('action', select_form);
							});
							});
							
						</script> 
			";
		}
		
	}

	public function read_alamat_web_umum($token_terenkapsulasi,$class,$method){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$Recordset=$this->user_defined_query_controller_as_array($query="select alamat,pemilik from alamat_web",$token='andisinra');
			//print_r($Recordset);
			
			echo "
			<label>Silahkan pilih target pengiriman:</label>
			<select name=okbro id=select_alamat class=\"form-control\" style=\"width:55%;min-width:250px;\">
			<option value=\"".$this->config->item('bank_data')."/index.php\" style=\"color:red;\">Klik disini untuk memilih alamat tujuan</option>
			";
			foreach($Recordset as $key=>$unit){
				echo "<option value=".$unit['alamat']." >".$unit['pemilik']."::  ".$unit['alamat']."</option>";
			}
			echo "</select>
			<br>";

			echo "
				<!-- Script untuk pemanggilan ajax -->
				<script>      
						$(document).ready(function(){
							$(\"#select_alamat\").click(function(){
							var selectedVal = $(\"#select_alamat option:selected\").val();
							var select_form=selectedVal+'/".$class."/".$method."';
							$('#form_unggah_berkas').attr('action', select_form);
							});
							});
							
						</script> 
			";
		}
		
	}

	public function read_alamat_web_surat_masuk($token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$Recordset=$this->user_defined_query_controller_as_array($query="select alamat,pemilik from alamat_web",$token='andisinra');
			//print_r($Recordset);
			
			echo "
			<label>Silahkan pilih target pengiriman:</label>
			<select name=okbro id=select_alamat class=\"form-control\" style=\"width:55%;min-width:250px;\">
			<option value=\"".$this->config->item('bank_data')."/index.php\" style=\"color:red;\">Klik disini untuk memilih alamat tujuan</option>
			";
			foreach($Recordset as $key=>$unit){
				echo "<option value=".$unit['alamat']." >".$unit['pemilik']."::  ".$unit['alamat']."</option>";
			}
			echo "</select>
			<br>";

			echo "
				<!-- Script untuk pemanggilan ajax -->
				<script>      
						$(document).ready(function(){
							$(\"#select_alamat\").click(function(){
							var selectedVal = $(\"#select_alamat option:selected\").val();
							var select_form=selectedVal+'/Frontoffice/terima_surat_masuk';
							$('#form_unggah_berkas').attr('action', select_form);
							});
							});
							
						</script> 
			";
		}
		
	}

	public function add($table_terenkapsulasi,$data_terenkapsulasi,$token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$table=$this->enkripsi->dekapsulasiData($table_terenkapsulasi);
			$kiriman=$this->enkripsi->dekapsulasiData($data_terenkapsulasi);
			$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
			return $this->enkripsi->enkapsulasiData($hasil_insersi_surat_berkas);
		}
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token);
	}

	public function read_where($table_terenkapsulasi,$data_terenkapsulasi,$token_terenkapsulasi){
		
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$table=$this->enkripsi->dekapsulasiData($table_terenkapsulasi);
			$kiriman=$this->enkripsi->dekapsulasiData($data_terenkapsulasi);
			foreach($kiriman as $key => $unit){
				$Recordset[$key]=$this->user_defined_query_controller_as_array($query="select * from ".$table." where ".$key."=".$unit,$token='andisinra');
			}
			return $this->enkripsi->enkapsulasiData($Recordset);
		}
	}

	public function update($table_terenkapsulasi,$data_terenkapsulasi,$token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$table=$this->enkripsi->dekapsulasiData($table_terenkapsulasi);
			$kiriman=$this->enkripsi->dekapsulasiData($data_terenkapsulasi);
			$hasil=$this->general_update_controller($kiriman,$tabel);
			return $hasil;
		}
	}

	public function delete($table_terenkapsulasi,$data_terenkapsulasi,$token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$table=$this->enkripsi->dekapsulasiData($table_terenkapsulasi);
			$kiriman=$this->enkripsi->dekapsulasiData($data_terenkapsulasi);
			$hasil=$this->hapus_rekord($tabel,$id);
			return $hasil;
		}
	}

	public function read_all($table_terenkapsulasi,$token_terenkapsulasi){
		if($this->enkripsi->dekapsulasiData($token_terenkapsulasi)=='andisinra'){
			$table=$this->enkripsi->dekapsulasiData($table_terenkapsulasi);
			$Recordset=$this->user_defined_query_controller_as_array($query="select * from ".$table,$token='andisinra');
			return $this->enkripsi->enkapsulasiData($Recordset);
		}
	}

	//===============END REST API==================


	//========================MODUL UNTUK CRUID BASISDATA=============================================================
	public function sanitasi_controller($input){
        return $this->model_frommyframework->sanitasi($input);
	}
	
	public function tampil_tabel_cruid($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=NULL,$kolom_cari=NULL,$nilai_kolom_cari=NULL){
		$awal=($currentpage-1)*$limit;
		$numrekord=$this->db->count_all($table);
		$jumlah_halaman=ceil($numrekord/$limit);

		//echo "INI JUMLAH HALAMAN: ".$jumlah_halaman;
		//echo "<br>INI mode: ".$mode;
		//echo "<br>INI kolom_cari: ".$kolom_cari;
		//echo "<br>INI nilai_kolom_cari: ".$nilai_kolom_cari;

		echo "<div align=left>Basisdata >> ".ucwords(implode(' ',explode('_',$table)))." >> Halaman ".$currentpage."</div>";
		echo "<h4>Kelola Tabel ".ucwords(implode(' ',explode('_',$table)))."</h4>";
		echo "<hr><div align=right>";
		echo "<button style=\"position:absolute; left:11px;\" id=\"tambah_data\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#modal_tambah_data\">Tambahkan data +</button>";
		echo "<button id=\"pencarian_lanjut_atas\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#searchmodal\">Pencarian Lanjut</button>";
		echo "</div><hr>";
		
		//Kode untuk tambah data:
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tambah_data\").click(function(){
                  var loading = $(\"#pra_modal_tambah_data\");
				  var tampilkan = $(\"#penampil_modal_tambah_data\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tambah_data/".$table)."',{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
        ";

		echo "
			<!-- Modal Tambah Data -->
			<div class='modal fade' id='modal_tambah_data' role='dialog' style='z-index:100000;'>
				<div class='modal-dialog modal-lg'>
				
				<!-- Modal content-->
				<div class='modal-content'>
					<div class='modal-header'>
					<h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_modal_tambah_data' style='width:65%;' align='center' >
					<i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
					<!--
					<div class='progress' style='margin-top:50px; height:20px'>
						<div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
						mohon tunggu...
						</div>
					</div>
					-->
					</center>
					<div id=penampil_modal_tambah_data align='center' style='width:100%;'></div>
					</div>
					<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
					</div>
				</div>
				
				</div>
			</div>
		";

		echo "
			<style>
				#myInput{
					width:30%;
				}
				#quantity{
					margin-left:5px;
					width:70px;
				}
				#tampilbaris{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#myInput{
						width:100%;
					}
					#quantity{
						margin-left:0px;
						width:40%;
					}
					#tampilbaris{
						margin-left:0px;
						width:59%;
					}
				  }
			</style>
			<script>
				$(document).ready(function(){
				$(\"#myInput\").on(\"keyup\", function() {
					var value = $(this).val().toLowerCase();
					$(\"#myTable tr\").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
				});
			</script>
				<div align=left>
				<label for=\"quantity\" style=\"float:left;line-height:2.2;\">Tampilkan jumlah maksimal rekord: </label>
				<input type=\"number\" class=\"form-control\" id=\"quantity\" name=\"quantity\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-info\" id=\"tampilbaris\" style=\"height:35px;\">Tampilkan</button>
				<input type=\"text\" class=\"form-control\" id=\"myInput\" style=\"float:right;height:35px;min-width:100px;\" placeholder=\"Filter...\">
				</div>
		";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tampilbaris\").click(function(){
                  var loading = $(\"#pra_tabel\");
				  var tampilkan = $(\"#penampil_tabel\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit,{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
		";

		$mode==NULL?$query=$this->sanitasi_controller("select * from $table order by $nama_kolom_id $order limit $awal,$limit"):$query=$this->sanitasi_controller("select * from $table where $kolom_cari LIKE ")."'%".$this->sanitasi_controller($nilai_kolom_cari)."%'".$this->sanitasi_controller(" order by $nama_kolom_id $order limit 0,$limit");
		//echo "<br>INI query: ".$query;
		//$query=$this->sanitasi_controller($query);
		//echo "<br> INI sehabis disanitasi: ".$query;
		$this->penampil_tabel_no_foto_controller($table,$nama_kolom_id,$array_atribut=array("","id=\"myTable\" class=\"table table-condensed table-hover table-striped\"",""),$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		echo "
			<style>
				#blokpage{
					display:flex; justify-content:center;
				}
				@media screen and (max-width: 480px) {
					#blokpage{
						justify-content:left;
					}
				}
			</style>
			<div id=\"blokpage\">
			<nav aria-label='...'>
			<ul class='pagination'>";

			//Siapkan nomor-nomor page yang mau ditampilkan
			$array_page=NULL;
			$j=0;
			for($i=$page_awal;$i<=($page_awal+($jumlah_page_tampil-1));$i++){
				$array_page[$j]=$i;
				if($limit*$i>$numrekord)break;
				$j++;
			}
			//print_r($array_page);;
				
			if($currentpage<=$jumlah_page_tampil){
				echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
			}else{
				echo "<li class='page-item' id='Previous'><a class='page-link' href='#'>Previous</a></li>";
				$current_pagePrevious=$array_page[0]-1;
				$page_awalPrevious=$current_pagePrevious-($jumlah_page_tampil-1);
				echo "
						<script>
						$(document).ready(function(){
							$(\"#Previous\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							var limit=$(\"#quantity\").val();
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_pagePrevious+'/'+$page_awalPrevious+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
							function(data,status){
								loading.fadeOut();
								tampilkan.html(data);
								tampilkan.fadeIn(2000);
							});
							});
							});
						</script>
				";
			}

			
			//echo "<br>INI current_page: ".$currentpage;
			//echo "<br>INI page_awal: ".$page_awal;

			//Tampilkan nomor-nomor halaman di paging
			for($i=$array_page[0];$i<=$array_page[sizeof($array_page)-1];$i++){
				if($currentpage==$i){
					//echo "<br>INI DALAM currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item active' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";				
				}else{
					//echo "<br>INI LUAR currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";
				}
				//if($i==$jumlah_page_tampil){break;}
			}
		
		//echo "<br>INI jumlah_halaman: ".$jumlah_halaman;
		//echo "<br>INI jumlah_page_tampil: ".$jumlah_page_tampil;
		//echo "<br>INI currentpage: ".$currentpage;
		//echo "<br>INI TOTAL HITUNG: ".($array_page[0]+$jumlah_page_tampil-1);
		//if($jumlah_halaman>$jumlah_page_tampil && !($currentpage==$jumlah_halaman)){

		//Kode untuk tombol Next:
		if(($array_page[0]+$jumlah_page_tampil-1)<$jumlah_halaman){
			echo "<li class='page-item' id=\"Next\"><a class='page-link' href='#'>Next</a></li>";
			$current_page=$array_page[sizeof($array_page)-1]+1;
			$page_awal=$current_page;
			echo "
					<script>
					$(document).ready(function(){
						$(\"#Next\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_page+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
			";
		}
		else{
			echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
		}

		echo "
			<li class='page-item disabled'><a class='page-link' href='#'>$jumlah_halaman page</a></li>
			<li class='page-item disabled'><a class='page-link' href='#'>$numrekord rekord</a></li>
			</ul>
			</nav>
			</div>
		";

		//go to page:
		echo "
			<style>
				#gotopage{
					margin-left:5px;
					width:70px;
				}
				#go{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#pencarianlanjut{
						width:100%;
					}
					#gotopage{
						margin-left:0px;
						width:40%;
					}
					#go{
						margin-left:3px;
					}
				}
			</style>
				<div align=left>
				<div style=\"float:left;\">
				<label for=\"gotopage\" style=\"float:left;line-height:2.2;\">Page: </label>
				<input type=\"number\" class=\"form-control\" id=\"gotopage\" name=\"gotopage\" min=\"1\" value=\"".$currentpage."\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-primary\" id=\"go\" style=\"height:35px;width:40px;\">go</button>
				</div>
				<button class=\"btn btn-xs btn-primary\" id=\"pencarianlanjut\" data-toggle=\"modal\" data-target=\"#searchmodal\" style=\"height:35px;float:right;\">Pencarian Lanjut</button>
				</div>
			";

			//Kode untuk id=gotopage dan id=go 
			echo "
					<script>
					$(document).ready(function(){
						$(\"#go\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#quantity\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";
			
			//Modal untuk pencarian lanjut:
			$fields = $this->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			echo "
				<!-- Modal Searching-->
				<div class=\"modal fade\" id=\"searchmodal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
					<div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\">
						<div class=\"modal-header\">
						<h5 class=\"modal-title\" id=\"exampleModalLabel\">Mode Pencarian Lanjut</h5>
						<button class=\"close\" type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\">
							<span aria-hidden=\"true\">×</span>
						</button>
						</div>
						<div class=\"modal-body\" style=\"display:flex; justify-content:center;flex-wrap: wrap;\">
						
						<input class=\"form-control\" type=\"text\" id=\"nilai_kolom_cari\" placeholder=\"Search...\"> 
						<button class=\"btn btn-xs\" disabled>Berdasarkan</button> 
						<select class=\"form-control\" id=\"kolom_cari\" name=\"kolom_cari\">";
						echo "<option value=".$fields[0].">Pilih nama kolom tabel</option>";
						foreach ($fields as $field){
							echo "<option value=\"$field\">".ucwords(implode(' ',explode('_',$field)))."</option>";
						}
						echo "
						</select>
						</div>
						<hr>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<label for=\"limicari\" style=\"float:left;line-height:2.2;\">Jumlah maksimal rekord: </label>
							<input type=\"number\" class=\"form-control\" id=\"limicari\" name=\"limicari\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;width:75px;\">
						</div>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<button class=\"btn btn-xs btn-danger\" id=\"lakukanpencarian\" data-dismiss=\"modal\">Lakukan pencarian</button>
						</div>
						<div class=\"modal-footer\">
						<button class=\"btn btn-secondary\" type=\"button\" data-dismiss=\"modal\">Cancel</button>
						</div>
					</div>
					</div>
				</div>
			";

			//Kode untuk id=lakukanpencarian
			echo "
					<script>
					$(document).ready(function(){
						$(\"#lakukanpencarian\").click(function(){
						var loading = $(\"#pra_tabel\");
						var tampilkan = $(\"#penampil_tabel\");
						var limit=$(\"#limicari\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						var kolom_cari=$(\"#kolom_cari\").val();
						var nilai_kolom_cari=$(\"#nilai_kolom_cari\").val();

						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil+'/TRUE/'+kolom_cari+'/'+nilai_kolom_cari,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";

	}

	public function hapus_data(){
		
		//$this->load->view('admin_frontoffice/dashboard');
		//cccc
		if(isset($_POST['nama_tabel'])){
			$fields = $this->db->list_fields($_POST['nama_tabel']);
			foreach ($fields as $field){
				if($field=='direktori_surat_masuk' || $field=='direktori_berkas_yg_menyertai' || preg_grep("#direktori#i",array($field))){
					//baca dulu 
					$this->db->select($field);
					$this->db->from($_POST['nama_tabel']);
					$this->db->where($fields[0], $_POST['id_hapus']);
					$query = $this->db->get();
					//print_r($direktori_hapus);
					
					foreach($query->result() as $row){
						$direktori_hapus=$row->$field;
					}
					$nama_file=explode('/',$direktori_hapus);
					$nama_file=$nama_file[sizeof($nama_file)-1];
					try {
						if(@unlink($direktori_hapus)==TRUE){
							alert("File $nama_file yang terkait rekord juga sukses terhapus");
						}else{
							throw new Exception("File $nama_file yang terkait rekord tidak dapat dihapus, mungkin file yang bersangkutan tidak dalam direktori yang tercatat di rekord, atau file sedang terbuka");
							//alert("File yang terkait rekord tidak dapat dihapus, mungkin file yang bersangkutan tidak dalam direktori yang tercatat di rekord, atau file sedang terbuka");
						}
					}
					catch (Exception $e) {
						alert($e->getMessage()); // will print Exception message defined above.
					} 
					
				}
			}
			$this->hapus_rekord($_POST['nama_tabel'],$_POST['id_hapus']);
			$this->session->set_userdata('modal',TRUE);
			$this->session->set_userdata('tabel',$_POST['nama_tabel']);;
			$this->load->view('admin_frontoffice/dashboard');
			//	redirect(site_url('Frontoffice/frontoffice_admin'));
		}else{
			$this->load->view('admin_frontoffice/dashboard');
		}
		
	}

	public function update_data_cruid($table=NULL,$modal=TRUE){
		//$user = $this->session->userdata('user_ruangkaban');
        //$str = $user['email'].$user['username']."1@@@@@!andisinra";
        //$str = hash("sha256", $str );
		//$hash=$this->session->userdata('hash');
		//if(($user!==FALSE)&&($str==$hash)){
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
				$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');
				//print_r($data_post);

				$kiriman=array();
					foreach($data_post as $key=>$k){
							//if($key=='password'){
							//	array_push($kiriman,password_hash($k['nilai'], PASSWORD_BCRYPT));
							//else{
								array_push($kiriman,$k['nilai']);
							//} //xx1
						}

					//print_r($kiriman);
					//print_r($data_post);
					//$tabel='surat_masuk';
					$this->general_update_controller($kiriman,$table);
					//$this->general_insertion_controller($kiriman,$table);
					//if($hasil_insersi_surat_berkas){alert('Perubahan data sukses');}else{alert('Perubahan data gagal');}
					$this->session->set_userdata('modal',$modal);
					$this->session->set_userdata('tabel',$table);;
					$this->load->view('admin_frontoffice/dashboard');
			} else {
				!$table?alert('Nama Tabel yang hendak dirubah tidak ada'):NULL;//alert('Data berhasil ditambahkan');				
				$this->load->view('admin_frontoffice/dashboard');
			}
		//}else{
		//	alert('Maaf Session anda kadaluarsa');
		//	redirect('Frontoffice/index');
		//}
	}

	public function tambah_data($tabel)
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">Tambahkan Data Baru</span>";
		$fields = $this->db->list_fields($tabel);
		$coba=array();
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($fields[0],$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($fields[0],$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		foreach($coba as $key=>$k){
			//reset dulu semua komponen form
			$coba[$key][7]='';

			//ok mulai pengisian standar
			if($key==0) {
				$coba[$key][0]='hidden';
			}else{
				$coba[$key][0]='text';
	
				//jika nama kolom mengandung kata timestamp atau tanggal atau tgl:
				if(preg_grep("#timestamp#i",array($fields[$key])) || preg_grep("#tanggal#i",array($fields[$key])) || preg_grep("#tgl#i",array($fields[$key]))){
					$coba[$key][0]='date';
				}

				//jika nama kolom mengandung kata keterangan:
				if(preg_grep("#keterangan#i",array($fields[$key]))){
					$coba[$key][0]='area';
				}

				//jika nama kolom mengandung kata target_penerima:
				if(preg_grep("#target_penerima#i",array($fields[$key])) || preg_grep("#disposes_ke#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Kepala BKD';
				}

				//jika nama kolom mengandung kata status_pengirim:
				if(preg_grep("#status_pengirim#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='ASN internal';
				}

				//jika nama kolom mengandung kata dari_satker:
				if(preg_grep("#dari_satker#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='BADAN KEPEGAWAIAN DAERAH';
				}

				//jika nama kolom mengandung kata dari_bidang:
				if(preg_grep("#dari_bidang#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Kesejahteraan dan Kinerja Pegawai';
				}

				//jika nama kolom mengandung kata dari_bidang:
				if(preg_grep("#dari_bidang#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Kesejahteraan dan Kinerja Pegawai';
				}

				//jika nama kolom mengandung kata status_surat:
				if(preg_grep("#status_surat#i",array($fields[$key]))){
					$coba[$key][8]='masuk';
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
				}

				//jika nama kolom mengandung kata harapan_respon_hari:
				if(preg_grep("#harapan_respon_hari#i",array($fields[$key]))){
					$coba[$key][8]='3';
					$coba[$key][0]='number';
				}

				//jika nama kolom mengandung kata urgensi_surat:
				if(preg_grep("#urgensi_surat#i",array($fields[$key]))){
					$coba[$key][0]='combo_database';
					$coba[$key][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[$key][8]='Yang Lain (Others)';
				}
			}
		}
		
		$target_action="Frontoffice/tambahkan_data/".$tabel;
		$komponen=$coba;
		$atribut_form=" id=\"form_unggah_berkas\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" ";
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Tambahkan','');
		//$tombol[0]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Proses penambahan...','Tambahkan data',"Frontoffice/tambahkan_data/".$tabel);
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		$value_selected_combo='';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//echo "OK BRO SIAP-SIAP";
		
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
		//echo "<iframe name='targetkosong' width='0' height='0' frameborder='0'></iframe>";
	}

	public function tambahkan_data($table){
		//alert("OK BRO MASUK");
		if(isset($_POST['data_nama'])){
			$data_post=array();
			$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
			$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');
			
			//BISMILLAH:
			//pindahkan isi $data_post ke $kiriman:
			$kiriman=array();
			foreach($data_post as $key=>$k){
				if($key=='password'){
					array_push($kiriman,password_hash($k['nilai'], PASSWORD_BCRYPT));
				}else if(($key=='pass_berlaku_mulai') || ($key=='pass_sampai_tgl')){
					array_push($kiriman,konversi_format_tgl_ttttbbhh_ke_hhbbtttt($k['nilai']));
				}else{
					array_push($kiriman,$k['nilai']);
				}
			}

			$oke=$this->general_insertion_controller($kiriman,$table);
			//print_r($kiriman);
			$this->session->set_userdata('modal',TRUE);
			$this->session->set_userdata('tabel',$table);;
			$this->load->view('admin_frontoffice/dashboard');
		} else {
			//alert("Data gagal terkirim");
			$this->session->set_userdata('modal',TRUE);
			$this->session->set_userdata('tabel',$table);;
			$this->load->view('admin_frontoffice/dashboard');
		}
	}

	public function penampil_tabel_no_foto_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar){
		return $this->viewfrommyframework->penampil_tabel_no_foto($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	//========================END MODUL CRUID=========================================================================

	//========================TERIMA SURAT MASUK DARI SEKRETARIAT (BUKAN TERUSAN ATAU BALASAN)========================
	public function terima_surat_masuk()
	{
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');

		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/surat_dan_berkas_masuk/';	
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						//}else if($key=='posisi_surat_terakhir'){
						//	array_push($kiriman,"Sekretariat BKD");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman[12]=$upload1[0];
					$kiriman[13]=$upload2[0];
					if($kiriman[12]) {$kiriman[14]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[14]=NULL;}
					if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[15]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[29]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_masuk';
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
					//print_r($kiriman);
					//Persiapan notifikasi
					
					if($hasil_insersi_surat_berkas){
						$counter_table='tbcounter_notifikasi';
						$kolom_rujukan['nama_kolom']='idcounter_notifikasi';
						$kolom_rujukan['nilai']=1;//untuk nama_counter: counter surat masuk
						$kolom_target='nilai_counter';
						$this->model_frommyframework->naikkan_counter_notifikasi($counter_table,$kolom_rujukan,$kolom_target);
						/*
						//baca counter terakhir
						$nilai_counter_terakhir=array();
						$nilai_counter_terakhir=$this->model_frommyframework->pembaca_nilai_kolom_tertentu($counter_table,$kolom_rujukan,$kolom_target);
						$nilai_counter_terakhir_berikut=$nilai_counter_terakhir[0]+1;
						//alert("NILAI COUNTER TERAKHIR: ".implode('  '.$nilai_counter_terakhir));

						//masukkan nilai counter berikut
						$data[$kolom_target]=$nilai_counter_terakhir_berikut;
						//alert("NILAI COUNTER TERAKHIR BERIKUT: ".$nilai_counter_terakhir_berikut);
						$this->model_frommyframework->update_style_CI_no_alert($counter_table,$kolom_rujukan,$data);
						*/
					}
	
				}
	
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Sekretariat BKD Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			}

		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
		*/
	
	}

	public function sekretariat_unggahsuratbaru_balasan()
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">UNGGAH SURAT DAN BERKAS BARU</span>";
		$tabel="surat_keluar";
		$coba=array();
		$id='idsurat_keluar';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}

		
		$coba[6][0]='combo_database';
		$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[7][0]='hidden';
		$coba[7][7]='ASN internal';
		
		$coba[8][0]='hidden';
		$coba[8][7]='BADAN KEPEGAWAIAN DAERAH';
		$coba[9][0]='hidden';
		$coba[9][7]='Ruang Kaban';
		//$coba[10][0]='combo_manual';
		//$coba[10][7]=array('Sekretaris Badan','Kasubbag Program','Kasubbag Keuangan','Kasubbag Umum, Kepegawaian');
		$coba[11][0]='hidden';
		$coba[11][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));

		$coba[12][0]='file';
		$coba[13][0]='file';

		$coba[12][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Surat</span>';
		$coba[13][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Berkas Pendukung</span>';

		$coba[14][0]='hidden';
		$coba[15][0]='hidden';

		$coba[16][4]='';
		$coba[16][6]='<b>Diteruskan ke</b>';
		$coba[16][0]='combo_database';
		$coba[16][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[17][0]='area';
		$coba[18][4]='';
		$coba[18][0]='combo_database';
		$coba[18][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[19][0]='hidden';
		$coba[20][0]='hidden';
		$coba[21][0]='hidden';
		$coba[22][0]='hidden';
		$coba[23][0]='hidden';
		$coba[24][0]='hidden';

		$coba[25][7]='Ruang Kaban';
		$coba[28][0]='hidden';

		$coba[26][0]='combo_manual';
		$coba[26][7]=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[26][8]=3;

		$coba[27][0]='combo_database';
		$coba[27][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[27][8]='Yang Lain (Others)';
		//1111
		/*
		UNTUK DIPAHAMI ULANG:
		case ("upload") :
			//echo "submenu_userpelanggan";	
			$oke=$_SESSION['perekam1'];
			$nama=$_GET['nama'];
			$lokasi=$_GET['lokasi'];
			echo "HKJHKJHASK";
			foreach ($oke as $isi) {
			if (!(($isi[type]=='button') || ($isi[type]=='button_ajax') || ($isi[type]=='submit'))) {echo "<br />".$_POST[$isi[nama_komponen]];}}
			upload($nama,$lokasi,'txt,jpg,jpeg,gif,png');
		*/
		//$coba[9][6]='target_surat'; //ini label
		$target_action='';
		$komponen=$coba;
		$atribut_form=" id=\"form_unggah_berkas_balasan\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" ";
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		//jika tombol[0] bertipe 'submit_multi' maka $event berfungsi sebagai pengisi alamat target action yang mau dituju dan berupa array target action.
		//catatn, untuk $atribut masih error untuk digunakan di tombol[0]. 
		$tombol[0]=array('submit_multi_2','submit_nama_komponen_2','btn btn-primary','id-baru-2','',array(site_url('/Frontoffice/terima_arsip_surat_keluar'),$this->config->item('bank_data')."/index.php/Frontoffice/terima_arsip_surat_keluar_bidang"),'','Unggah','');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//echo "OK BRO SIAP-SIAP";
		echo "
			<!--Skrip untuk menampilkan modal saat window onload-->
			<script type=\"text/javascript\">
				$(document).ready(function(){
					let loading_api_balasan_2 = $(\"#pra_api_balasan_2\");
					let tampilkan_api_balasan_2 = $(\"#penampil_api_balasan_2\");
					tampilkan_api_balasan_2.hide();
					loading_api_balasan_2.fadeIn();
					";
					$token=$this->enkripsi->enkapsulasiData('andisinra');
					echo"
					$.post(\"".$this->config->item('bank_data')."/index.php/Frontoffice/read_alamat_web_umum_balasan/".$token."/Frontoffice/terima_surat_masuk\",{ data:\"okbro\"},
					function(data,status){
					loading_api_balasan_2.fadeOut();
					tampilkan_api_balasan_2.html(data);
					tampilkan_api_balasan_2.fadeIn(2000);
					});
				});
			</script>
		";

		echo "
			<div id='pra_api_balasan_2' style='width:65%;' align='center' >
			<div class=\"progress\" style=\"margin-top:50px; height:20px\">
			<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
			mohon tunggu data dari bank data...
			</div>
			</div>
			</div>
			</center>

			<div id=penampil_api_balasan_2 align=\"center\" style='width:100%;'></div>
		";
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
	}


	public function sekretariat_unggahsuratbaru()
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">UNGGAH SURAT DAN BERKAS BARU</span>";
		$tabel="surat_keluar";
		$coba=array();
		$id='idsurat_keluar';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}

		
		$coba[6][0]='combo_database';
		$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[7][0]='hidden';
		$coba[7][7]='ASN internal';
		
		$coba[8][0]='hidden';
		$coba[8][7]='BADAN KEPEGAWAIAN DAERAH';
		$coba[9][0]='hidden';
		$coba[9][7]='Ruang Kaban';
		//$coba[10][0]='combo_manual';
		//$coba[10][7]=array('Sekretaris Badan','Kasubbag Program','Kasubbag Keuangan','Kasubbag Umum, Kepegawaian');
		$coba[11][0]='hidden';
		$coba[11][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));

		$coba[12][0]='file';
		$coba[13][0]='file';

		$coba[12][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Surat</span>';
		$coba[13][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Berkas Pendukung</span>';

		$coba[14][0]='hidden';
		$coba[15][0]='hidden';

		$coba[16][4]='';
		$coba[16][6]='<b>Diteruskan ke</b>';
		$coba[16][0]='combo_database';
		$coba[16][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[17][0]='area';
		$coba[18][4]='';
		$coba[18][0]='combo_database';
		$coba[18][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[19][0]='hidden';
		$coba[20][0]='hidden';
		$coba[21][0]='hidden';
		$coba[22][0]='hidden';
		$coba[23][0]='hidden';
		$coba[24][0]='hidden';

		$coba[25][7]='Ruang Kaban';
		$coba[28][0]='hidden';

		$coba[26][0]='combo_manual';
		$coba[26][7]=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[26][8]=3;

		$coba[27][0]='combo_database';
		$coba[27][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[27][8]='Yang Lain (Others)';
		
		/*
		UNTUK DIPAHAMI ULANG:
		case ("upload") :
			//echo "submenu_userpelanggan";	
			$oke=$_SESSION['perekam1'];
			$nama=$_GET['nama'];
			$lokasi=$_GET['lokasi'];
			echo "HKJHKJHASK";
			foreach ($oke as $isi) {
			if (!(($isi[type]=='button') || ($isi[type]=='button_ajax') || ($isi[type]=='submit'))) {echo "<br />".$_POST[$isi[nama_komponen]];}}
			upload($nama,$lokasi,'txt,jpg,jpeg,gif,png');
		*/
		//$coba[9][6]='target_surat'; //ini label
		$target_action='';
		$komponen=$coba;
		$atribut_form=" id=\"form_unggah_berkas\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" ";
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		//jika tombol[0] bertipe 'submit_multi' maka $event berfungsi sebagai pengisi alamat target action yang mau dituju dan berupa array target action.
		//catatn, untuk $atribut masih error untuk digunakan di tombol[0]. 
		$tombol[0]=array('submit_multi','submit_nama_komponen','btn btn-primary','id-baru','',array(site_url('/Frontoffice/terima_arsip_surat_keluar'),$this->config->item('bank_data')."/index.php/Frontoffice/terima_arsip_surat_keluar_bidang"),'','Unggah','');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//echo "OK BRO SIAP-SIAP";
		echo "
			<!--Skrip untuk menampilkan modal saat window onload-->
			<script type=\"text/javascript\">
				$(document).ready(function(){
					let loading_api_balasan = $(\"#pra_api_balasan\");
					let tampilkan_api_balasan = $(\"#penampil_api_balasan\");
					tampilkan_api_balasan.hide();
					loading_api_balasan.fadeIn();
					";
					$token=$this->enkripsi->enkapsulasiData('andisinra');
					echo"
					$.post(\"".$this->config->item('bank_data')."/index.php/Frontoffice/read_alamat_web_umum/".$token."/Frontoffice/terima_surat_masuk\",{ data:\"okbro\"},
					function(data,status){
					loading_api_balasan.fadeOut();
					tampilkan_api_balasan.html(data);
					tampilkan_api_balasan.fadeIn(2000);
					});
				});
			</script>
		";

		echo "
			<div id='pra_api_balasan' style='width:65%;' align='center' >
			<div class=\"progress\" style=\"margin-top:50px; height:20px\">
			<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
			mohon tunggu data dari bank data...
			</div>
			</div>
			</div>
			</center>

			<div id=penampil_api_balasan align=\"center\" style='width:100%;'></div>
		";
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
	}

	public function tesevent(){
		alert('ini tes event bro');
	}

	public function terima_arsip_surat_keluar()
	{//1111
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');

		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/surat_keluar_arsip/';	
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						}else if($key=='posisi_surat_terakhir'){
							array_push($kiriman,"Kesejahteraan dan Kinerja Pegawai");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman[12]=$upload1[0];
					$kiriman[13]=$upload2[0];
					if($kiriman[12]) {$kiriman[14]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[14]=NULL;}
					if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[15]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[28]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_keluar';
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
					//print_r($kiriman);
					//Persiapan notifikasi
					/*
					if($hasil_insersi_surat_berkas){
						$tabel_notifikasi='tbnotifikasi';
						$notifikasi=array();
						$notifikasi[1]=$data_post['pengirim']['nilai'];
						$notifikasi[2]=$kiriman[29];
						$notifikasi[3]='masuk';
						$notifikasi[4]=$data_post['timestamp_masuk']['nilai'];
						$notifikasi[5]='';
						$this->general_insertion_controller($notifikasi,$tabel_notifikasi);
					}*/
	
				}
	
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Sekretariat BKD Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('admin_frontoffice/dashboard',$data_upload);
			}
		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
		*/
	
	}

	//========================END TERIMA SURAT MASUK DARI SEKRETARIAT (BUKAN TERUSAN ATAU BALASAN)====================

	public function tampilkan_tombol_baca_surat(){
		//Awal 
/*
		echo "
			<!-- Content Row -->
			<div class=\"row\" style=\"width:100%;display:flex;flex-wrap:wrap;justify-content: center;\">

			<!-- Earnings (Monthly) Card Example -->
			<div class=\"col-xl-3 col-md-6 mb-4\" style=\"width:270px;float:left;height:180px;\">
			<div class=\"card border-left-primary shadow h-100 py-2\">
				<div class=\"card-body\">
				<div class=\"row no-gutters align-items-center\">
					<div class=\"col mr-2\">
					<div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1\">Kepala Bidang</div>
					<div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1\">Drs. H.A.Harun</div>
					<div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1\">Nip 19650719 199208 1 001</div>
					</div>
					<div class=\"col-auto\">
					<!--<i class=\"fas fa-calendar fa-2x text-gray-300\"></i>-->
					</div>
				</div>
				</div>
			</div>
			</div>

			<!-- Earnings (Monthly) Card Example -->
			<div class=\"col-xl-3 col-md-6 mb-4\" style=\"width:270px;float:left;height:180px;\">
			<div class=\"card border-left-success shadow h-100 py-2\">
				<div class=\"card-body\">
				<div class=\"row no-gutters align-items-center\">
					<div class=\"col mr-2\">
					<div class=\"text-xs font-weight-bold text-success text-uppercase mb-1\">Kasubbid Kinerja Pegawai</div>
					<div class=\"text-xs font-weight-bold text-success text-uppercase mb-1\">Agustina, S.Kom</div>
					<div class=\"text-xs font-weight-bold text-success text-uppercase mb-1\">Nip 19740813 200801 2 010</div>
					</div>
					<div class=\"col-auto\">
					<!--<i class=\"fas fa-dollar-sign fa-2x text-gray-300\"></i>-->
					</div>
				</div>
				</div>
			</div>
			</div>

			<!-- Earnings (Monthly) Card Example -->
			<div class=\"col-xl-3 col-md-6 mb-4\" style=\"width:270px;float:left;height:180px;\">
			<div class=\"card border-left-info shadow h-100 py-2\">
				<div class=\"card-body\">
				<div class=\"row no-gutters align-items-center\">
					<div class=\"col mr-2\">
					<div class=\"text-xs font-weight-bold text-info text-uppercase mb-1\">Kasubbid Pensiun dan Cuti</div>
					<div class=\"text-xs font-weight-bold text-info text-uppercase mb-1\">Maemuna, S.E</div>
					<div class=\"text-xs font-weight-bold text-info text-uppercase mb-1\">Nip 19671231 199203 2 038</div>
					</div>
					<div class=\"col-auto\">
					<!--<i class=\"fas fa-clipboard-list fa-2x text-gray-300\"></i>-->
					</div>
				</div>
				</div>
			</div>
			</div>

			<!-- Pending Requests Card Example -->
			<div class=\"col-xl-3 col-md-6 mb-4\" style=\"width:270px;float:left;height:180px;\">
			<div class=\"card border-left-warning shadow h-100 py-2\">
				<div class=\"card-body\">
				<div class=\"row no-gutters align-items-center\">
					<div class=\"col mr-2\">
					<div class=\"text-xs font-weight-bold text-warning text-uppercase mb-1\">Kasubbid Kesejahteraan dan Penghargaan</div>
					<div class=\"text-xs font-weight-bold text-warning text-uppercase mb-1\">Mirwan M, S.E, M.M</div>
					<div class=\"text-xs font-weight-bold text-warning text-uppercase mb-1\">Nip 19830522 200701 1 002</div>
					</div>
					<div class=\"col-auto\">
					<!--<i class=\"fas fa-comments fa-2x text-gray-300\"></i>-->
					</div>
				</div>
				</div>
			</div>
			</div>
			</div>

			<!-- Content Row -->

			";
*/
		
		//Tombol baca surat
		echo "
			<style>
				.kotak{
					width:18%;
					height:200px;
					min-width:100px;
				}
				@media screen and (max-width: 480px) {
					.kotak{
						width:100%;
						height:100px;
						margin-top:5px;
						font-size:14px;
					}
				}
			</style>
		";
		echo "<hr>";
		echo "
		<button class=\"btn btn-lg btn-info shadow-sm kotak\" id=\"baca_surat_masuk\"><i class=\"fas fa-envelope-open fa-lg text-white-100\"></i>
		<span id=\"counter_surat_masuk_masuk_besar\" class=\"badge badge-danger badge-counter\" style=\"margin-left:-15px;top:-10px;\"></span>
		<br>Baca Surat Masuk <br>[INBOX]</button>
		<button style=\"cursor:pointer;color:white;\" class=\"kotak d-sm-inline-block btn btn-lg btn-success shadow-sm\" id=\"buat_catatan\" ><i class=\"fas fa-file-alt fa-lg text-white-100\"></i><br>Buat Dokumen <br>[MiniOffice]</button>
		<!-- Script untuk pemanggilan ajax -->
		<script>      
		$(document).ready(function(){
			var tampilkan = $(\"#counter_surat_masuk_masuk_besar\");
			$.post('".site_url('/Frontoffice/baca_counter_surat_masuk/echo')."',{ data:\"okbro\"},
			function(data,status){
			tampilkan.html(data);
			});
		});
		</script> 
			  
		<script>      
          $(document).ready(function(){
            $(\"#baca_surat_masuk\").click(function(){
              var loading = $(\"#pra_tabel\");
              var tampilkan = $(\"#penampil_tabel\");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('".site_url('/Frontoffice/tampilkan_tabel_new')."',{ data:\"okbro\"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
		  </script> 
		  
		  <script>      
			$(document).ready(function(){
				$(\"#buat_catatan\").click(function(){
					var loading = $(\"#pra_tabel\");
					var tampilkan = $(\"#penampil_tabel\");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('".site_url('/Frontoffice/iframe_editor')."',{ data:\"okbro\"},
					function(data,status){
						loading.fadeOut();
						tampilkan.html(data);
						tampilkan.fadeIn(2000);
					});
				});
			  });
			</script>
		";

		//Tombol baca surat
		echo "
		<button class=\"d-sm-inline-block btn btn-lg btn-warning shadow-sm kotak\" id=\"arsip_keluar_bidang\"><i class=\"fas fa-folder-open fa-lg text-white-100\"></i>
		<span id=\"counter_surat_masuk_arsip_besar\" class=\"badge badge-danger badge-counter\" style=\"margin-left:-15px;top:-10px;\"></span>
		<br>Acara Hari Ini <br>[Agenda] </i></button>

		<script>      
		$(document).ready(function(){
			var tampilkan = $(\"#counter_surat_masuk_arsip_besar\");
			$.post('".site_url('/Frontoffice/baca_counter_surat_arsip/echo')."',{ data:\"okbro\"},
			function(data,status){
			tampilkan.html(data);
			});
		});
		</script> 

		<!-- Script untuk pemanggilan ajax -->
		<script>      
          $(document).ready(function(){
            $(\"#arsip_keluar_bidang\").click(function(){
              var loading = $(\"#pra_tabel\");
              var tampilkan = $(\"#penampil_tabel\");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('".site_url('/Frontoffice/baca_agenda')."',{ data:\"okbro\"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 
		";

		//Tombol baca pesan/nota
		echo "
		<button class=\"d-sm-inline-block btn btn-lg btn-primary shadow-sm kotak\" id=\"nota_masuk\"><i class=\"fas fa-sticky-note fa-lg text-white-100\"></i>
		<span id=\"counter_nota_masuk\" class=\"badge badge-danger badge-counter\" style=\"margin-left:-15px;top:-10px;\"></span>
		<br>Buat | Baca Pesan <br>[Nota] </i></button>

		<script>      
		$(document).ready(function(){
			var tampilkan = $(\"#counter_nota_masuk\");
			$.post('".site_url('/Frontoffice/baca_nota_masuk/echo')."',{ data:\"okbro\"},
			function(data,status){
			tampilkan.html(data);
			});
		});
		</script> 

		<!-- Script untuk pemanggilan ajax -->
		<script>      
          $(document).ready(function(){
            $(\"#nota_masuk\").click(function(){
              var loading = $(\"#pra_tabel\");
              var tampilkan = $(\"#penampil_tabel\");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('".site_url('/Frontoffice/iframe_editor_note')."',{ data:\"okbro\"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 
		";

		//Tombol buat laporan
		echo "
		<button class=\"d-sm-inline-block btn btn-lg btn-danger shadow-sm kotak\" id=\"buat_laporan_9\"><i class=\"fas fa-file-download fa-lg text-white-100\"></i>
		<span id=\"counter_nota_masuk\" class=\"badge badge-danger badge-counter\" style=\"margin-left:-15px;top:-10px;\"></span>
		<br>Laporan PDF | Excel  <br>[Report] </i></button>

		<!-- Script untuk pemanggilan ajax -->
		<script>      
          $(document).ready(function(){
            $(\"#buat_laporan_9\").click(function(){
              var loading = $(\"#pra_tabel\");
              var tampilkan = $(\"#penampil_tabel\");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('".site_url('/Frontoffice/cetak_laporan')."',{ data:\"okbro\"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 
		";
		echo "<hr>";
	}

	public function pesan_rencana_konstruksi(){
		echo "
			<div class=\"alert alert-info\">
				<i class=\"fas fa-info-circle fa-lg text-white-100\"></i>
				<strong>Mohon maaf</strong> Lalu lintas nota pesan antar pimpinan dan bidang sedang dalam rencana konstruksi.
			</div>
		";
	}

	

    //===============TES OPEN PDF==================
    public function tesopenpdf($src_ok){
		$src_ok=explode("/",$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($src_ok)));
		$src_berkas=NULL;
		foreach($src_ok as $key=>$k){
			if($key!==0){$src_berkas=$src_berkas."/".$k;}
		}
		//echo "INI DIA BRO src_ok: ".$src_berkas;
		if($src_berkas){
			echo "<iframe id=\"target_pdf\" name=\"target_pdf\" src=\"".base_url($src_berkas)."\" style=\"left:5%;right:5%;top:5%;bottom:5%;border:0px solid #000;position:absolute;width:90%;height:500px;\"></iframe>";
		}else {
			echo "MAAF TIDAK ADA FILE YANG DIUNGGAH";
		}
    }

    //===============END TES OPEN PDF==============

	public function tampilkan_tabel(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter');
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_masuk_frontoffice($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_masuk order by idsurat_masuk desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}	

	public function tampilkan_tabel_surat_keluar(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>2),$kolom_target='nilai_counter');
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_keluar($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_keluar order by idsurat_keluar desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}	

	public function tampilkan_tabel_surat_terusan(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>3),$kolom_target='nilai_counter');
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_terusan($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_terusan order by idsurat_terusan desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}	

	public function tampilkan_tabel_surat_balasan(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->model_frommyframework->reset_counter_notifikasi($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>4),$kolom_target='nilai_counter');
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_balasan($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_balasan_tamupegawai order by idsurat_balasan desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}

	/*
	public function tampilkan_tabel_surat_terusan_di_akun_tamu(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_terusan($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_terusan order by idsurat_terusan desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}
	*/
	
	public function tes71(){
		echo "OK BRO MASUK jdkasjdka";
	}
	
	public function gerbang($pilihan){
		switch ($pilihan) {
			case ("rincian_pegawai_table_tab") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				echo "<h5>Rincian Data Pegawai</h5>";
				$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from identpeg where nipbaru=".$json->nipbaru,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
			break;

			case ("hapus_aja_tapi_ingat_peringatan_dulu") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				$kolom=$json->nama_kolom_id;
				echo "<h5>Apakah anda benar-benar ingin menghapus data?</h5>";
				echo "
					<form action=\"".site_url('Frontoffice/hapus_data')."\" method='post'>
					<input type='hidden' name='id_hapus' id='id_hapus' value=".$json->$kolom.">
					<input type='hidden' name='nama_tabel' id='nama_tabel' value=".$json->nama_tabel.">
					<button type=\"submit\" class=\"btn btn-info\" id=\"tombol_hapus\">Hapus</button>
					</form> 
				";
				//echo "<iframe id=\"target_hapus\" name=\"target_hapus\" src=\"\" style=\"border:0px solid #000;width:0;height:0\"></iframe>";
	
			break;

			case ("rincian_penampil_tabel_rincian") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$kolom=$json->nama_kolom_id;
				$surat=$this->user_defined_query_controller_as_array($query="select * from ".$json->nama_tabel." where ".$json->nama_kolom_id."=".$json->$kolom,$token="andisinra");
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN DATA</span>";
					$tabel=$json->nama_tabel;
					$coba=array();
					$id=$json->nama_kolom_id;
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					//print_r($surat);
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						//$coba[$key][4]=' readonly ';
					}

					/*
					$coba[6][0]='combo_database';
					$coba[6][8]=$coba[6][7];
					$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					
					$coba[7][0]='combo_database';
					$coba[7][8]=$coba[7][7];
					$coba[7][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					

					$coba[17][0]='area';
					$coba[18][7]='dibaca';
					$coba[20][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					*/

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					/*
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[14][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					*/
					//$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					//$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					$tombol[2]=array('button','submit','btn btn-primary','submit','','','','Ok','');
					//$tombol[3]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Unggah Surat Balasan...','Unggah Surat Balasan',"Frontoffice/frontoffice_unggahberkas_surat_masuk");
					
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/teruskan_surat/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					$this->session->set_userdata('modal','ok');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;

			case ("edit_penampil_tabel_edit") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$kolom=$json->nama_kolom_id;
				$surat=$this->user_defined_query_controller_as_array($query="select * from ".$json->nama_tabel." where ".$json->nama_kolom_id."=".$json->$kolom,$token="andisinra");
				//echo "INI PASSWORD: ".$surat[0]['password'];
				if(!$surat){
					alert('Data yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">EDIT DATA</span>";
					$tabel=$json->nama_tabel;
					$coba=array();
					$id=$json->nama_kolom_id;
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected
					$j=0;
					foreach($surat[0] as $key=>$unit){
						is_string($key)?$surat_keyNo_isiString_buffer[$j]=$key:NULL;
						$j++;
					}
					$j=0;
					foreach($surat_keyNo_isiString_buffer as $key=>$unit){
						$surat_keyNo_isiString[$j]=$unit;
						$j++;
					}

					//reset form sebelum dibuka:
					//print_r($surat_keyNo_isiString);

					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						//$coba[$key][7]=$surat_keyNo_isiString[$key];
						$surat_keyNo_isiString[$key]=='password'?$coba[$key][4]=' readonly ':NULL;
					}

					/*
					$coba[6][0]='combo_database';
					$coba[6][8]=$coba[6][7];
					$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					
					$coba[7][0]='combo_database';
					$coba[7][8]=$coba[7][7];
					$coba[7][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					

					$coba[17][0]='area';
					$coba[18][7]='dibaca';
					$coba[20][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					*/

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					/*
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[14][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					*/
					//$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					//$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Perbaharui data','');
					//$tombol[3]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Unggah Surat Balasan...','Unggah Surat Balasan',"Frontoffice/frontoffice_unggahberkas_surat_masuk");
					
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/update_data_cruid/".$tabel;//general_update_controller($kiriman,$tabel)
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					$this->session->set_userdata('modal','ok');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;
			
			//Bagian ini adalah fungsi yang bereaksi terhadap tombol "verifikasi" di halaman admin frontoffice. Memunculkan rincian surat
			//dan memiliki tombol teruskan surat yang memicu fungsi persiapan yaitu fungsi teruskan_surat().
			case ("rincian_penampil_tabel") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$surat=$this->user_defined_query_controller_as_array($query="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$token="andisinra");
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN SURAT DAN BERKAS</span>";
					$tabel="surat_masuk";
					$coba=array();
					$id='idsurat_masuk';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					//print_r($surat);
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						//$coba[$key][4]=' readonly ';
					}

					/*
					$coba[6][0]='combo_database';
					$coba[6][8]=$coba[6][7];
					$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					
					$coba[7][0]='combo_database';
					$coba[7][8]=$coba[7][7];
					$coba[7][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					*/

					$coba[17][0]='area';
					$coba[18][7]='dibaca';
					$coba[20][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[14][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					//Sementara disable
					//$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan','');
					//$tombol[3]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Unggah Surat Balasan...','Unggah Surat Balasan',"Frontoffice/sekretariat_unggahsuratbaru_balasan");
					
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/teruskan_surat/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					$this->session->set_userdata('modal','ok');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;
			case ("rincian_penampil_tabel_surat_keluar") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$surat=$this->user_defined_query_controller_as_array($query="select * from surat_keluar where idsurat_keluar=".$json->idsurat_keluar,$token="andisinra");
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN SURAT DAN BERKAS</span>";
					$tabel="surat_keluar";
					$coba=array();
					$id='idsurat_keluar';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					//print_r($surat);
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						$coba[$key][4]=' readonly ';
					}

					/*
					$coba[6][0]='combo_database';
					$coba[6][8]=$coba[6][7];
					$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					
					$coba[7][0]='combo_database';
					$coba[7][8]=$coba[7][7];
					$coba[7][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					*/

					//$coba[17][0]='area';
					$coba[18][7]='dibaca';
					$coba[20][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[14][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					//$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan','');
					//$tombol[3]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Unggah Surat Balasan...','Unggah Surat Balasan',"Frontoffice/frontoffice_unggahberkas_surat_masuk");
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/teruskan_surat/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					$this->session->set_userdata('modal','ok');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;
			case ("rincian_penampil_tabel_terusan") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				
				$surat=$this->user_defined_query_controller_as_array($query="select * from surat_terusan where idsurat_terusan=".$json->idsurat_terusan,$token="andisinra");
				//print_r($surat);
				
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN SURAT DAN BERKAS</span>";
					$tabel="surat_terusan";
					$coba=array();
					$id='idsurat_terusan';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						$coba[$key][4]=' readonly ';
					}

					
					//$coba[17][7]='Sekretariat BKD';
					$coba[21][7]='dibaca';
					$coba[23][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					$coba[30][4]='';
					$coba[30][0]='combo_database';
					$coba[30][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[30][8]=$surat[0][28];
					$coba[20][4]='';
					$coba[20][0]='area';
					$coba[21][4]='';
					$coba[21][0]='combo_database';
					$coba[21][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[19][4]='';
					$coba[19][6]='<b>Diteruskan ke</b>';
					$coba[19][0]='combo_database';
					$coba[19][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[17][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[18][7]));
					$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					
					//sementara ini dipending
					//$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan','');
					//$tombol[3]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Unggah Surat Balasan...','Unggah Surat Balasan',"Frontoffice/frontoffice_unggahberkas");
					
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/teruskan_surat/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					//$this->session->set_userdata('teks_modal','Surat dan berkas sedang dimuat ke memori');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
				
			break;
			case ("rincian_penampil_tabel_balasan") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$surat=$this->user_defined_query_controller_as_array($query="select * from surat_balasan_tamupegawai where idsurat_balasan=".$json->idsurat_balasan,$token="andisinra");
				//print_r($surat);
				
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN SURAT DAN BERKAS BALASAN SEKRETARIAT</span>";
					$tabel="surat_balasan_tamupegawai";
					$coba=array();
					$id='idsurat_balasan';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						$coba[$key][4]=' readonly ';
					}
					
					$coba[8][0]='combo_database';
					$coba[8][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[8][8]='Kepala BKD';

					$coba[9][0]='combo_database';
					$coba[9][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[9][8]='ASN internal';

					$coba[10][0]='combo_database';
					$coba[10][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[10][8]='Yang Lain (Others)';

					$coba[11][0]='combo_database';
					$coba[11][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[11][8]='Yang Lain (Others)';

					$coba[12][0]='combo_database';
					$coba[12][7]=array("nama_subbidang","nama_subbidang",'subbidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[12][8]='Yang Lain (Others)';

					$coba[13][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					$coba[13][4]='readonly';

					//$coba[18][4]='';
					$coba[18][6]='<b>Diteruskan ke</b>';
					$coba[18][0]='combo_database';
					$coba[18][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					
					$coba[19][0]='area';
					$coba[20][4]='';
					$coba[20][0]='combo_database';
					$coba[20][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
								
					$coba[27][7]='Sekretariat BKD';
					
					$coba[28][0]='combo_manual';
					$coba[28][7]=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[28][8]=3;

					$coba[29][0]='combo_database';
					$coba[29][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[29][8]='Yang Lain (Others)';

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[16][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[17][7]));
					$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					
					//sementara ini dipending:
					//$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan','');
					//$tombol[3]=array('button_ajax_unggahberkas','button13','btn btn-primary','button13','','myModal_unggah_surat','Unggah Surat Balasan...','Unggah Surat Balasan',"Frontoffice/frontoffice_unggahberkas");
					//tutup tombol[2] karena tamu atau pegawai bisa mengunduh surat menggunakan tabel_terusan ini, mereka bisa melihat list surat terusan berdasarkan idtamu atau nip mereka
					//$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan','');
					
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/teruskan_surat/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					//$this->session->set_userdata('teks_modal','Surat dan berkas sedang dimuat ke memori');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;
			case ("edit_penampil_tabel") :
				echo "OK BRO MASUK EDIT";
			break;
			case ("tes_penampil_tabel_perhalaman") :
				echo "OK BRO MASUK EDIT";
			break;
			
		}
	}

	//Fungsi ini untuk meload surat dan berkas ke memory dengan menyematkannya ke $_POST
	//Kemudian menyajikan tombol untuk mengirim file yang sudah di load serta memberi informasi jika ukuran melampaui batas.
	public function teruskan_surat($sekretariat=NULL){

		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
		$hash=$this->session->userdata('hash');
		if(($user!==FALSE)&&($str==$hash)){
		*/

			if(isset($_POST['data_nama'])){
				$data_post=array();
				$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
				$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');
				//print_r($data_post);

				//Ambil file untuk diteruskan:

				//PERHATIKAN INI, KALAU MELAKUKAN DEBUG, HAPUS error_reporting()
				error_reporting(0);
				if($data_post['direktori_surat_masuk']['nilai']){
					$handle_surat = file_get_contents($data_post['direktori_surat_masuk']['nilai']);
					$handle_enkrip_surat=$this->enkripsi->enkripSimetri_data($handle_surat);
					$handle_hex_surat=$this->enkripsi->strToHex($handle_enkrip_surat);
				}else{
					$handle_hex_surat=NULL;
				}
		
				if($data_post['direktori_berkas_yg_menyertai']['nilai']){
					$handle_berkas = file_get_contents($data_post['direktori_berkas_yg_menyertai']['nilai']);
					$handle_enkrip_berkas=$this->enkripsi->enkripSimetri_data($handle_berkas);
					$handle_hex_berkas=$this->enkripsi->strToHex($handle_enkrip_berkas);
				}else {
					$handle_hex_berkas=NULL;
				}

				$data_post=array_merge($data_post,array('handle_hex_surat'=>array('nilai'=>$handle_hex_surat,'file'=>NULL)));
				$data_post=array_merge($data_post,array('handle_hex_berkas'=>array('nilai'=>$handle_hex_berkas,'file'=>NULL)));
				//print_r($data_post);

				//Enkrip data_post
				$data_post_enkrip=$this->enkripsi->enkripSimetri_data(serialize($data_post));
				$data_post_enkrip_hex=$this->enkripsi->strToHex($data_post_enkrip);
				$data['data_post_enkrip_hex']=$data_post_enkrip_hex;

				$this->load->view('admin_frontoffice/dashboard',$data);

				/*
				echo "INI UKURAN POST: ".strlen($data_post_enkrip_hex)."<br>";
				$ok=trim(ini_get('post_max_size'),'M');
				$ok=$ok*1024*1024;
				echo "BATAS MAKSIMUM ADALAH: ".$ok;
				*/

				/*
				echo "<br> INI adalah nilai sehabis trim: ".$ok;
				if(strlen($data_post_enkrip_hex)>$ok) {alert('file anda melampaui batas upload\nbatas ukuran kirim file terkirim adalah 40M\nanda dapat menyampaikan ke admin server \nuntuk merubah nilai post_max_size pada PHP.ini');} else{
					echo "
					<form name=\"myform\" action=\"".site_url('Frontoffice/coba_kirim')."\" method=\"POST\">
						<input type=\"hidden\" name=\"data_post_enkrip_hex\" value=\"".$data_post_enkrip_hex."\">
						<button id=\"Link\" class=\"btn btn-primary\" onclick=\"document.myform.submit()\" >Kirim</button>
					</form>
					";
				}
				*/
			} else {
				!$sekretariat?alert('Tidak ada surat dan berkas yang hendak diteruskan'):alert('Penerusan surat atau berkas berhasil dilakukan');				
				$this->load->view('admin_frontoffice/dashboard');
			}
		/*
		}else{
			alert('Maaf Session anda kadaluarsa');
			redirect('Frontoffice/index');
		}
		*/
		
	} 
	
	//fungsi ini untuk jadi target pengiriman surat terusan dari sekretariat
	public function coba_kirim($terusan=NULL){

		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
		$hash=$this->session->userdata('hash');
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_post_enkrip_hex'])){
				$data_post_terima=$_POST['data_post_enkrip_hex'];

				//Dekrip dan uraikan:
				$data_post_terima=unserialize($this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($data_post_terima)));
				if($data_post_terima['handle_hex_surat']['nilai']){
					$handle_hex_surat=$data_post_terima['handle_hex_surat']['nilai'];
					$pasca_dekrip_surat=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_surat));
					$oksurat=file_put_contents("./public/surat_dan_berkas_terusan/".$data_post_terima['nama_file_surat']['nilai'], $pasca_dekrip_surat);
				}
				
				if($data_post_terima['handle_hex_berkas']['nilai']){
					$handle_hex_berkas=$data_post_terima['handle_hex_berkas']['nilai'];
					$pasca_dekrip_berkas=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_berkas));
					$okberkas=file_put_contents("./public/surat_dan_berkas_terusan/".$data_post_terima['nama_file_berkas']['nilai'], $pasca_dekrip_berkas);
				}
				if(isset($oksurat)){$data['pesan_kirim_surat']=$oksurat;}
				if(isset($okberkas)){$data['pesan_kirim_berkas']=$okberkas;}

				//Insersi ke tabel surat_terusan jika file surat atau berkas berhasil masuk, jika tidak maka jangan insersi.
				if(isset($oksurat) || isset($okberkas)){
					$buffer=array();
					foreach($data_post_terima as $key=>$k){
						if(!($key=='handle_hex_surat') && !($key=='handle_hex_berkas')){
							if($key=='timestamp_masuk'){
								array_push($buffer,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
							//}else if($key=='posisi_surat_terakhir'){
							//	array_push($buffer,"Sekretariat BKD");//sesuaikan jawaban ini dengan bidangnya, jika ini di sekretariat maka ganti dengan sekretariat BKD
							}else if($key=='direktori_surat_masuk') {
								array_push($buffer,str_replace('surat_dan_berkas_masuk','surat_dan_berkas_terusan',$k['nilai']));
							}else if($key=='direktori_berkas_yg_menyertai'){
								array_push($buffer,str_replace('surat_dan_berkas_masuk','surat_dan_berkas_terusan',$k['nilai']));
							}else{
								array_push($buffer,$k['nilai']);
							}
						}
					}
				$kiriman=array_merge(array(0=>NULL),$buffer);
				$tabel='surat_terusan';
				//print_r($kiriman);
				$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);//ggg3
				if($hasil_insersi_surat_berkas){
					$counter_table='tbcounter_notifikasi';
					$kolom_rujukan['nama_kolom']='idcounter_notifikasi';
					$kolom_rujukan['nilai']=3;//untuk nama_counter: counter surat masuk terusan
					$kolom_target='nilai_counter';
					$this->model_frommyframework->naikkan_counter_notifikasi($counter_table,$kolom_rujukan,$kolom_target);
				}
				$hasil_insersi_surat_berkas?$hasil_insersi_surat_berkas='okbro':$hasil_insersi_surat_berkas=NULL;
				redirect($_POST['asal_surat']."/".$hasil_insersi_surat_berkas);
				}
				$this->load->view('admin_frontoffice/dashboard/',$data);
			} else{
				$this->load->view('admin_frontoffice/dashboard');
			}
		//}


	}

	//fungsi ini untuk jadi target pengiriman surat balasan dari sekretariat
	public function frontoffice_index_balasan()
	{
		
		//PEMERIKSAAN SESSION INI DIHAPUS SEMENTARA, AGAR PENGIRIMAN SURAT BALASAN DARI SEKRETARIAT TIDAK MENGHARUSKAN SESSIOAN HARUS HIDUP (ADMIN AKRIF ATAU APLIKASI FRONTOFFICE TERBUKA)
		//KAPAN SAJA WALAU ADMINNYA PERGI ATAU TELAH MENUTUP APLIKASI, SEKRETARIAT TETAP BISA MENGIRIM SURAT BALASAN.
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');

		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/surat_dan_berkas_balasan/';	
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						}else if($key=='posisi_surat_terakhir'){
							array_push($kiriman,"Kesejahteraan dan Kinerja Pegawai");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman=array_merge(array(NULL),$kiriman);
					$kiriman[14]=$upload1[0];
					$kiriman[15]=$upload2[0];
					if($kiriman[14]) {$kiriman[16]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[16]=NULL;}
					if($kiriman[15]) {$kiriman[17]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[17]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[30]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_balasan_tamupegawai';
					//print_r($kiriman);
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);//ggg4
					$hasil_insersi_surat_berkas?$hasil_insersi_surat_berkas='okbro':$hasil_insersi_surat_berkas=NULL;
					//print_r($kiriman);
					//Persiapan notifikasi
					
					if($hasil_insersi_surat_berkas){
						$counter_table='tbcounter_notifikasi';
						$kolom_rujukan['nama_kolom']='idcounter_notifikasi';
						$kolom_rujukan['nilai']=4;//untuk nama_counter: counter surat masuk balasan
						$kolom_target='nilai_counter';
						$this->model_frommyframework->naikkan_counter_notifikasi($counter_table,$kolom_rujukan,$kolom_target);
					}
					redirect($this->config->item('base_domain')."/sekretariat-bkd/index.php/Frontoffice/teruskan_surat/".$hasil_insersi_surat_berkas);			
				}
				
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi BKD Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('index',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('index',$data_upload);
			}

		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
		*/
	}

	//fungsi ini sepertinya OBSELET, digantikan oleh coba_kirim().
	//Fungsi ini untuk menerima balasan kiriman surat dari sekretariat tetapi bukan surat yang datang dari upload form
	//tetapi datang dari fungsi file_get_contents().
	public function terima_balasan_surat_dari_sekretariat(){
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
		$hash=$this->session->userdata('hash');
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
				$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');

				//Terima kiriman file:
				if($data_post['handle_hex_surat']['nilai']){
					$handle_hex_surat=$data_post['handle_hex_surat']['nilai'];
					$pasca_dekrip_surat=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_surat));
					file_put_contents("./public/surat_dan berkas _balasan/".$data_post['nama_file_surat']['nilai'], $pasca_dekrip_surat);
				}
				
				if($data_post['handle_hex_berkas']['nilai']){
					$handle_hex_berkas=$data_post['handle_hex_berkas']['nilai'];
					$pasca_dekrip_berkas=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_berkas));
					file_put_contents("./public/surat_dan berkas _balasan/".$data_post['nama_file_berkas']['nilai'], $pasca_dekrip_berkas);
				}


				$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						}else if($key=='posisi_surat_terakhir'){
							array_push($kiriman,"Front Office BKD");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
				
				//print_r($kiriman);
				//print_r($data_post);
				$tabel='surat_balasan_sekretariat';
				$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
				//print_r($kiriman);
				if($hasil_insersi_surat_berkas){
					$tabel_notifikasi='tbnotifikasi';
					$notifikasi=array();
					$notifikasi[1]=$data_post['pengirim']['nilai'];
					$notifikasi[2]=$kiriman[29];
					$notifikasi[3]='masuk';
					$notifikasi[4]=$data_post['timestamp_masuk']['nilai'];
					$notifikasi[5]='';
					$notifikasi[6]='balasan dari sekretariat';
					$this->general_insertion_controller($notifikasi,$tabel_notifikasi);
				}
				$this->frontoffice_admin();
			} else {
				echo "GA MASUK BRO";
			}
		/*
		}else{
			alert('Maaf Session anda kadaluarsa');
		}
		*/
	}

	//======================================================BATAS SENDING SURAT KE SEKRETARIAT================================================
	public function frontoffice_index()
	{
		/*
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');
		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/surat_dan_berkas_masuk/';	
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="pdf,jpeg,gif,png,doc,bbc,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						}else if($key=='posisi_surat_terakhir'){
							array_push($kiriman,"Front Office BKD");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman[13]=$upload1[0];
					$kiriman[14]=$upload2[0];
					if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[15]=NULL;}
					if($kiriman[14]) {$kiriman[16]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[16]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[29]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_masuk';
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
					//print_r($kiriman);
					//Persiapan notifikasi
					/*
					if($hasil_insersi_surat_berkas){
						$tabel_notifikasi='tbnotifikasi';
						$notifikasi=array();
						$notifikasi[1]=$data_post['pengirim']['nilai'];
						$notifikasi[2]=$kiriman[29];
						$notifikasi[3]='masuk';
						$notifikasi[4]=$data_post['timestamp_masuk']['nilai'];
						$notifikasi[5]='';
						$this->general_insertion_controller($notifikasi,$tabel_notifikasi);
					}*/
				}
				
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi BKD Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('index',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('index',$data_upload);
			}

		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
		*/
	}

	public function frontoffice_admin(){ //xx1
		$user = $this->session->userdata('user_ruangkaban');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
		$hash=$this->session->userdata('hash');
		
		if(($user!==FALSE)&&($str==$hash)){
			$this->load->view('admin_frontoffice/dashboard');
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage");
		}
	}


	public function penampil_iframe_pdf($src='Frontoffice/pdf'){
		echo "<iframe id=\"target_pdf\" name=\"target_pdf\" src=\"".site_url($src)."\" style=\"left:5%;right:5%;top:5%;bottom:5%;border:0px solid #000;position:absolute;width:90%;height:70%\"></iframe>";
	}
	
	//Fungsi ini dipanggil oleh halaman index.php di view secara asinkron lewat iframe
	//ditampilkan setelah user selesai dan berhasil unggah surat atau berkas, sebagai nota unggah
	public function pdf($data_kiriman,$date_note){
			$data_kiriman=unserialize($this->enkripsi->hexToStr($data_kiriman));
			$date_note=unserialize($this->enkripsi->hexToStr($date_note));
			$data_key=array_keys($data_kiriman);
			$data=array(
				'NOTA UNGGAH SURAT DAN BERKAS',
				'RINCIAN SURAT DAN BERKAS YANG TERUNGGAH:'
			);
			foreach($data_key as $k){
				$temp=$k.": ".$data_kiriman[$k]['nilai'];
				array_push($data,$temp);
			}
			$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi BKD Provinsi Sulawesi Selatan');
			$data=array_merge($data,$date_note);
			cetak_tiket_pdf($data);
	}

	public function frontoffice_unggahberkas()
	{
		//$this->header_lengkap_bootstrap_controller();
		//echo "OK BRO MASUK SINI";
		$judul="<span style=\"font-size:20px;font-weight:bold;\">UNGGAH SURAT DAN BERKAS BALASAN</span>";
		$tabel="surat_masuk";
		$coba=array();
		$id='idsurat_masuk';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}
		
		$coba[6][0]='combo_database';
		$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[6][8]='Kepala BKD';

		$coba[7][0]='combo_database';
		$coba[7][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[7][8]='ASN internal';

		$coba[8][0]='combo_database';
		$coba[8][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[8][8]='BADAN KEPEGAWAIAN DAERAH';

		$coba[9][0]='combo_database';
		$coba[9][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[9][8]='Kesejahteraan dan Kinerja Pegawai';

		$coba[10][0]='combo_manual';
		$coba[10][7]=array('KEPALA BIDANG','KINERJA PEGAWAI','PENSIUN DAN CUTI','KESEJAHTERAAN DAN PENGHARGAAN','Yang Lain (Others)'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[10][8]='Yang Lain (Others)';

		$coba[11][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
		$coba[11][4]='readonly';

		$coba[12][0]='file';
		$coba[13][0]='file';

		$coba[12][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Surat</span>';
		$coba[13][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Berkas Pendukung</span>';

		$coba[14][0]='hidden';
		$coba[15][0]='hidden';

		$coba[16][4]='';
		$coba[16][6]='<b>Diteruskan ke</b>';
		$coba[16][0]='combo_database';
		$coba[16][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[17][0]='area';
		$coba[18][4]='';
		$coba[18][0]='combo_database';
		$coba[18][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[19][0]='hidden';
		$coba[20][0]='hidden';
		$coba[21][0]='hidden';
		$coba[22][0]='hidden';
		$coba[23][0]='hidden';
		$coba[24][0]='hidden';

		$coba[25][7]='Kesejahteraan dan Kinerja Pegawai';

		$coba[26][0]='combo_manual';
		$coba[26][7]=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[26][8]=3;

		$coba[27][0]='combo_database';
		$coba[27][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[27][8]='Yang Lain (Others)';
		
		$coba[28][0]='hidden';

		/*
		UNTUK DIPAHAMI ULANG:
		case ("upload") :
			//echo "submenu_userpelanggan";	
			$oke=$_SESSION['perekam1'];
			$nama=$_GET['nama'];
			$lokasi=$_GET['lokasi'];
			echo "HKJHKJHASK";
			foreach ($oke as $isi) {
			if (!(($isi[type]=='button') || ($isi[type]=='button_ajax') || ($isi[type]=='submit'))) {echo "<br />".$_POST[$isi[nama_komponen]];}}
			upload($nama,$lokasi,'txt,jpg,jpeg,gif,png');
		*/
		//$coba[9][6]='target_surat'; //ini label
		$target_action="Frontoffice/frontoffice_index/";
		$komponen=$coba;
		$atribut_form=" id=\"form_unggah_berkas\" target='targetkosong' method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" ";
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Unggah','');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//echo "OK BRO SIAP-SIAP";
		echo "
			<!--Skrip untuk menampilkan modal saat window onload-->
			<script type=\"text/javascript\">
				$(document).ready(function(){
					let loading_api_balasan = $(\"#pra_api_balasan\");
					let tampilkan_api_balasan = $(\"#penampil_api_balasan\");
					tampilkan_api_balasan.hide();
					loading_api_balasan.fadeIn();
					";
					$token=$this->enkripsi->enkapsulasiData('andisinra');
					echo"
					$.post(\"".$this->config->item('bank_data')."/index.php/Frontoffice/read_alamat_web_balasan/".$token."\",{ data:\"okbro\"},
					function(data,status){
					loading_api_balasan.fadeOut();
					tampilkan_api_balasan.html(data);
					tampilkan_api_balasan.fadeIn(2000);
					});
				});
			</script>
		";

		echo "
			<div id='pra_api_balasan' style='width:65%;' align='center' >
			<div class=\"progress\" style=\"margin-top:50px; height:20px\">
			<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
			mohon tunggu data dari bank data...
			</div>
			</div>
			</div>
			</center>

			<div id=penampil_api_balasan align=\"center\" style='width:100%;'></div>
		";

		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
		echo "
		<iframe name='targetkosong' width='0' height='0' frameborder='0'></iframe>";
	}
	
	public function frontoffice_unggahberkas_surat_masuk()
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">UNGGAH SURAT DAN BERKAS BALASAN</span>";
		$tabel="surat_masuk";
		$coba=array();
		$id='idsurat_masuk';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}
		
		$coba[6][0]='combo_database';
		$coba[6][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[6][8]='Kepala BKD';

		$coba[7][0]='combo_database';
		$coba[7][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[7][8]='ASN internal';

		$coba[8][0]='combo_database';
		$coba[8][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[8][8]='BADAN KEPEGAWAIAN DAERAH';

		$coba[9][0]='combo_database';
		$coba[9][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[9][8]='Kesejahteraan dan Kinerja Pegawai';

		$coba[10][0]='combo_manual';
		$coba[10][7]=array('KEPALA BIDANG','KINERJA PEGAWAI','PENSIUN DAN CUTI','KESEJAHTERAAN DAN PENGHARGAAN','Yang Lain (Others)'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[10][8]='Yang Lain (Others)';

		$coba[11][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
		$coba[11][4]='readonly';

		$coba[12][0]='file';
		$coba[13][0]='file';

		$coba[12][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Surat</span>';
		$coba[13][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Berkas Pendukung</span>';

		$coba[14][0]='hidden';
		$coba[15][0]='hidden';

		$coba[16][4]='';
		$coba[16][6]='<b>Diteruskan ke</b>';
		$coba[16][0]='combo_database';
		$coba[16][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[17][0]='area';
		$coba[18][4]='';
		$coba[18][0]='combo_database';
		$coba[18][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		
		$coba[19][0]='hidden';
		$coba[20][0]='hidden';
		$coba[21][0]='hidden';
		$coba[22][0]='hidden';
		$coba[23][0]='hidden';
		$coba[24][0]='hidden';

		$coba[25][7]='Kesejahteraan dan Kinerja Pegawai';

		$coba[26][0]='combo_manual';
		$coba[26][7]=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[26][8]=3;

		$coba[27][0]='combo_database';
		$coba[27][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[27][8]='Yang Lain (Others)';
		
		$coba[28][0]='hidden';

		/*
		UNTUK DIPAHAMI ULANG:
		case ("upload") :
			//echo "submenu_userpelanggan";	
			$oke=$_SESSION['perekam1'];
			$nama=$_GET['nama'];
			$lokasi=$_GET['lokasi'];
			echo "HKJHKJHASK";
			foreach ($oke as $isi) {
			if (!(($isi[type]=='button') || ($isi[type]=='button_ajax') || ($isi[type]=='submit'))) {echo "<br />".$_POST[$isi[nama_komponen]];}}
			upload($nama,$lokasi,'txt,jpg,jpeg,gif,png');
		*/
		//$coba[9][6]='target_surat'; //ini label
		$target_action="Frontoffice/frontoffice_index/";
		$komponen=$coba;
		$atribut_form=" id=\"form_unggah_berkas\" target='targetkosong' method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" ";
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Unggah','');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//echo "OK BRO SIAP-SIAP";
		echo "
			<!--Skrip untuk menampilkan modal saat window onload-->
			<script type=\"text/javascript\">
				$(document).ready(function(){
					let loading_api_balasan = $(\"#pra_api_balasan\");
					let tampilkan_api_balasan = $(\"#penampil_api_balasan\");
					tampilkan_api_balasan.hide();
					loading_api_balasan.fadeIn();
					";
					$token=$this->enkripsi->enkapsulasiData('andisinra');
					echo"
					$.post(\"".$this->config->item('bank_data')."/index.php/Frontoffice/read_alamat_web_balasan/".$token."\",{ data:\"okbro\"},
					function(data,status){
					loading_api_balasan.fadeOut();
					tampilkan_api_balasan.html(data);
					tampilkan_api_balasan.fadeIn(2000);
					});
				});
			</script>
		";

		echo "
			<div id='pra_api_balasan' style='width:65%;' align='center' >
			<div class=\"progress\" style=\"margin-top:50px; height:20px\">
			<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
			mohon tunggu data dari bank data...
			</div>
			</div>
			</div>
			</center>

			<div id=penampil_api_balasan align=\"center\" style='width:100%;'></div>
		";

		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
		echo "
		<iframe name='targetkosong' width='0' height='0' frameborder='0'></iframe>";
	}

	public function frontoffice_login($asal_login)
	{
		if($asal_login=='loginbyadmin'){
			$judul="<span style=\"font-size:20px;font-weight:bold;\">Login Admin Front Office</span>";
		}else{
			$judul="<span style=\"font-size:20px;font-weight:bold;\">Login Akun Tamu atau Pegawai</span>";
		}
		$tabel="user";
		$coba=array();
		$id='idadmin';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}

		$coba[1][0]='hidden';
		$coba[2][7]='';
		$coba[3][7]='';
		$coba[4][0]='hidden';
		$coba[5][0]='hidden';
		$coba[6][0]='hidden';
		$coba[7][0]='hidden';
		$coba[8][0]='hidden';
		$coba[9][0]='hidden';
		$coba[10][0]='hidden';
		$coba[11][0]='hidden';
		$coba[12][0]='hidden';

		$komponen=$coba;
		$atribut_form='';
		$array_option='';
		$atribut_table=array('table'=>"class=\"table\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Submit','');
		//$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		if($asal_login=='loginbyadmin'){
			$target_action="Frontoffice/frontoffice_responlogin/";
		}else{
			$target_action="Frontoffice/frontoffice_responlogin_akun/";
		}
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
	}

	public function frontoffice_responlogin()
	{	
		redirect('Frontoffice/frontoffice_admin');
		#$data['data']='Halaman Admin Front Office';
		#$this->load->view('underconstruction',$data);
	}

	public function frontoffice_responlogin_akun()
	{	
		$data['data']='Halaman Akun Tamu atau Pegawai';
		$this->load->view('underconstruction',$data);
	}

	public function frontoffice_register1()
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">REGISTER UNTUK TAMU</span>";
		$tabel="tamu";
		$coba=array();
		$id='idtamu';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}

		$coba[1][4]="required placeholder='wajib diisi...contoh: emailku@gmail.com'";

		$coba[2][4]="required placeholder='wajib diisi...'";
		$coba[4][4]="required placeholder='wajib diisi...'";
		$coba[7][4]="required placeholder='wajib diisi...'";

		$coba[3][0]='password';
		$coba[3][4]="required placeholder='wajib diisi...'";

		$coba[5][6]='<b>NIP (jika pegawai)</b>';

		$coba[8][0]='combo_database';
		$coba[8][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[8][6]='<b>Asal Satuan Kerja/OPD (jika pegawai)</b>';
		$coba[8][8]='Yang Lain (Others)';

		$coba[9][0]='combo_database';
		$coba[9][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[9][8]='Yang Lain (Others)';
		$coba[9][6]='<b>Asal Bidang (jika pegawai)</b>';

		$coba[10][0]='combo_database';
		$coba[10][7]=array("nama_subbidang","nama_subbidang",'subbidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[10][8]='Yang Lain (Others)';
		$coba[10][6]='<b>Asal Subbidang (jika pegawai)</b>';

		$coba[11][0]='combo_database';
		$coba[11][7]=array("nama_provinsi","nama_provinsi",'provinsi'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[11][8]='SULAWESI SELATAN';

		$coba[12][0]='combo_database';
		$coba[12][7]=array("nama_kabupaten","nama_kabupaten",'kabupaten'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[12][8]='Kota Makassar';

		$coba[13][0]='combo_database';
		$coba[13][7]=array("nama_kecamatan","nama_kecamatan",'kecamatan'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[13][8]='Yang Lain (Others)';

		$coba[14][0]='combo_database';
		$coba[14][7]=array("nama_kelurahan","nama_kelurahan",'kelurahan'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[14][8]='Yang Lain (Others)';

		$coba[15][0]='date';
		$coba[15][6]='<b>Password berlaku mulai</b>';
		$coba[16][0]='date';
		$coba[16][6]='<b>Password berlaku sampai</b>';
		$coba[17][0]='hidden';
		$coba[18][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
		$coba[18][4]='readonly';

		$coba[19][0]='file';
		$coba[19][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Foto</span>';

		$komponen=$coba;
		$atribut_form='';
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Submit','');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		$target_action="Frontoffice/frontoffice_indexregister/";
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
		
	}

	public function frontoffice_indexregister() {		
		
		if(isset($_POST['data_nama'])){
			//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
			
			$directory_relatif_file_upload='./public/image_tamu/';	
			$upload=array();
			$upload1=upload('direktori_foto', $folder=$directory_relatif_file_upload, $types="jpeg,gif,png,jpg");
			
			$data_post=array();
			$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
			$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
			//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
			//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
			//jadi biarkan saja demikian.
			

			//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
			//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
			$signature=$this->enkripsi->simplesignature_just_hashing($data_post);
			$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
			//print_r($data_post);echo "<br>";
			
			//BISMILLAH:
			//pindahkan isi $data_post ke $kiriman:
			$kiriman=array();
			foreach($data_post as $key=>$k){
				if($key=='password'){
					array_push($kiriman,password_hash($k['nilai'], PASSWORD_BCRYPT));
				}else if(($key=='pass_berlaku_mulai') || ($key=='pass_sampai_tgl')){
					array_push($kiriman,konversi_format_tgl_ttttbbhh_ke_hhbbtttt($k['nilai']));
				}else{
					array_push($kiriman,$k['nilai']);
				}
			}
			
			if($upload1[0]) {$kiriman[19]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[19]=$directory_relatif_file_upload."no-image.jpg";}
			//echo "<br> ini kiriman: ";
			//print_r($kiriman);
			//print_r($data_post);
			$tabel='tamu';
			$oke=$this->general_insertion_controller($kiriman,$tabel);
			//print_r($kiriman);
		
			if($oke){
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi BKD Provinsi Sulawesi Selatan');
				$data_upload['src_register']="Frontoffice/pdf_registrasi/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				$this->load->view('index',$data_upload);
			} else{
				$this->load->view('index');
			}
		} else {
			$this->load->view('index');
		}
		
	
	}

	//Fungsi ini dipanggil oleh halaman index.php di view secara asinkron lewat iframe
	//ditampilkan setelah user selesai dan berhasil unggah surat atau berkas, sebagai nota unggah
	public function pdf_registrasi($data_kiriman,$date_note){
		$data_kiriman=unserialize($this->enkripsi->hexToStr($data_kiriman));
		$date_note=unserialize($this->enkripsi->hexToStr($date_note));
		$data_key=array_keys($data_kiriman);
		$data=array(
			'NOTA REGISTRASI TAMU',
			'Yang bersangkutan telah registrasi, dengan rincian:'
		);
		foreach($data_key as $k){
			$temp=$k.": ".$data_kiriman[$k]['nilai'];
			array_push($data,$temp);
		}
		//$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi BKD Provinsi Sulawesi Selatan');
		$data=array_merge($data,$date_note);
		cetak_tiket_pdf_registrasi($data);
	} 






















//=========================================BATAS, SEMUA FUNGSI DIBAWAH ADALAH FUNGSI PUSTAKA YANG DIRENCANAKAN UNTUK DIPINDAHKAN KE LIBRRAY ATAU CORE==========================
//TES 
	//ALHAMDULILLAH SUKSES, YANG ARTINYA:
	//BISA KOMUNIKASI ANTAR FILE CONTROLLER, SALING KIRIM DATA DAN SEBAGAINYA
	//BISA KIRIM DATA TERENKRIPSI.
	public function tes1($ok="NOT YET",$ok1="NOT YET 2")
	{
		//$dataenkrip=$this->pengirim_terenkripsi_simetri('select nama from identpeg');
		//$tokenenkrip=$this->pengirim_terenkripsi_simetri('andisinra');
		/*
		$pageNum_Recordset1=1;
		$maxRows_Recordset1=100;
		$kolom_cari='nama';
		$key_cari='andi';*/

		/*
		//INI TES FUNGSI general_insertion_model
		$tabel='admin';
		$kiriman=array(28,'update@jskjs.com','','','cKamos');
		$kiriman=$this->strtohex(serialize($kiriman));
		*/
		/*
		$tabel='admin';
		$id=28;*/
		
		/*
		global $coba;
		$id=3;
		$tabel='admin';
		echo "<br>sebelum: <br>";
		$coba1=$this->penarik_key_controller('admin');
		$coba2=$this->penarik_key_controller('identpeg');
		print_r($coba1);
		echo "<br>INI BRO: ".$coba1[1];
		$i=0;
		
		//$coba=array();
		for($i=0;$i<sizeof($coba1);$i++){
			$coba_panel['admin'][$i][0]=$coba1[$i];
			//$coba_panel['agama'][$i][0]=$coba1[$i];
		}
		for($i=0;$i<sizeof($coba2);$i++){
			//$coba_panel['admin'][$i][0]=$coba1[$i];
			$coba_panel['identpeg'][$i][0]=$coba2[$i];
		}
		//for($i=0;$i<sizeof($coba1);$i++){
			$tabel_panel[0]='admin';
			$tabel_panel[1]='identpeg';
			$id_panel[0]=3;
			$id_panel[1]=195412311974041002;
		//}$id_panel,$tabel_panel
		//print_r($coba_panel);
		$this->session->set_userdata('coba_panel', $coba_panel);
		$tabel_panel=$this->enkripsi->enkapsulasiData($tabel_panel);
		$id_panel=$this->enkripsi->enkapsulasiData($id_panel);
		*/
		/*
		$kolom_value='idadmin';
		$kolom_label='username';
		$tabel='admin';
		$id=30;
		*/
		/*
		$tabel_panel[0]='admin';
		$tabel_panel[1]='identpeg';
		$tabel_panel=$this->enkripsi->enkapsulasiData($tabel_panel);
		redirect(site_url('frontoffice/tes2/'.$tabel_panel));
		*/
		//redirect(site_url('frontoffice/tes2/'.$pageNum_Recordset1.'/'.$maxRows_Recordset1.'/'.$tabel.'/'.$kolom_cari.'/'.$key_cari));

		/*
		TES AKSES KONFIGURASI DATABASE DI database.php di folder config
		echo $this->db->hostname;
		echo "<br>".$this->db->username;
		echo "<br>".$this->db->password;
		echo "<br>".$this->db->database;
		*/

		//print_r($this->penarik_key_string_ut_sebarang_query_controller($query='select * from admin'));
		echo "OK BRO MASUK";
		//echo "INI DATA name: ".$this->enkripsi->dekapsulasiData($_POST['data_json']);
		//echo "<br>INI DATA username: ".$_POST['username'];
		echo "<br>INI DATA proses: ".$_GET['proses'];
		echo "<br>INI DATA ok: ".$ok;
		echo "<br>INI DATA ok1: ".$ok1;
	}

	public function tes2()
	{
		//$tabel_panel=$this->enkripsi->dekapsulasiData($tabel_panel);
		//$id=$this->enkripsi->dekapsulasiData($id);
		/*
		echo "Nama Tabel: ".$tabel."<br>";
		$kiriman=unserialize($this->hextostr($kiriman));
		print_r($kiriman);
		*/
		/*
        echo "INI pageNum_Recordset1: ".$pageNum_Recordset1;
        echo "<br>INI maxRows_Recordset1: ".$maxRows_Recordset1;
        echo "<br>INI tabel: ".$tabel;
        echo "<br>INI kolom_cari: ".$kolom_cari;
		echo "<br>INI key_cari: ".$key_cari;
		*/
        //echo "<br>INI query_Recordset1: ".$query_Recordset1;
		//$datatodekrip=$this->penerima_terenkripsi_simetri($query_Recordset1,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'));
		//echo "<br>INI query_Recordset1: ".$datatodekrip;
		//$coba=$this->session->userdata('coba');
		//print_r($this->penarik_key_controller_panel($tabel_panel));
		//echo "<br>setelah: <br>";
		//global $coba;
		//print_r($coba);
		//var_dump($coba);
		//foreach ($coba as $row){echo "<br>".$row->nama;}

		$this->header_lengkap_bootstrap_controller();
		
		/*
		$array_option=array('ok'=>'bro','ok1'=>'bro1');
		$this->form_input('checkbox','tes_text','checkbox','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('checkbox','tes_text','checkbox disabled','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('number','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('checkbox','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('color','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('text','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_area('text_area','form-control','text_tes',$atribut="style=\"margin:20px\"");
		echo "<br>";
		$this->form_combo_manual('tes_combo','form-control','tes_combo',$atribut="style=\"margin:20px\"",$array_option,$selected);
		echo "<br>";
		*/
		//$this->form_combo_database_controller('tes_combo_database','form-control','tes_combo_database',"style=\"margin:20px\"",array('username','email'),'admin','noeng.hunter@gmail.com');
		
		/*
		//TES form_general_controller:
		$komponen=array('Username'=>'text','email'=>'email','keterangan'=>'area','Radio'=>'radio','Search'=>'search','Checkbox'=>'checkbox','Warna'=>'color','Range'=>'range','Image'=>'image','Bilangan'=>'number','Tanggal'=>'date','Kirim Kueri'=>'submit','Ulangi'=>'reset','Tombol'=>'button');
		$array_option=array('Onde-onde'=>'onde','Doko-doko'=>'doko','Beppa Apang'=>'apang');
		$judul='<center>UJI COBA FORM<center>';
		$this->session->set_userdata('perekam',array());
		$selected='Beppa Apang';
		$array_value_label_checkbox=array('bajabu', 'botting', 'tahu', 'bumi','kambing');
		$disable_checkbox=array('tahu', 'bumi');
		$array_value_label_radio=array('radiobajabu', 'radiobotting', 'radiotahu', 'radiobumi','radiokambing');
		$disable_radio=array('radiotahu', 'radiokambing');
		echo "<div style=\"width:70%;\">";
		$hasil=$this->form_general_controller($komponen,$atribut_form=" class=\"form-group\" ",$array_option,$atribut_table=array('table'=>" class=\"table table-hover\" ",'tr'=>'','td'=>''),$judul,$selected,$class='form-control',$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio);
		echo "</div>";
		print_r($hasil);
		*/
		
		/*
		$this->buat_komponen_form_controller($type='text',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='date',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='email',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='datetime-local',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='url',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='search',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='range',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='button',$nama_komponen='text1',$class='btn btn-warning',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='area',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='file',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='password',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='number',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='time',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='week',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='month',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='button_ajax2',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='checkbox',$nama_komponen='text1',$class='checkbox',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='radio',$nama_komponen='text1',$class='radio',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='reset',$nama_komponen='text1',$class='btn btn-info',$id='text1',$atribut='',$event='',$label='',$value='Submit',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='submit',$nama_komponen='text1',$class='btn btn-primary',$id='text1',$atribut='',$event='',$label='',$value='Reset',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		*/
		/*
		$value_manual=array('bumi','bulan','dna','yupiter','matahari');
		$value_database=array('username','email','admin');
		$this->buat_komponen_form_controller($type='combo_manual',$nama_komponen='combo_manual',$class='form-control',$id='combo_manual',$atribut='',$event='',$label='',$value_manual,$value_selected_combo='bulan',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='combo_database',$nama_komponen='combo_database',$class='form-control',$id='combo_database',$atribut='',$event='',$label='',$value_database,$value_selected_combo='bagus',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='text',$nama_komponen='text2',$class='form-control',$id='text2',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='text',$nama_komponen='text3',$class='form-control',$id='text3',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		
		//$this->buat_komponen_form_controller($type='button_iframe',$nama_komponen='text1',$class='btn btn-primary',$id='text1',$atribut='',$event='',$label='',$value='Button_iframe',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$perekam_id_untuk_button_ajax[1]['id']='combo_manual';
		$perekam_id_untuk_button_ajax[2]['id']='combo_database';
		$perekam_id_untuk_button_ajax[3]['id']='text2';
		$perekam_id_untuk_button_ajax[4]['id']='text3';
		$this->buat_komponen_form_controller($type='button_ajax',$nama_komponen='text1',$class='btn btn-warning',$id='text1',$atribut='',$event='',$label='',$value='Button Ajax',$value_selected_combo='',$submenu='pilihan',$aksi='tambah',$perekam_id_untuk_button_ajax);
		*/
		
		
		//$this->header_lengkap_bootstrap_controller();
		$judul="Tambahkan Kandidat";
		$tabel="admin";
		//$database="dbdatacenter";
		//$key_cari=$_GET['kolom_cari'];
		//$kolom_cari="nama_alternatif";
		$coba=array();
		$id='idadmin';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//print_r($coba);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//print_r($coba);
		
		$coba[1][4]='';//"style='padding:5px;border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		$coba[2][4]='';//"style='padding:5px;border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		//$coba[2][0]="hidden";
		//$coba[2][6]="";
		//$coba[0][6]="<font style=\"color:white;\">No Id (biarkan tidak diisi)</font>";
		//$coba[1][6]="<font style=\"color:white;\">Nama Jamkesmas</font>";
		
		$coba[2][4]='';//"cols='60' style='border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		$coba[3][4]='';//"style='padding:5px;border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		//$coba[3][0]="hidden";
		//$coba[3][6]="";
		$coba[3][4]='';//"cols='60' style='border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		//print_r($coba);
		$komponen=$coba;
		//$atribut_form='';
		//$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[2]=array('submit','submit','btn btn-primary','submit','','','','Tombol Submit');
		//$tombol[0]=array('button_ajax2','button_ajax2','btn btn-info','button_ajax2','','','','Tombol Ajax2','');
		$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Tombol Ajax4','');
		//$tombol[0]=array('button_ajax_post_CI','button_ajax_post_CI','btn btn-info','button_ajax_post_CI','','','','Tombol Ajax4','');

		$tombol1[0]=array('button_ajax','button_ajax','btn btn-info','button_ajax','','','','Tombol Ajax','');
		$value_selected_combo='';
		$target_action='target_action';
		$submenu='ini_pesan_submenu';
		$aksi='ini_pesan_tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		$this->form_general_2_view_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='Frontoffice/tes1/123/234',$data_ajax=NULL);
		//$this->form_general_2_view_vertikal_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//print_r($komponen);
		/*
		$panel[0]['judul']='Judul Panel ke-0';
     	$panel[0]['komponen']=$komponen;
		$panel[0]['tombol']=$tombol1;
		$panel[0]['value_selected_combo']=2;
		$panel[0]['target_action']=site_url('/frontoffice/tes1');
		$panel[0]['submenu']='submenu1';
		$panel[0]['aksi']='tambah';
		$panel[0]['atribut_form']='';
		$panel[0]['array_option']=array('sabar','kuat','cerah','diam');
		$panel[0]['atribut_table']=$atribut_table;
			
     	$panel[1]['judul']='Judul Panel ke-1';
     	$panel[1]['komponen']['Nama']='text';
     	$panel[1]['komponen']['Alamat']='area';
		$panel[1]['komponen']['Pilihan']='combo_manual';
		$panel[1]['tombol']=$tombol;
		$panel[1]['value_selected_combo']=3;
		$panel[1]['target_action']=site_url('/frontoffice/tes1');
		$panel[1]['submenu']='submenu1';
		$panel[1]['aksi']='tambah';
		$panel[1]['atribut_form']='';
		$panel[1]['array_option']=array('sabar','kuat','cerah','diam');
		$panel[1]['atribut_table']=$atribut_table;
		
		//print_r($panel);
		//$this->form_general_2_view_panel_controller($panel,$perekam_id_untuk_button_ajax,$class='form-control');
		*/

		//$this->penampil_tabel_tanpa_CRUID_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan='select * from tbchat',$submenu='',$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg');
		//$this->header_lengkap_bootstrap_controller();
		//$count_tbchat=$this->model_frommyframework->jumlah_rekord('tbchat');
		//$this->penampil_tabel_komentar_controller($array_atribut=array(""," class=\"table table-hover\"",""),$query_chat='SELECT * FROM `tbchat` order by idchat ASC',$count_tbchat,$jumlah_komen_ditampilkan=3,$submenu='');
	}

	public function tes3(){
		$this->header_lengkap_bootstrap_controller();
		$this->user_defined_query_controller_as_array_terenkripsi($query_terenkripsi,$token_terenkripsi);
		$tes=$this->user_defined_query_controller_as_array($query='select * from admin',$token="andisinra");
		echo "is array? ".is_array($tes)."<br>";
		print_r($tes);
	}

	public function tes4(){
		$this->header_lengkap_bootstrap_controller();
		$this->penampil_tabel_tab_pegawai_controller ($array_atribut=array(""," class=\"table table-condensed\"",""),$Query_pegawai_terbatas='select * from identpeg limit 1,20',$submenu='',$tab='',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg',$target_ajax='Frontoffice/gerbang/rincian_pegawai_table_tab');
	}

	public function tes5(){
		$this->header_lengkap_bootstrap_controller();
		$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');

	}

	public function tes6(){
		$this->header_lengkap_bootstrap_controller();
		//$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');
		$this->penampil_tabel_perhalaman ($maxRows_Recordset1=10,$tabel='identpeg',$array_atribut=array(""," class=\"table table-condensed\"",""),$style='',$query_Recordset1='select * from identpeg limit 0,10',$submenu='tes_penampil_tabel_perhalaman',$tab='');
	}

	public function tes7(){
		$this->header_lengkap_bootstrap_controller();
		//$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');
		$this->default_cruid_controller ($tabel='admin',$judul='PERCOBAAN',$pilihan1='tes_penampil_tabel_perhalaman',$aksi='tambah');
	}
//[END TES]

//[START TERJEMAHAN CONTROLLER DARI FRAMEWORK SEBELUMNYA]

	//OK, INSHAA ALLAH TINGGAL DI UJI
	//SUDAH DI UJI, ADA KEKURANGAN: TETAPI INI DIANGGAP OBSELET JADI DITINGGALKAN SEMENTARA.
	/*
	public function tes6(){
		$this->header_lengkap_bootstrap_controller();
		//$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');
		$this->penampil_tabel_perhalaman ($maxRows_Recordset1=10,$tabel='identpeg',$array_atribut=array(""," class=\"table table-condensed\"",""),$style='',$query_Recordset1='select * from identpeg limit 0,10',$submenu='tes_penampil_tabel_perhalaman',$tab='');
	}
	*/
	function penampil_tabel_perhalaman ($maxRows_Recordset1,$tabel,$array_atribut=array(""," class=\"table table-condensed\"",""),$style='',$query_Recordset1,$submenu,$tab) {
		//Definisi Style:
		echo $style;
		$currentPage = $_SERVER["PHP_SELF"];
		if (!$query_Recordset1) {
		$pageNum_Recordset1 = $this->nomor_halaman(); 
		$totalRows_Recordset1= $this->jumlah_rekord ($tabel);
		$queryString_Recordset1 = $this->penangkap_query_string ($totalRows_Recordset1);
		$totalPages_Recordset1 = $this->jumlah_page($maxRows_Recordset1,$tabel);
		$Recordset1 = $this->page_Recordset1($pageNum_Recordset1,$maxRows_Recordset1,$tabel);
		$key_kolom=$this->penarik_key_controller($tabel); 
		$Recordset=$this->konvers_recordset_CI_to_array_controller($Recordset1,$key_kolom);
		} 
		else {
		$pageNum_Recordset1 = $this->nomor_halaman(); 
		$totalRows_Recordset1= $this->jumlah_rekord_query ($query_Recordset1);
		$queryString_Recordset1 = $this->penangkap_query_string ($totalRows_Recordset1);
		$totalPages_Recordset1 = $this->jumlah_page_query($maxRows_Recordset1,$query_Recordset1);
		$Recordset =$this->page_Recordset1_byquery($pageNum_Recordset1,$maxRows_Recordset1,$query_Recordset1);
		//$key_kolom=$this->penarik_key_query_CI_controller($query_Recordset1);
		//$Recordset=$this->konvers_recordset_PDOStatement_to_array_controller($Recordset1);
		}
		
		//$row_Recordset1 = $this->konvers_recordset_to_array_controller($Recordset1);
		
		//penampil_tabel ($array_atribut,$Recordset1,$row_Recordset1,$submenu,$tab); //BAGIAN INI MUNGKIN SALAH, CEK NANTI JIKA ADA ERROR
		
		$this->penampil_tabel_with_no_query_controller ($array_atribut,$Recordset,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		
		$startRow_Recordset1 = $this->start_baris_rekord($maxRows_Recordset1,$pageNum_Recordset1);
		$this->tanda_halaman ($startRow_Recordset1,$maxRows_Recordset1,$totalRows_Recordset1);//echo "GGJGJHG".$submenu;
		if($pageNum_Recordset1=NULL){$pageNum_Recordset1=$this->session->userdata('pageNum_Recordset1');}
		echo "<div align='center' ><table border='0' width='22%' align='center'><tr style='cursor:pointer;'><td width='30' align='center'  onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pilihan=".$submenu."&pageNum_Recordset1=0"."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Awal";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pageNum_Recordset1=".max(0, $pageNum_Recordset1 - 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Sebelumnya";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pageNum_Recordset1=".min($totalPages_Recordset1, $pageNum_Recordset1 + 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Berikutnya";} // Show if not last page 
		echo "</td><td width='39' align='center' onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pageNum_Recordset1=".$totalPages_Recordset1."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Akhir";} // Show if not last page 
		echo "</td></tr></table></div>";
	}
	
	//INI DIANGGAP OBSELET, DITINGGALKAN SEMENTARA
	//Fungsi menampilkan navigasi (ALHAMDULILLAH, SUDAH DITES, OK)
	function penampil_tabel_perhalamanLAMA ($maxRows_Recordset1,$tabel,$array_atribut,$style,$Recordset1,$submenu) {
		//Definisi Style:
		echo $style;
		$currentPage = $_SERVER["PHP_SELF"];
		$pageNum_Recordset1 = $this->nomor_halaman(); 
		$totalRows_Recordset1= $this->controller_jumlah_rekord ($tabel,$database);
		$queryString_Recordset1 = $this->penangkap_query_string ($totalRows_Recordset1);
		$totalPages_Recordset1 = $this->jumlah_page($maxRows_Recordset1,$tabel);
		
		if (!$Recordset1) $Recordset1 = $this->page_Recordset1($pageNum_Recordset1,$maxRows_Recordset1,$tabel);
		//$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		
		//penampil_tabel ($array_atribut,$Recordset1,$row_Recordset1,$submenu); //BAGIAN INI MUNGKIN SALAH, CEK NANTI JIKA ADA ERROR
		$this->penampil_tabel_with_no_query_controller ($array_atribut,$Recordset1,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');

		$startRow_Recordset1 = $this->start_baris_rekord($maxRows_Recordset1,$pageNum_Recordset1);
		$this->tanda_halaman ($startRow_Recordset1,$maxRows_Recordset1,$totalRows_Recordset1);
		echo "<div align='center' ><table border='0' width='22%' align='center'><tr style='cursor:pointer;'><td width='30' align='center'  onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=0$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Awal";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=".max(0, $pageNum_Recordset1 - 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Sebelumnya";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=".min($totalPages_Recordset1, $pageNum_Recordset1 + 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Berikutnya";} // Show if not last page 
		echo "</td><td width='39' align='center' onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=".$totalPages_Recordset1."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Akhir";} // Show if not last page 
		echo "</td></tr></table></div>";
		echo "pageNum_Recordset1 = ".$pageNum_Recordset1; 
	} 

	//ALHAMDULILLAH SUDAH DITES SUKSES.
	//Fungsi Pengisi label komponen: $id digunakan jika mode nya adalah edit atau rincian, artinya semua komponen diisi berdasar id=$id, sbg awal.
	function pengisi_komponen_controller($id,$tabel,$type_form) {
		$komponen=array();$key_kolom=$this->penarik_key_controller($tabel); 
		//$komponen=array($type,$nama_komponen,$class,$id,$atribut,$event,$label,$nilai_awal)
		//---type
		$i=0;
		foreach ($key_kolom as $isi) {$komponen[$i][0]="text";$komponen[$i][2]="text";$komponen[$i][4]='';$komponen[$i][5]='';$i++;} 
		//----name/id
		$i=0;
		foreach ($key_kolom as $isi) {$komponen[$i][1]=$isi;$komponen[$i][3]=$isi;$i++;} 
		//----value
		if (!($type_form==NULL) && !($type_form=="tambah")) {
			$i=0;
			$Recordset=$this->user_defined_query_controller ("SELECT * FROM $tabel WHERE $key_kolom[0]=$id ",$token='andisinra');
			//$RowRecordset=mysql_fetch_assoc($Recordset);
			$RowRecordset=$Recordset->fetch(PDO::FETCH_ASSOC);
			foreach ($RowRecordset as $isi) {
				$komponen[$i][7]=$isi;$i++;
			}
		}
		//----label
		$i=0;
		//foreach ($key_kolom as $isi) {$key_kolom=ucwords($isi);} 
		foreach ($key_kolom as $isi) {$komponen[$i][6]=join("",array("<b>",ucwords(implode(" ",explode("_",ucwords($isi)))),"</b>"));$i++;}   
		return $komponen;
	} //end pengisi_komponen

	//BELUM TES
	//ALHAMDULILLAH SUDAH DITES OK.
	function pengisi_awal_combo ($id,$tabel,$coba) {
		//global $coba;//jangan gunakan perintah global, gunakan saja session.ini perintah lama.
		//$coba=$this->session->userdata('coba');
		$key_combo=$this->penarik_key_controller($tabel);
		
		if ($id) {
			$Recordset1=$this->user_defined_query_controller ("SELECT * FROM $tabel WHERE $key_combo[0]=$id",$token='andisinra');
			$RowRecordset1=$Recordset1->fetch(PDO::FETCH_ASSOC);
			if($coba){
				for($i=0;$i<sizeof($coba);$i++){
					$coba[$i][7]=$RowRecordset1[$key_combo[$i]];
					$coba[$i][8]='';
				}
			}
		}
		//$this->session->set_userdata('coba', $coba);
		return $coba;
	}

	//ALHAMDULILLAH SUDAH DITES SUKSES.
	function pengisi_awal_combo_panel ($id_panel,$tabel_panel) {
		//global $coba_panel; DEKLARASI CARA INI OBSELET, digantikan dengan session saja.
		$coba_panel=$this->session->userdata('coba_panel');
		foreach($tabel_panel as $key=>$k){
			$key_combo[$key]=$this->penarik_key_controller($k);
			if ($id_panel[$key]) {
				$Recordset1[$key]=$this->user_defined_query_controller ("SELECT * FROM $k WHERE {$key_combo[$key][0]}=$id_panel[$key]",$token='andisinra');
				$RowRecordset1[$key]=$Recordset1[$key]->fetch(PDO::FETCH_ASSOC);
				if($coba_panel){
					for($i=0;$i<sizeof($key_combo[$key]);$i++){
						$coba_panel[$k][$i][7]=$RowRecordset1[$key][$key_combo[$key][$i]];
					}
				}
			}
		}
		$this->session->set_userdata('coba_panel', $coba_panel);
		return $coba_panel;
	}

	//LANGSUNG AJA, KARENA SUDAH DITES, HANYA UNTUK KOMPATIBILITAS DENGAN FRAMEWORK SEBELUMNYA
	//membungkus fungsi page dari model.php
	function tabel_perhalaman($halaman_ke,$maxRows_Recordset1,$tabel) {
		return $this->page_row_Recordset1($halaman_ke,$maxRows_Recordset1,$table);
	}

	//LANGSUNG AJA. GA DI TES KARENA SIMPLE.
	//Fungsi menemukan nomor halaman (SUDAH DITES, OK)
	function nomor_halaman () {
		if (isset($_POST['pageNum_Recordset1'])) {$pageNum_Recordset1 = $_POST['pageNum_Recordset1'];} 
		else if (isset($_GET['pageNum_Recordset1'])){$pageNum_Recordset1 = $_GET['pageNum_Recordset1'];} 
		else {$pageNum_Recordset1 = 0;}
		return $pageNum_Recordset1;
	}

	//LANGSUNG AJA.
	//Fungsi penghitung rekord awal (SUDAH DITES, OK)
	function start_baris_rekord($maxRows_Recordset1,$pageNum_Recordset1) {return $pageNum_Recordset1*$maxRows_Recordset1;}

	//LANGSUNG AJA. UNTUK KOMPATIBILITAS.
	//Fungsi penghitung jumlah rekord dari controller (SUDAH DITES, OK)
	function controller_jumlah_rekord($tabel) {return $this->jumlah_rekord($tabel);}

	//LANGSUNG AJA, MASIH SIMPLE.
	//Fungsi penghitung jumlah page, (SUDAH DITES, OK)
	function jumlah_page($maxRows_Recordset1,$tabel) {
		if (isset($_GET['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_GET['totalRows_Recordset1'];} 
		else if (isset($_POST['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_POST['totalRows_Recordset1'];} 
		else {$totalRows_Recordset1 = $this->jumlah_rekord($tabel);}
		$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
		return $totalPages_Recordset1;
	}

	//LANGSUNG AJA.
	//Fungsi penghitung jumlah page, (SUDAH DITES, OK)
	function jumlah_page_query($maxRows_Recordset1,$query) {
		if (isset($_GET['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_GET['totalRows_Recordset1'];} 
		else if (isset($_POST['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_POST['totalRows_Recordset1'];} 
		else {$totalRows_Recordset1 = $this->jumlah_rekord_query($query,$token='andisinra');}
		$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
		return $totalPages_Recordset1;
	}
	
	//LANGSUNG AJA. 
	//TAPI INI MESTI DIPERHATIKAN NANTI, MUNGKIN SUDAH OBSELET KARENA CODEIGNITER MENGGUNAKAN ATURAN URI YANG BERBEDA.
	//Sudah dites (SUDAH DITES, OK) tetapi logikanya belum dites, tunggu hasil sebenarnya.
	function penangkap_query_string ($totalRows_Recordset1) {
		$queryString_Recordset1 = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
			$params = explode("&", $_SERVER['QUERY_STRING']);
			$newParams = array(); 
			foreach ($params as $param) {
				if (stristr($param, "pageNum_Recordset1") == false && stristr($param, "totalRows_Recordset1") == false) {
					array_push($newParams, $param);
				}
			}
			if (count($newParams) != 0) {
				$queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
			}
		}
		$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
		return $queryString_Recordset1;
	}
	
	//Fungsi menampilkan halaman yg sudah dibrowse : (ALHAMDULILLAH, SUDAH DITES, OK)
	function tanda_halaman ($startRow_Recordset1,$maxRows_Recordset1,$totalRows_Recordset1) {
		echo "<div align='center'>Records".($startRow_Recordset1 + 1)." to ".min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1)." of ".$totalRows_Recordset1." </div>";
	}

	//OK INI LANGSUNG AJA
	//--------------------------------------------------------------------
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
		switch ($theType) {
		case "text":$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";break;    
		case "long":case "int":$theValue = ($theValue != "") ? intval($theValue) : "NULL";break;
		case "double":$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";break;
		case "date":$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";break;
		case "defined":$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;break;
	}
		return $theValue;
	}

	function editFormAction(){
		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);}
		return $editFormAction;
	}

	

	//[END TERJEMAHAN CONTROLLER]

	
	//[START TERJEMAHAN VIEW DARI FRAMEWORK SEBELUMNYA]

	function penampil_tombol_add_controller ($add,$toolbar,$src_wh){
		$this->viewfrommyframework->penampil_tombol_add ($add,$toolbar,$src_wh);
	}

	public function penampil_tabel_with_no_query_controller ($array_atribut,$Recordset1,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
		$this->viewfrommyframework->penampil_tabel_with_no_query ($array_atribut,$Recordset1,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}

	function penampil_tabel_tab_pegawai_controller ($array_atribut,$Query_pegawai_terbatas,$submenu,$tab,$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg',$target_ajax){
		$this->viewfrommyframework->penampil_tabel_tab_pegawai ($array_atribut,$Query_pegawai_terbatas,$submenu,$tab,$kolom_direktori,$direktori_avatar,$target_ajax);
	}

    //UNTUK KOMPATIBILITAS
    function penampil_bar_searching_controller ($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3) {
		$this->viewfrommyframework->penampil_bar_searching ($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3);
	}

    //UNTUK KOMPATIBILITAS
    function penampil_bar_judul_controller ($judul,$style) {
		$this->viewfrommyframework->penampil_bar_judul($judul,$style);
	}
	function tampil_add ($add,$toolbar,$src_wh) {
		$this->viewfrommyframework->penampil_tombol_add ($add,$toolbar,$src_wh);
	}	
	function tampil_bar_searching($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3) {
		$this->viewfrommyframework->penampil_bar_searching ($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3);
	}
	function penampil_bar_judul_c ($judul,$style){
		$this->viewfrommyframework->penampil_bar_judul ($judul,$style);
	}  
	
	function penampil_tabel_komentar_controller ($array_atribut,$query_chat='SELECT * FROM `tbchat` order by idchat DESC',$count_tbchat,$jumlah_komen_ditampilkan,$submenu){
		$this->viewfrommyframework->penampil_tabel_komentar ($array_atribut,$query_chat,$count_tbchat,$jumlah_komen_ditampilkan,$submenu);
	}

	public function penampil_tabel_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar){
		return $this->viewfrommyframework->penampil_tabel($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}
	
	//hanya untuk jaga-jaga, untuk kompatibilitas.
	public function penampil_tabel_LAMA_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg'){
		return $this->viewfrommyframework->penampil_tabel($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	public function penampil_tabel_tanpa_CRUID_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg'){
		return $this->viewfrommyframework->penampil_tabel_tanpa_CRUID($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	public function penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg'){
		return $this->viewfrommyframework->penampil_tabel_tanpa_CRUID_vertikal($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	function buat_komponen_form_controller($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->buat_komponen_form($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
	}

	function form_general_2_view_panel_controller($panel,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_view_panel($panel,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}
	
	function form_general_2_view_vertikal_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_view_vertikal($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}
	
	function form_general_2_view_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_view($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_2_vertikal_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_vertikal($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
		$this->viewfrommyframework->form_general_2_vertikal_non_iframe($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$selected,$class='form-control',$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio){
		$this->viewfrommyframework->form_general($komponen,$atribut_form,$array_option,$atribut_table,$judul,$selected,$class,$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio);
	}

	function form_combo_database_controller($type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected){
		$this->viewfrommyframework->form_combo_database($type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected);
	}

	function bootstrap_css_controller($path='assets/bootstrap/css/bootstrap.min.css'){
		$this->viewfrommyframework->bootstrap_css($path);
	}

	function fontawesome_css_controller($path='assets/fontawesome-free/css/all.min.css'){
		$this->viewfrommyframework->fontawesome_css($path);
	}

	function jquery_controller($path='assets/jquery/jquery.min.js'){
		$this->viewfrommyframework->jquery($path);
	}

	function bootstrap_js_controller($path='/login/vendor/bootstrap/js/bootstrap.min.js'){
		$this->viewfrommyframework->bootstrap_js($path);
	}

	function header_lengkap_bootstrap_controller($charset='utf-8',$content='width=device-width, initial-scale=1',$path_boostrap_js='/login/vendor/bootstrap/js/bootstrap.min.js',$path_jquery='/login/vendor/jquery/jquery-3.2.1.min.js',$path_fontawesome='/assets/fontawesome-free/css/all.min.css',$path_bootstrap_css='/login/css/css/bootstrap.css'){
		$this->viewfrommyframework->header_lengkap_bootstrap($charset,$content,$path_boostrap_js,$path_jquery,$path_fontawesome,$path_bootstrap_css);
	}

	function css_lain_controller($path){
		$this->viewfrommyframework->css_lain($path);
	}

	function js_lain_controller($path){
		$this->viewfrommyframework->js_lain($path);
	}
	//====================================================================================================================================

	function form_input_controller($type,$nama_komponen,$class='form-control',$id,$atribut,$event){
		$this->viewfrommyframework->form_input($type,$nama_komponen,$class,$id,$atribut,$event);
	}

	function form_area_controller($nama_komponen,$class='form-control',$id,$atribut){
		$this->viewfrommyframework->form_area($nama_komponen,$class,$id,$atribut);
	}

	function form_combo_manual_controller($nama_komponen,$class='form-control',$id,$atribut,$array_option,$selected){
		$this->viewfrommyframework->form_combo_manual($nama_komponen,$class,$id,$atribut,$array_option,$selected);
	}

//[END BATAS]

//[START BATAS]
//BATAS SEMUA FUNGSI YANG MERPRESENTASIKAN MODEL DAN FUNGSI-FUNGSI BANTU

	public function penarik_key_controller_panel($tabel_panel)
	{
		return $this->model_frommyframework->penarik_key_model_panel($tabel_panel);
	}

	//Fungsi ini bertujuan menarik semua nama kolom dari tabel_panel.
	//tabel_panel = tabel yang memuat nama-nama tabel, strukturnya: array('index'=>'nama_tabel'), itu saja
	//penarik_key_model_panel = menghasilkan semua nama kolom dari daftar tabel di tabel_panel.
	//dikatakan tabel_panel karena ada sebuah panel yang menggunakan n buah tabel untuk tampil pada frontend.
	//sehingga perlu menarik informasi nama kolom semua n buah tabel tersebut.
	public function penarik_kolom_controller($kolom_value,$kolom_label,$tabel)
	{
		return $this->model_frommyframework->penarik_kolom_model($kolom_value,$kolom_label,$tabel);
	}

	public function strToHex($str)
	{
		return $this->enkripsi->strToHex($str);
		
	}

	public function hexToStr($str)
	{
		return $this->enkripsi->hexToStr($str);
	}

	public function hapus_rekord($tabel,$id)
	{
		return $this->model_frommyframework->hapus_rekord($tabel,$id);
	}

	public function general_update_controller($kiriman,$tabel)
	{
		return $this->model_frommyframework->general_update_model($kiriman,$tabel);
	}

	public function general_insertion_controller($kiriman,$tabel)
	{
		return $this->model_frommyframework->general_insertion_model($kiriman,$tabel);
	}

	public function page_Recordset1_search($pageNum_Recordset1,$maxRows_Recordset1,$tabel,$kolom_cari,$key_cari)
	{
		return $this->model_frommyframework->page_Recordset1_search($pageNum_Recordset1,$maxRows_Recordset1,$tabel,$kolom_cari,$key_cari);
	}

	//Fungsi ini mengenkripsi data yang hendak dikirim kemudian menerjemahkannya ke hex
	public function pengirim_terenkripsi_simetri($dataToEnkrip,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'))
	{
		$dataToEnkrip=str_replace('%20',' ',$dataToEnkrip);//data ga boleh memuat %20, terjadi jika dimasukkan lewat addressbar browser.
		$this->enkripsi->initialize($setting);
		$dataTerenkripsi=$this->enkripsi->enkripSimetri_data($dataToEnkrip);
		return $this->enkripsi->strToHex($dataTerenkripsi);
	}

	//Fungsi ini untuk mendekrip data
	public function penerima_terenkripsi_simetri($dataToDekrip,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'))
	{
		$dataToDekrip=$this->enkripsi->hexToStr($dataToDekrip);
		$this->enkripsi->initialize($setting);
		return $this->enkripsi->dekripSimetri_data($dataToDekrip);
	}

	//Fungsi penarik dengan query user defined dimana menerima query dan token yang terenkripsi 
	//menerima enkripsi simetri dari kelas Enkripsi.php
	function user_defined_query_controller_terenkripsi($query_terenkripsi,$token_terenkripsi)
	{
		$query=$this->penerima_terenkripsi_simetri($query_terenkripsi);//jangan tambahakn $setting pada penerima_terenkripsi_simetri($query_terenkripsi,$setting)
		$token=$this->penerima_terenkripsi_simetri($token_terenkripsi);//karena error, dianggap menimpa default setting padahal kosong sehingga menghasilkan setingan kosong
		return $this->user_defined_query_controller($query,$token);
		//kembalian ini berupa array dengan key adalah nama-nama kolom 
		//TES: foreach ($hasil_query as $row){echo "<br>".$row['username'];}
		
	}

	public function page_row_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table)
	{
		return $this->model_frommyframework->page_row_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table);
		//hasilnya langsung berupa array, tinggal dipanggil menggunakan nama kolomnya, misal $testabel->namakolom
	}

	public function page_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table,$order='DESC')
	{
		return $this->model_frommyframework->page_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table,$order);
		//buat tes: foreach ($testabel->result() as $row){echo "<br>".$row->username;}
		//ini berupa objek hasilnya, bukan item hyang siap pakai, untuk menggunakannya pake result() dulu baru pake nama kolomnya.
		//fungsi ini hanya untuk memelihara kompatibilitas sebelum migrasi
	}	

	public function page_Recordset1_byquery($pageNum_Recordset1,$maxRows_Recordset1,$query_Recordset1)
	{
		return $this->model_frommyframework->page_Recordset1_byquery($pageNum_Recordset1,$maxRows_Recordset1,$query_Recordset1);
		//foreach ($testabel as $row){echo "<br>".$row['nama'];}
		//ini berupa objek hasilnya, bukan item hyang siap pakai, untuk menggunakannya pake result() dulu baru pake nama kolomnya.
		//fungsi ini hanya untuk memelihara kompatibilitas sebelum migrasi
	}	

	public function penarik_key_controller($table)
	{
		return $this->model_frommyframework->penarik_key_model($table);
		//kembalian berupa array nama kolom tabel
	}

	public function jumlah_rekord($table)
	{
		return $this->model_frommyframework->jumlah_rekord($table);
		//kembaliannya hanyalah bilangan tunggal
	}


	public function jumlah_rekord_query($query,$token='andisinra')
	{
		return $this->model_frommyframework->jumlah_rekord_query($query,$token);
	}

	public function total_halaman($maxRows_Recordset1,$table)
	{
		return $this->model_frommyframework->total_halaman($maxRows_Recordset1,$table);
		//return $testabel; //kembaliannya hanyalah bilangan tunggal
	}

	//ALHAMDULILLAH SUKSES
	public function alert($e){$e=str_replace('%20',' ',$e);alert($e);}

	//ALHAMDULILLAH SUKSES
	public function user_defined_query_controller($query,$token="oke")
	{
		return $this->model_frommyframework->user_defined_query_model($query,$token);
		//foreach ($coba as $row){echo "<br>".$row['username'];}
		//haslnya adalah objek PDOStatment, untuk menggunakan anggap saja dia array, misal $coba['username'], secara umum $coba['nama_kolom']
		//atau hanya menggunakan indexnya $coba['$i'] dimana $i adalah integer. ini karena saat dia didefinisikan di kelas model_frommyframework
	}

	public function user_defined_query_controller_as_array($query,$token="oke")
	{
		return $this->model_frommyframework->user_defined_query_model_as_array($query,$token);
		//foreach ($coba as $row){echo "<br>".$row['username'];}
		//haslnya adalah objek PDOStatment, untuk menggunakan anggap saja dia array, misal $coba['username'], secara umum $coba['nama_kolom']
		//atau hanya menggunakan indexnya $coba['$i'] dimana $i adalah integer. ini karena saat dia didefinisikan di kelas model_frommyframework
	}

	//INI HANYA CONTOH PENGGUNAAN ENKRIPSI, SEJATINYA DISANA MESTI ADA DEKRIP SEBELUM QUERY, BTW INSHAA ALLAH BISA DITERAPKAN PADA LOGIC DILUAR FUNGSI
	//Fungsi penarik dengan query user defined dimana menerima query dan token yang terenkripsi 
	//menerima enkripsi simetri dari kelas Enkripsi.php
	function user_defined_query_controller_as_array_terenkripsi($query_terenkripsi,$token_terenkripsi)
	{
		$query=$this->penerima_terenkripsi_simetri($query_terenkripsi);//jangan tambahakn $setting pada penerima_terenkripsi_simetri($query_terenkripsi,$setting)
		$token=$this->penerima_terenkripsi_simetri($token_terenkripsi);//karena error, dianggap menimpa default setting padahal kosong sehingga menghasilkan setingan kosong
		return $this->user_defined_query_controller_as_array($query,$token);
		//kembalian ini berupa array dengan key adalah nama-nama kolom 
		//TES: foreach ($hasil_query as $row){echo "<br>".$row['username'];}
		
	}

	//ALHAMDULILLAH FUNGSI INI DITUJUKAN UNTUK MENGAMBIL SEMUA KEY DARI TABEL ATAU SEMBARANG QUERY YANG MENGHASILKAN TABEL UNTUK DITAMPILKAN
	public function penarik_key_string_ut_sebarang_query_controller($query){
		return $this->model_frommyframework->penarik_key_string_ut_sebarang_query_model($query);
	}

	public function konvers_recordset_PDOStatement_to_array_controller($recordset){
		return $this->model_frommyframework->konvers_recordset_PDOStatement_to_array($recordset);
	}

	public function konvers_recordset_CI_to_array_controller($Recordset1,$nama_kolom){
        return $this->model_frommyframework->konvers_recordset_CI_to_array($Recordset1,$nama_kolom);
    }

    //penarik key untuk query yang dihasilkan oleh perintah $this->db() milik CI
    public function penarik_key_query_CI_controller($query){
		return $this->model_frommyframework->penarik_key_query_CI($query);
    }
//[END BATAS]

/**
 * Pertanyaan tersisa:
 * Dimana menempatkan gerbang.php?
 * Bagaimana menerapkan php moderen peritem fungsi?
 * Bagaimana generator?
 * Bagaimana exeption?
 * Bagaimana error handler?
 */
}
