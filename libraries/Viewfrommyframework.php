<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewfrommyframework {
    protected $CI;
    protected $button_ajax2=array();
    
	public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('alert');
        $this->CI->load->library('enkripsi');
		$this->CI->load->library('session');
        $this->CI->load->model("model_frommyframework");

    }
	
	public function index(){
		
    }

    public function _set($data=array('Frontoffice/gerbang')){
        foreach($data as $k){
            array_push($this->button_ajax2,$k);
        }
    }

    //===========================================TAMBAHAN KHUSUS UNTUK CRUID VERIFIKASI PERBAIKAN===========================================
    function penampil_tabel_no_foto_untuk_surat_masuk_frontoffice_surat_masuk_verifikasi ($kolom_cari,$nama_kolom_direktori_surat,$array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        //var_dump($Recordset1);
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            //var_dump($Recordset);
        
            unset($Recordset1);
            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                //echo "<h4>Kelola Surat Masuk</h4>";
                //print_r($_SERVER);
                echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    if(in_array($value,$kolom_cari)){//timestamp_masuk
                        if($value=='timestamp_masuk')echo "<th >Tanggal masuk</th>";else {
                            $temporer=ucwords(implode(' ',explode('_',$value)));
                            echo "<th>$temporer</th>";
                        }
                    }
                } 
                echo "</tr>";
                
                
                $i=0;
                foreach($Recordset as $keyisi=>$isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;
                    echo "
                    <style>
                    #td$keyisi{
                        width:120px;
                    }
                    </style>
                    ";
                    echo "<td valign='top' id='td$keyisi'>";
                    //$this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Verifikasi',$value_selected_combo='',$submenu='rincian_penampil_tabel',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "
                    <button style=\"float:left; margin-right:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm kotak\" id=\"eyebuka$keyisi\"><i class='fas fa-check-circle fa-sm text-white-100'></i> Verifikasi</button>
                    ";

                    /*OLD
                    echo "
                    <button data-toggle=\"modal\" data-target=\"#modal_verifikasi\" class=\"btn btn-sm btn-success shadow-sm kotak\" id=\"verifikasi$keyisi\"><i class='fas fa-check-circle fa-sm text-white-100'></i> Verifikasi</button>
                    ";
                    */
                    echo "</td>";
                    //$this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Verifikasi',$value_selected_combo='',$submenu='rincian_penampil_tabel',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        $warna_badge='info';
                        if(in_array($k,$kolom_cari)){
                            if($k!=='status_surat'){
                                if($k=='timestamp_masuk'){
                                    $temporer=explode('-',$isi[$k]);
                                    echo "<td align=left valign=top>".$temporer[0]."</td>";
                                } else {
                                    echo "<td align=left valign=top>".$isi[$k]."</td>";
                                }
                            }else{
                                /*
                                $isi[$k]=='dibaca'?$warna_badge='success':
                                $isi[$k]=='ditolak'?$warna_badge='danger':
                                $isi[$k]=='dipending'?$warna_badge='warning':
                                $isi[$k]=='diteruskan'?$warna_badge='primary':
                                */
                                if($isi[$k]=='dibaca'){
                                    $warna_badge='success';
                                    $time=explode('-',$isi['timestamp_baca']);
                                    echo "<td align=left valign=top><span class=\"badge badge-$warna_badge\">".$isi[$k]."</span><br><span class=\"badge badge-$warna_badge\">".$time[0]."</span>
                                    <br><span class=\"badge badge-$warna_badge\">".$time[1]."</span>";
                                } else if($isi[$k]=='ditolak'){
                                    $warna_badge='danger';
                                    $time=explode('-',$isi['timestamp_tolak']);
                                    echo "<td align=left valign=top><span class=\"badge badge-$warna_badge\">".$isi[$k]."</span><br><span class=\"badge badge-$warna_badge\">".$time[0]."</span>
                                    <br><span class=\"badge badge-$warna_badge\">".$time[1]."</span>";
                                } else if($isi[$k]=='dipending'){
                                    $warna_badge='warning';
                                    $time=explode('-',$isi['timestamp_dipending']);
                                    echo "<td align=left valign=top><span class=\"badge badge-$warna_badge\">".$isi[$k]."</span><br><span class=\"badge badge-$warna_badge\">".$time[0]."</span>
                                    <br><span class=\"badge badge-$warna_badge\">".$time[1]."</span>";
                                } else if($isi[$k]=='diteruskan'){
                                    $warna_badge='primary';
                                    $time=explode('-',$isi['timestamp_diteruskan']);
                                    echo "<td align=left valign=top><span class=\"badge badge-$warna_badge\">".$isi[$k]."</span><br><span class=\"badge badge-$warna_badge\">".$time[0]."</span>
                                    <br><span class=\"badge badge-$warna_badge\">".$time[1]."</span>";
                                } else if($isi[$k]=='selesai'){
                                    $warna_badge='success';
                                    $time=explode('-',$isi['timestamp_dikembalikan']);
                                    echo "<td align=left valign=top><span class=\"badge badge-$warna_badge\">SELESAI</span><br><span class=\"badge badge-$warna_badge\">".$time[0]."</span>
                                    <br><span class=\"badge badge-$warna_badge\">".$time[1]."</span>";
                                } else {
                                    $warna_badge='info';
                                    $time=explode('-',$isi['timestamp_masuk']);
                                    echo "<td align=left valign=top><span class=\"badge badge-$warna_badge\">".$isi[$k]."</span><br><span class=\"badge badge-$warna_badge\">".$time[1]."</span>";
                                }
                                echo "</td>";
                            }
                        }
                    } 
                    echo "</tr>";
                    $size=sizeof($kolom_cari);
                    echo "<tr id=\"rincian_surat$keyisi\">
                    <td>
                    <button class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\"  style=\"width:100px; height:100%; margin-top:20px; margin-bottom:0px; margin-top:5px;\" id=\"eyeSlashTutup$keyisi\"><i class='fas fa-eye-slash fa-sm text-white-100'></i> <br>Tutup</button>
                    </td>
                    <td colspan=".$size." align='justify'>
                    <button class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm kotak\" style=\"width:13%;margin-top:5px;\" id=\"isiSurat$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-envelope-open fa-sm text-white-100'></i> <br>Buka Surat</button>
                    <button class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm kotak\" style=\"width:15.5%;margin-top:5px;\" id=\"berkasSurat$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-folder-open fa-sm text-white-100'></i> <br>Berkas Pendukung</button>
                    <button class=\"d-sm-inline-block btn btn-sm btn-primary shadow-sm kotak\" style=\"width:13%;margin-top:5px;\" id=\"selesai$keyisi\" data-toggle=\"modal\" data-target=\"#modal_verifikasi\"><i class='fas fa-archive fa-sm text-white-100'></i> <br>SELESAI</button>
                    <button class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" style=\"width:13%;margin-top:5px;\" id=\"pending$keyisi\" data-toggle=\"modal\" data-target=\"#modal_verifikasi_sedang\"><i class='fas fa-pause fa-sm text-white-100'></i> <br>Pending</button>
                    <button class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\" style=\"width:13%;margin-top:5px;\" id=\"tolak$keyisi\" data-toggle=\"modal\" data-target=\"#modal_verifikasi_sedang\"><i class='fas fa-backward fa-sm text-white-100'></i> <br>Tolak</button>
                    <button class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm kotak\" style=\"width:13%;margin-top:5px;\" id=\"detail_surat$keyisi\" ><i class='fas fa-cog fa-sm text-white-100'></i> <br>Detail</button>
                    <button class=\"d-sm-inline-block btn btn-sm btn-primary shadow-sm kotak\" style=\"width:15.5%;margin-top:5px;\" id=\"unggah_balasan$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-upload fa-sm text-white-100'></i> <br>Unggah Balasan</button>
                    </td></tr>";
                    
                    echo "<tr id=\"rincian_surat_2$keyisi\">";
                    echo "<td width='100px'>
                    <button class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\"  style=\"width:100px;\" id=\"eyeSlashTutup2$keyisi\"><i class='fas fa-eye-slash fa-sm text-white-100'></i> Tutup</button><br>
                    </td>";
                    echo "<td colspan=".sizeof($kolom_cari).">
                    <button class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm kotak\" style=\"width:100%;\" id=\"detail_surat2$keyisi\" ><i class='fas fa-eye fa-sm text-white-100'></i> Lihat Rincian Surat</button>
                    <center>
                    <div id='pra_rincian$keyisi' style='width:65%;' align='center' >
                    Mohon ditunggu...<br>
                    <i class='fa-3x fas fa-spinner fa-pulse' ".$this->CI->config->item('style_progres_bulat_admin')."></i>
                    </center>
                    </div>
                    <div id=penampil_rincian$keyisi style='text-align:left;width:100%;overflow:auto;'></div>
                    ";
                    echo "</td>";
                    //echo "<td ></td>";
                    echo "</tr>";
                    
                    echo "
                        <style>
                            #rincian_surat$keyisi{
                                display:none;
                            }
                            #rincian_surat_2$keyisi{
                                display:none;
                            }
                            #pra_rincian$keyisi{
                                display:none;
                            }
                            @media screen and (max-width: 480px) {
                                #verifikasi$keyisi{
                                    width:100%;
                                }
                                #eyebuka$keyisi{
                                    width:100%;
                                    margin-bottom:5px;
                                }
                            }
                        </style>
                    ";
                    $direktori_surat=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($isi[$nama_kolom_direktori_surat['surat']]));
                    $direktori_berkas=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($isi[$nama_kolom_direktori_surat['berkas']]));
                    $surat=explode('.',$isi[$nama_kolom_direktori_surat['surat']]);
                    $berkas=explode('.',$isi[$nama_kolom_direktori_surat['berkas']]);
                    echo "
                    <script>
					$(document).ready(function(){
                        $(\"#teruskan_new$keyisi\").click(function(){
                            var loading = $(\"#pra_verifikasi\");
                            var tampilkan = $(\"#penampil_verifikasi\");
                            tampilkan.hide();
							//loading.html('Persiapan pengiriman dengan memuat data ke memori...loading..<br><i class=\"fa-3x fas fa-spinner fa-pulse\" ".$this->CI->config->item('style_progres_bulat_admin')."></i>');
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/untuk_menampilkan_modal_alamat')."',{key:\"".$tampung_key[0]."\",data:\"".$isi[$tampung_key[0]]."\" },
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#selesai$keyisi\").click(function(){
                            var loading = $(\"#pra_verifikasi\");
                            var tampilkan = $(\"#penampil_verifikasi\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/selesai')."',{key:\"".$tampung_key[0]."\",data:\"".$isi[$tampung_key[0]]."\" },
                            function(data,status){
                                //alert('OK BRO SELESAI DALAM');
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#pending$keyisi\").click(function(){
                            var loading = $(\"#pra_verifikasi_sedang\");
                            var tampilkan = $(\"#penampil_verifikasi_sedang\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/pending')."',{key:\"".$tampung_key[0]."\",data:\"".$isi[$tampung_key[0]]."\" },
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#tolak$keyisi\").click(function(){
                            var loading = $(\"#pra_verifikasi_sedang\");
                            var tampilkan = $(\"#penampil_verifikasi_sedang\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/tolak')."',{key:\"".$tampung_key[0]."\",data:\"".$isi[$tampung_key[0]]."\" },
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#detail_surat2$keyisi\").click(function(){
                            var loading = $(\"#pra_rincian$keyisi\");
                            var tampilkan = $(\"#penampil_rincian$keyisi\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/tampilkan_rincian_surat_frontoffice')."',{key:\"".$tampung_key[0]."\",data:\"".$isi[$tampung_key[0]]."\" },
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });
                        
						$(\"#eyebuka$keyisi\").click(function(){
							$('#rincian_surat$keyisi').toggle(1000);
							$('#rincian_surat_2$keyisi').hide(1000);
							$('#penampil_rincian$keyisi').html('');
						});
						$(\"#detail_surat$keyisi\").click(function(){
							$('#rincian_surat_2$keyisi').toggle(1000);
							$('#penampil_rincian$keyisi').html('');
						});
						$(\"#eyeSlashTutup$keyisi\").click(function(){
							$('#rincian_surat$keyisi').fadeOut(1000);
							$('#rincian_surat_2$keyisi').hide(1000);
                        });
						$(\"#eyeSlashTutup2$keyisi\").click(function(){
							$('#rincian_surat_2$keyisi').hide(1000);
							$('#penampil_rincian$keyisi').html('');
                        });
                        $(\"#isiSurat$keyisi\").click(function(){
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
							loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                $.post('".site_url('/Frontoffice/proses_baca')."',{key:\"".$tampung_key[0]."\",data:\"".$isi[$tampung_key[0]]."\" },
                                function(data_baca,status){
                                    //BAGIAN MENCATAT LOG KE BANKDATA
                                    $.post('".$this->CI->config->item('bank_data')."/index.php/Frontoffice/insersi_ke_tabel_log_surat_frontoffice/"."'+data_baca,{ data:data_baca},
                                    function(data_log,status_log){
                                    });
                                });

                                //Bagian membaca di modal yang besar
                                loading1.fadeOut();
                                ";
                                if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "tampilkan1.html(data);";
					echo "		});
                        });
                        ";

                    echo "
                        $(\"#berkasSurat$keyisi\").click(function(){
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
							loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_berkas)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                if($berkas[sizeof($berkas)-1]=='pdf'||$berkas[sizeof($berkas)-1]=='png'||$berkas[sizeof($berkas)-1]=='jpg'||$berkas[sizeof($berkas)-1]=='html'||$berkas[sizeof($berkas)-1]=='htm'||$berkas[sizeof($berkas)-1]=='bmp'||$berkas[sizeof($berkas)-1]=='gif'||$berkas[sizeof($berkas)-1]=='mp4'||$berkas[sizeof($berkas)-1]=='mp3'||$berkas[sizeof($berkas)-1]=='vid'||$berkas[sizeof($berkas)-1]=='wav') echo "            tampilkan1.html(data);";
                    echo "		});
                        });
                        
                                                
						});
					</script>
                    ";

                }
                
                echo "</table></div>";
                echo "
                    <!-- Modal Baca Surat -->
                    <div class='modal fade' id='modal_verifikasi' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog' >
                        
                        <!-- Modal content-->
                        <div class='modal-content' ".$this->CI->config->item('style_modal_admin')." >
                            <div class='modal-header'>
                            <h4 class='modal-title'>e-Sinra ".$this->CI->config->item('nama_opd')." Provinsi Sulawesi Selatan</h4>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_verifikasi' style='width:65%;' align='center' >
                            Mohon ditunggu...<br>
                            <i class='fa-3x fas fa-spinner fa-pulse' ".$this->CI->config->item('style_progres_bulat_admin')."></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_verifikasi align='center' style='width:100%;overflow:auto;'></div>
                            </div>
                            <div class='modal-footer'>
                            <!--<button type='button' class='btn btn-primary' id=\"perbesar_modal\" onclick='$(\"#modal_baca_surat_new_perbesar\").modal(\"show\");'>Perbesar</button>-->
                            <button type='button' class='btn btn-primary' data-dismiss='modal' id='close_ok'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal Baca Surat -->
                    <div class='modal fade' id='modal_verifikasi_sedang' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog'>
                        
                        <!-- Modal content-->
                        <div class='modal-content' ".$this->CI->config->item('style_modal_admin').">
                            <div class='modal-header'>
                            <h4 class='modal-title'>e-Sinra ".$this->CI->config->item('nama_opd')." Provinsi Sulawesi Selatan</h4>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_verifikasi_sedang' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' ".$this->CI->config->item('style_progres_bulat_admin')."></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_verifikasi_sedang align='center' style='width:100%;overflow:auto;'></div>
                            </div>
                            <div class='modal-footer'>
                            <!--<button type='button' class='btn btn-primary' id=\"perbesar_modal\" onclick='$(\"#modal_baca_surat_new_perbesar\").modal(\"show\");'>Perbesar</button>-->
                            <button type='button' class='btn btn-primary' data-dismiss='modal' id='close_ok_sedang'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal Baca Surat -->
                    <div class='modal fade' id='modal_baca_surat_new' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog modal-lg'>
                        
                        <!-- Modal content-->
                        <div class='modal-content' ".$this->CI->config->item('style_modal_admin').">
                            <div class='modal-header'>
                            <h4 class='modal-title'>e-Sinra ".$this->CI->config->item('nama_opd')." Provinsi Sulawesi Selatan</h4>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_baca_surat_new' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' ".$this->CI->config->item('style_progres_bulat_admin')."></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_baca_surat_new align='center' style='width:100%;height:500px;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id=\"perbesar_modal\" onclick='$(\"#modal_baca_surat_new_perbesar\").modal(\"show\");'>Perbesar</button>
                            <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal Baca Surat Perbesar -->
                    <div class='modal fade' id='modal_baca_surat_new_perbesar' role='dialog' style='z-index:100001;'>
                        <div class='modal-dialog modal-lg' style='max-width:100%;'>
                        
                        <!-- Modal content-->
                        <div class='modal-content' ".$this->CI->config->item('style_modal_admin').">
                            <div class='modal-header'>
                            <h4 class='modal-title'>e-Sinra ".$this->CI->config->item('nama_opd')." Provinsi Sulawesi Selatan</h4>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_baca_surat_new1' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' ".$this->CI->config->item('style_progres_bulat_admin')."></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_baca_surat_new1 align='center' style='width:100%;height:500px;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
            } else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }
    //===========================================END TAMBAHAN KHUSUS UNTUK CRUID VERIFIKASI PERBAIKAN=======================================
    
    //===========================================REVISI-9001:ruangkaban-view================================================================
    public function baca_agenda_view_9001($table='tbagenda_kerja',$mulai_rekord=0,$jumlah_rekord=20,$order='desc'){
        /**
         * Rencana revisi untuk fungsi ini:
         * [0]Hapus beberapa informasi yang tidak penting seperti id acara, cukup mereka tampil di bagian kelola agenda.
         * [1]Tambahkan menu popup.
         * [2]Ganti menu rincian dan menu kirim.
         * [3]Ga usah pake narasi di ruangkaban, tapi sembunyikan saja.
         * [4]Tambahkan jam pada tanggal.
         * [5]Khusus untuk penampilan informasi tanggal di agenda hari ini, gabungkan dalam satu kolom format:
         * Cek dulu bila kolom tanggal_mulai dan tanggal_sampai nilainya sama maka tampilkan informasi dengan format:
         * Mulai Jam:
         * Sampai Jam:
         * Jika tanggal_mulai tidak sama dengan tanggal_sampai maka:
         * Mulai Tanggal:     Jam:...
         * Sampai Tanggal:    Jam:...
         */

		echo "
			<h5>Agenda Hari Ini</h5>  
            <table class='table table-hover table-striped' width=\"100%\">
            <style>
            #head_agenda{
                display:none;
            }
            .kiri_tabel{
                display:none;
            }
            @media screen and (max-width: 480px) {
                .kiri_tabel{
                    display:table-cell;
                }
                .kanan_tabel{
                    display:none;
                }
                #menu_pengayaan{
                    width:200px;
                }
            }
            </style>
            <thead>
				<tr id='head_agenda'>
				<th colspan=2 class='kiri_tabel' data-toggle=\"tooltip\" title=\"Tambahkan agenda baru\"><button  data-toggle=\"modal\" data-target=\"#modal_baca_surat_new_tambah\" style=\"width:100%;\" class=\"btn btn-success shadow-sm kotak tambahkan_item_agenda\"><i class='fas fa-plus fa-sm text-white-100'></i> Agenda</button></th>
                <th style='min-width:200px;'>
                <div class='kiri_tabel'>
                <input style=\"width:50px;height:30px;float:left;margin-right:5px;margin-top:8px;\" type=\"number\" class=\"form-control kotak\" id=\"quantity_agenda2\" name=\"quantity_agenda\" min=\"1\" value=\"5\" max=\"100000\">
                <button class=\"btn btn-success shadow-sm kotak\" id=\"tampilbaris_agenda2\" style=\"float:left;margin-bottom:0px;\"><i class='fas fa-eye fa-sm text-white-100'></i></button>
				</div>
                </th>
				<th><input id=\"cari_agenda\" class='form-control' type=\"text\" placeholder=\"Search..\"></th>
				<th></th>
                <th data-toggle=\"tooltip\" title=\"Tampilkan jumlah rekord agenda\">
                <div class='kanan_tabel'>
				<input type=\"number\" class=\"form-control kotak\" id=\"quantity_agenda\" name=\"quantity_agenda\" min=\"1\" value=\"5\" max=\"100000\" style=\"width:50px;height:30px;float:right;margin-right:-15px;\">
                </div>
                </th>
                <th class='kanan_tabel'>
                <button class=\"btn btn-success shadow-sm kotak\" id=\"tampilbaris_agenda\" style=\"float:left;margin-bottom:0px;\"><i class='fas fa-eye fa-sm text-white-100'></i></button>
				</th>
                <th class='kanan_tabel' colspan=2 data-toggle=\"tooltip\" title=\"Tambahkan agenda baru\"><button  data-toggle=\"modal\" data-target=\"#modal_baca_surat_new_tambah\" style=\"width:100%;\" class=\"btn btn-success shadow-sm kotak tambahkan_item_agenda\"><i class='fas fa-plus fa-sm text-white-100'></i> Agenda</button></th>
                </tr>
                <script>
                $(document).ready(function(){
                    $('#cari_agenda').on('keyup', function() {
                        var value = $(this).val().toLowerCase();
                        $('.cari_agenda_rekord').filter(function() {
                          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                      });
                      
                    $('#tampilbaris_agenda').click(function(){
                        var loading = $('#pra_tabel');
                        var tampilkan = $('#penampil_tabel');
                        var limit = $('#quantity_agenda').val();
                        tampilkan.hide();
                        loading.fadeIn(); 
                        $.post('".site_url('/Frontoffice/baca_agenda_limit_9001/tbagenda_kerja/0/')."'+limit,{ data:'okbro'},
                        function(data,status){
                            loading.fadeOut();
                            tampilkan.html(data);
                            tampilkan.fadeIn(2000);
                        });
                    });
                    $('#tampilbaris_agenda2').click(function(){
                        var loading = $('#pra_tabel');
                        var tampilkan = $('#penampil_tabel');
                        var limit = $('#quantity_agenda2').val();
                        tampilkan.hide();
                        loading.fadeIn(); 
                        $.post('".site_url('/Frontoffice/baca_agenda_limit_9001/tbagenda_kerja/0/')."'+limit,{ data:'okbro'},
                        function(data,status){
                            loading.fadeOut();
                            tampilkan.html(data);
                            tampilkan.fadeIn(2000);
                        });
                    });
                    $(\".tambahkan_item_agenda\").click(function(){
                        var loading = $(\"#pra_baca_surat_new_tambah\");
                        var tampilkan = $(\"#penampil_baca_surat_new_tambah\");
                        var limit=$(\"#quantity\").val();
                        tampilkan.hide();
                        loading.fadeIn(); 
                        $.post('".site_url("/Frontoffice/tambah_data_new_agenda2/".$table)."',{ data:\"okbro\"},
                        function(data,status){
                            loading.fadeOut();
                            tampilkan.html(data);
                            tampilkan.fadeIn(2000);
                        });
                    });

                    $('#tool_kanan_agenda').click(function(){
                        $('#head_agenda').toggle(1000);
                    });

                    $('#tool_kiri_agenda').click(function(){
                        $('#head_agenda').toggle(1000);
                    });
                  });
                </script>
            </thead>
			<thead>
				<tr>
                <th class='kiri_tabel'>
                <button class=\"btn btn-danger shadow-sm kotak\" id=\"tool_kiri_agenda\" data-toggle=\"tooltip\" title=\"Tampilkan tool tambahan\"><i class='fas fa-cog fa-sm text-white-100'></i></button>
                </th>
				<th>id</th>
                <th>Acara</th>
				<th>Tempat</th>
				<th>Tanggal:Jam</th>
				<th>Sifat Kegiatan</th>
				<th class='kanan_tabel'>Aksi</th>
				<th class='kanan_tabel'>
                <button style='float:right;' class=\"btn btn-danger shadow-sm kotak\" id=\"tool_kanan_agenda\" data-toggle=\"tooltip\" title=\"Tampilkan tool tambahan\"><i class='fas fa-cog fa-sm text-white-100'></i></button>
                </th>
				</tr>
			</thead>
			<tbody>";
			$fields=$this->CI->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			$query=$this->CI->model_frommyframework->query_dengan_limit($table,$mulai_rekord,$jumlah_rekord,$fields[0],$order);
			foreach ($query->result() as $row)
			{
                    echo "
                    <div class='cari_agenda_rekord'>
					<tr class='cari_agenda_rekord' data-toggle=\"tooltip\" title=\"Klik untuk menampilkan surat agenda\" style='cursor:pointer;'>
                    <th class='kiri_tabel'>
                    <button data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" style=\"width:40px; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm cetak_laporan_agenda$row->idagenda_kerja\"><i class='fas fa-download fa-sm text-white-50'></i></button>
                    <br><button data-toggle=\"modal\" data-target=\"#modal_kirim_agenda\" data-toggle=\"tooltip\" title=\"Unggah Media (foto, Video atau Surat baru)\" style=\"width:40px; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm unggah_file_baru$row->idagenda_kerja\"><i class='fas fa-upload fa-sm text-white-50'></i></button>
                    <br><button data-toggle=\"tooltip\" title=\"Tampilkan foto, video dan rincian lengkap kegiatan\" style=\"width:40px;\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm rincian_agenda$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-50'></i></button>
                    
                    </th>    
                    <div >
                    <td class='buka_surat_rekord$row->idagenda_kerja' data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\">".$row->idagenda_kerja."</td>
                    <td style='max-width:400px;' class='buka_surat_rekord$row->idagenda_kerja' data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\">".$row->acara_kegiatan."<br>
                    </td>
					<td class='buka_surat_rekord$row->idagenda_kerja' data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\">".$row->tempat."</td>
                    <td style='min-width:200px;' class='buka_surat_rekord$row->idagenda_kerja' data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\">Mulai Tgl: ".$row->tanggal_mulai."<br>
                    Jam: ".$row->jam_mulai."<br>
                    Akhir Tgl: ".$row->tanggal_selesai."<br>
                    Jam: ".$row->jam_selesai."<br>
                    </td>
                    <td class='buka_surat_rekord$row->idagenda_kerja' data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\">";
                    //echo "Urgensi: <br>";
                    if($row->urgensi=='penting'){
                        echo "<span class='badge badge-warning badge-counter' style='font-size:13px;'>
                        $row->urgensi</span>";
                    }elseif($row->urgensi=='sangat penting'){
                        echo "<span class='badge badge-danger badge-counter' style='font-size:13px;'>
                        $row->urgensi</span>";
                    }elseif($row->urgensi=='rutin'){
                        echo "<span class='badge badge-primary badge-counter' style='font-size:13px;'>
                        $row->urgensi</span>";
                    }elseif($row->urgensi=='dapat diwakilkan'){
                        echo "<span class='badge badge-success badge-counter' style='font-size:13px;'>
                        $row->urgensi</span>";
                    }elseif($row->urgensi=='biasa'){
                        echo "<span class='badge badge-info badge-counter' style='font-size:13px;'>
                        $row->urgensi</span>";
                    }elseif($row->urgensi=='Yang lain (others)'){
                        echo "<span class='badge badge-counter' style='font-size:13px;background:#29a99f;color:white;'>
                        $row->urgensi</span>";
                    }else{
                        echo "<span class='badge badge-success badge-counter' style='font-size:13px;'>
                        $row->urgensi</span>";
                    }
                    echo "<br>Status: <br>";
                    echo $row->status_kegiatan;
                    echo "</td>       
                    </div>             
                    <td colspan=2 width='150px' class='kanan_tabel'>
                    <!--<button data-toggle=\"modal\" data-target=\"#modal_kirim_agenda\" data-toggle=\"tooltip\" title=\"Teruskan ke Kaban\" style=\"width:100%;\" class=\"btn btn-danger shadow-sm kotak\" id=\"teruskan_kaban$row->idagenda_kerja\"><i class='fas fa-paper-plane fa-sm text-white-100'></i> Kirim</button>
                    -->
                    <button data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" style=\"width:100%; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm cetak_laporan_agenda$row->idagenda_kerja\"><i class='fas fa-download fa-sm text-white-50'></i> Cetak</button>
                    <button data-toggle=\"modal\" data-target=\"#modal_kirim_agenda\" data-toggle=\"tooltip\" title=\"Unggah Media (foto, Video atau Surat baru)\" style=\"width:100%; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm unggah_file_baru$row->idagenda_kerja\"><i class='fas fa-upload fa-sm text-white-50'></i> Foto</button>
                    <button data-toggle=\"tooltip\" title=\"Tampilkan foto, video dan rincian lengkap kegiatan\" style=\"width:100%;\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm rincian_agenda$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-50'></i> More..</button>
                    
                    </td>
                    </tr>
                    <tr id='tr$row->idagenda_kerja'>
                    <td colspan='9'>
                    <div id='menu_pengayaan'>
                    <button data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" data-toggle=\"tooltip\" title=\"Tampilkan foto kegiatan\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"lihat_foto_agenda$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-50'></i> Lihat Foto</button>
                    <button data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\" data-toggle=\"tooltip\" title=\"Tampilkan video kegiatan\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"lihat_video_agenda$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-50'></i> Lihat Video</button>
                    <button data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\" data-toggle=\"tooltip\" title=\"Tampilkan semua surat kegiatan\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm buka_surat_rekord$row->idagenda_kerja\" ><i class='fas fa-eye fa-sm text-white-50'></i> Lihat Surat</button>
                    <button data-toggle=\"tooltip\" title=\"Tampilkan menu lebih lengkap\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"lihat_rincian_menu_lengkap$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-50'></i> Lihat Menu Lebih Lengkap</button>
                    <div id='menu_lebih_lengkap$row->idagenda_kerja'>
                    <button id='edit_keterangan_foto$row->idagenda_kerja' data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" data-toggle=\"tooltip\" title=\"Edit keterangan pada foto\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm\"><i class='fas fa-eye fa-sm text-white-50'></i> Edit Keterangan Foto</button>
                    <button id='hapus_media$row->idagenda_kerja' data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" data-toggle=\"tooltip\" title=\"Hapus Foto, Video atau Surat\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm\"><i class='fas fa-eye fa-sm text-white-50'></i> Hapus Media pada Agenda</button>
                    <button data-toggle=\"modal\" data-target=\"#modal_kirim_agenda\" data-toggle=\"tooltip\" title=\"Kirim ke OPD lain atau ke bidang\" style=\"width:100%;margin-bottom:5px;\" class=\"btn btn-info btn-sm shadow-sm kotak\" id=\"teruskan_kaban$row->idagenda_kerja\"><i class='fas fa-paper-plane fa-sm text-white-100'></i> Kirim undangan acara ke OPD atau yang lain</button>
                    <button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new_narasi\" class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm kotak\" id=\"narasi_agenda$row->idagenda_kerja\"><i class='fas fa-sticky-note fa-sm text-white-100'></i>  Narasi Acara</button>
                    <button data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" data-toggle=\"tooltip\" title=\"Edit atau hapus acara ini\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm\" id='edit_hapus_acara$row->idagenda_kerja'><i class='fas fa-cog fa-sm text-white-50'></i> Edit/Hapus Acara ini keseluruhan</button>
                    <button data-toggle=\"tooltip\" title=\"Tampilkan rincian kegiatan\" style=\"width:100%;margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm\" id='lihat_rincian_lengkap$row->idagenda_kerja'><i class='fas fa-eye fa-sm text-white-50'></i> Lihat Rincian Data Agenda</button>
                    </div>
                    </div>
                        <style>
                        #menu_lebih_lengkap$row->idagenda_kerja{
                            display:none;
                        }
                        </style>
                    </td>
                    </tr>

					<tr id='tr2$row->idagenda_kerja' style='display:none;'>
                    <td align=center><i class='fas fa-eye fa-lg text-white-100'></i><br>
                    <button style=\"width:100%; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"tutup_rincian2$row->idagenda_kerja\"><i class='fas fa-eye-slash fa-sm text-white-100'></i><br>Tutup</button>
                    <button data-toggle=\"tooltip\" title=\"Perbaharui tampilan acara ini\" style=\"width:100%; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"refresh$row->idagenda_kerja\"><span style='color:green;'><i class='fas fa-leaf fa-sm text-white-100'></i><br>Refresh</span></button>
                    <!--
                    <div data-toggle=\"tooltip\" title=\"Buka edit narasi kegiatan\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new_narasi\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"narasi_agenda$row->idagenda_kerja\"><i class='fas fa-sticky-note fa-sm text-white-100'></i><br>Narasi Acara</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Buka surat pendukung kegiatan\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"buka_surat_agenda$row->idagenda_kerja\"><i class='fas fa-envelope-open fa-sm text-white-100'></i><br>Buka Surat</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Lihat foto-foto agenda\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"lihat_foto_agenda2$row->idagenda_kerja\"><i class='fas fa-images fa-sm text-white-100'></i><br>Lihat Foto</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Lihat video-video acara\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"lihat_video_agenda2$row->idagenda_kerja\"><i class='fas fa-file-video fa-sm text-white-100'></i><br>Lihat Video</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Buat laporan kegiatan dalam ppt, pdf, word atau excel\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\" id=\"cetak_laporan_agenda2$row->idagenda_kerja\"><i class='fas fa-print fa-sm text-white-100'></i><br>Cetak Laporan</button>
                    </div>
                    -->
                    </td>
					<td colspan=6 >
                    Rincian:<br>";
                    //foreach($fields as $key=>$nama_kolom){
                    //    echo "<b>".ucwords(implode(' ',explode('_',$nama_kolom))).":</b> ".$row->$nama_kolom."<br>";
                    //}    
                    echo "
                    <center>
                        <div id='pra_rincian_agenda$row->idagenda_kerja' style='width:40%;display:none;' align='center' >
                        <!--<i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>-->
                        <i class='fa-3x fas fa-spinner fa-spin' style='margin-top:100px;'></i>
                        <!--
                        <div class='progress' style='margin-top:50px; height:20px'>
                            <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                            mohon tunggu...
                            </div>
                        </div>
                        -->
                        </div>
                    </center>
                    <div id=penampil_rincian_agenda$row->idagenda_kerja align='left' style='width:100%;overflow:auto;'></div>
                    ";                
                    echo "
					</td>
                    </tr>
                    </div><!--untuk class cari_agenda_rekord-->";
                    
                    $direktori_surat=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->direktori_surat_pendukung));
                    $direktori_foto=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->direktori_foto_yg_menyertai));
                    $direktori_video=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->direktori_video_yang_menyertai));
                    $keterangan_foto=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->keterangan_foto));
                    $rekord=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkapsulasiData($row));
                    $video=explode('.',$row->direktori_video_yang_menyertai);
                    $surat=explode('.',$row->direktori_surat_pendukung);
                    $foto=explode('.',$row->direktori_foto_yg_menyertai);
                    $token=$this->CI->enkripsi->enkapsulasiData('andisinra');//ini untuk sementara, rencananya token ini berubah-ubah
					
                    echo "
					<style>
						#tr$row->idagenda_kerja{
							display:none;
						}
                    </style>
                    
                    <script>
                    $(document).ready(function(){
                        $(\"#narasi_agenda$row->idagenda_kerja\").click(function(){
                            var loading = $(\"#pra_baca_surat_new_narasi\");
                            var tampilkan = $(\"#penampil_baca_surat_new_narasi\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/tes_menampilkan_editor_agenda/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#teruskan_kaban$row->idagenda_kerja\").click(function(){
                            var loading1 = $(\"#pra_kirim_agenda1\");
                            var tampilkan1 = $(\"#penampil_kirim_agenda1\");
                            tampilkan1.hide();
                            loading1.fadeIn(); 
                            $.post('".site_url('Frontoffice/tampilkan_pilihan_kirim_foto_video/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading1.fadeOut();
                                tampilkan1.html(data);
                                tampilkan1.fadeIn(2000);
                            });
                        });
                        
                        $(\".unggah_file_baru$row->idagenda_kerja\").click(function(){
                            var loading1 = $(\"#pra_kirim_agenda1\");
                            var tampilkan1 = $(\"#penampil_kirim_agenda1\");
                            tampilkan1.hide();
                            loading1.fadeIn(); 
                            $.post('".site_url('Frontoffice/unggah_file_baru_9001/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading1.fadeOut();
                                tampilkan1.html(data);
                                tampilkan1.fadeIn(2000);
                            });
                        });
                    });
                    </script>

                    <script>
                    var loaded$row->idagenda_kerja = false;
					$(document).ready(function(){
                    if(!loaded$row->idagenda_kerja){
                        $(\".buka_surat_rekord$row->idagenda_kerja\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_surat)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#hapus_media$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/tampilkan_list_foto_dan_video_9001/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#edit_keterangan_foto$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/edit_keterangan_foto/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#rincian_agenda2$row->idagenda_kerja\").click(function(){
                            var loading = $(\"#pra_rincian_agenda$row->idagenda_kerja\");
                            var tampilkan = $(\"#penampil_rincian_agenda$row->idagenda_kerja\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/buka_rincian_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#lihat_rincian_lengkap$row->idagenda_kerja\").click(function(){
                            var loading = $(\"#pra_rincian_agenda$row->idagenda_kerja\");
                            var tampilkan = $(\"#penampil_rincian_agenda$row->idagenda_kerja\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/buka_rincian_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#refresh$row->idagenda_kerja\").click(function(){
                            var loading = $(\"#pra_rincian_agenda$row->idagenda_kerja\");
                            var tampilkan = $(\"#penampil_rincian_agenda$row->idagenda_kerja\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/buka_rincian_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\".cetak_laporan_agenda$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/buka_papan_cetak_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#edit_hapus_acara$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/edit_hapus_acara_keseluruhan/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#cetak_laporan_agenda2$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/buka_papan_cetak_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

						$(\".rincian_agenda$row->idagenda_kerja\").click(function(){
                            $('#tr$row->idagenda_kerja').toggle(500);
                            $('#tr2$row->idagenda_kerja').hide(500);
                            /*Agar fungsi toggle ini bisa bekerja, hapus kelas d-sm-inline-block pada #rincian_agenda */
                        });
                        $(\"#lihat_rincian_lengkap$row->idagenda_kerja\").click(function(){
                            $('#tr2$row->idagenda_kerja').toggle(500);
                            /*Agar fungsi toggle ini bisa bekerja, hapus kelas d-sm-inline-block pada #rincian_agenda */
						});
                        $(\"#lihat_rincian_menu_lengkap$row->idagenda_kerja\").click(function(){
                            $('#menu_lebih_lengkap$row->idagenda_kerja').toggle(500);
						});
						$(\"#tutup_rincian$row->idagenda_kerja\").click(function(){
							$('#tr$row->idagenda_kerja').fadeOut(500);
                        });
						$(\"#tutup_rincian2$row->idagenda_kerja\").click(function(){
							$('#tr2$row->idagenda_kerja').fadeOut(500);
                        });

                        $(\"#buka_surat_agenda$row->idagenda_kerja\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_surat)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#lihat_foto_agenda$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_foto)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tes_penampil_carousel/'.$direktori_foto.'/'.$keterangan_foto)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#lihat_video_agenda$row->idagenda_kerja\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_video)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#lihat_foto_agenda2$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_foto)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tes_penampil_carousel/'.$direktori_foto.'/'.$keterangan_foto)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#lihat_video_agenda2$row->idagenda_kerja\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_video)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\".tutup_suara\").click(function(){
							var tampilkan = $(\"#penampil_baca_surat_new\");
                            tampilkan.html('');
                        });

                        $(\".tutup_suara_perbesar\").click(function(){
                            var tampilkan = $(\"#penampil_baca_surat_new1\");
                            tampilkan.html('');
                        });

                        $(\".tutup_suara_foto\").click(function(){
                            var tampilkan = $(\"#penampil_foto_sedang\");
                            tampilkan.html('');
                        });

                    }
                    loaded$row->idagenda_kerja=true;

                        });
                    </script>";
			}
			echo "
			</tbody>
			</table>
        ";
        echo "
            <!-- Modal FOTO SEDANG-->
            <div class='modal fade' id='modal_foto_sedang' role='dialog' style='z-index:100000;'>
                <div class='modal-dialog'>
                
                <!-- Modal content-->
                <div class='modal-content'>
                    <div class='modal-header'>
                    <h6 class='modal-title'>BKD Provinsi Sulawesi Selatan</h6>
                    <button type='button' class='close tutup_suara_foto' data-dismiss='modal'>&times;</button>
                    </div>
                    <div class='modal-body' >
                    <center>
                    <div id='pra_foto_sedang' style='width:65%;' align='center' >
                    <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                    <!--
                    <div class='progress' style='margin-top:50px; height:20px'>
                        <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                        mohon tunggu...
                        </div>
                    </div>
                    -->
                    </center>
                    <div id=penampil_foto_sedang align='center' style='width:100%;'></div>
                    </div>
                    <div class='modal-footer'>
                    <button type='button' class='btn btn-primary tutup_suara_foto' data-dismiss='modal'>Close</button>
                    </div>
                </div>
                
                </div>
            </div>
        ";
        echo "
            <!-- Modal KIRIM AGENDA-->
            <div class='modal fade' id='modal_kirim_agenda' role='dialog' style='z-index:100000;'>
                <div class='modal-dialog'>
                
                <!-- Modal content-->
                <div class='modal-content'>
                    <div class='modal-header'>
                    <h6 class='modal-title'>BKD Provinsi Sulawesi Selatan</h6>
                    <button type='button' class='close tutup_suara_foto' data-dismiss='modal'>&times;</button>
                    </div>
                    <div class='modal-body' >

                    <!--batas untuk menampilkan pemilihan apakah hendak ikutkan foto, video atau tidak pilihan_foto_video-->
                    <center>
                    <div id='pra_kirim_agenda1' style='width:65%;' align='center' >
                    <!--<i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>-->
                    <div class='progress' style='margin-top:50px; height:20px'>
                        <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                        mohon tunggu...
                        </div>
                    </div>
                    </center>
                    <div id=penampil_kirim_agenda1 align='center' style='width:100%;'></div>     

                    </div>
                    <div class='modal-footer'>
                    <button type='button' class='btn btn-primary tutup_suara_foto' data-dismiss='modal'>Close</button>
                    </div>
                </div>
                
                </div>
            </div>
        ";
        
        echo "
        <!-- Modal Baca Surat -->
        <div class='modal fade' id='modal_baca_surat_new' role='dialog' style='z-index:100000;'>
            <div class='modal-dialog modal-lg'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                <button type='button' class='close tutup_suara' data-dismiss='modal'>&times;</button>
                </div>
                <div id='header_pra_baca_surat_new' class='modal-header' style='width:90%;margin-left:40px;margin-right:20px;overflow:auto;' align='left' >
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new align='center' style='width:100%;height:500px;overflow:auto;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary' id=\"perbesar_modal\" onclick='$(\"#modal_baca_surat_new_perbesar\").modal(\"show\");'>Perbesar</button>
                <button type='button' class='btn btn-primary tutup_suara' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";
    echo "
        <!-- Modal Baca Surat Perbesar -->
        <div class='modal fade' id='modal_baca_surat_new_perbesar' role='dialog' style='z-index:100001;'>
            <div class='modal-dialog modal-lg' style='max-width:100%;'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
                <button type='button' class='close tutup_suara_perbesar' data-dismiss='modal'>&times;</button>
                </div>
                <div id='header_pra_baca_surat_new1' class='modal-header' style='width:90%;margin-left:40px;margin-right:20px;overflow:auto;' align='left' >
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new1' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new1 align='center' style='width:100%;height:500px;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary tutup_suara_perbesar' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";
        
    echo "
    <!-- Modal Edit Narasi Acara -->
        <div class='modal fade' id='modal_baca_surat_new_narasi' role='dialog' style='z-index:100000;'>
            <div class='modal-dialog modal-lg'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                <button type='button' class='close tutup_suara' data-dismiss='modal'>&times;</button>
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new_narasi' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new_narasi align='center' style='width:100%;height:500px;overflow:auto;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary tutup_suara' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";

    echo "
    <!-- Modal Tambah Narasi Acara -->
        <div class='modal fade' id='modal_baca_surat_new_tambah' role='dialog' style='z-index:100000;'>
            <div class='modal-dialog modal-lg'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                <button type='button' class='close tutup_suara' data-dismiss='modal'>&times;</button>
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new_tambah' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new_tambah align='center' style='width:100%;overflow:auto;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary tutup_suara' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";
	}

    //===========================================END REVISI-9001:ruangkaban-view============================================================
    
    //==============FUNGSI-FUNGSI UNTUK MENAMPILKAN AGENDA==================================================================================
	public function baca_agenda_view($table='tbagenda_kerja',$mulai_rekord=0,$jumlah_rekord=20,$order='desc'){
        /**
         * Fungsi ini sudah obselet
         * Karena tabel tbagenda_kerja sudah diubah susunan nama kolomnya sehingga bisa menyebabkan error jika menggunakan fungsi ini
         * Untuk menggunakan fungsi ini hilangkan kode yang menggunakan nama kolom lama pada tabel, atau sesuaikan dengan nama kolom baru
         * InShaa Allah itu saja.
         * Yaitu adanya penambahan kolom jam untuk mulai dan sampai.
         */

		echo "
			<h5>Agenda Hari Ini</h5>           
            <table class='table table-hover table-striped' width=\"100%\">
            <style>
            #head_agenda{
                display:none;
            }
            </style>
            <thead>
				<tr id='head_agenda'>
				<th></th>
				<th></th>
				<th></th>
                <th colspan=2 data-toggle=\"tooltip\" title=\"Tampilkan jumlah rekord agenda\">
				<button class=\"btn btn-success shadow-sm kotak\" id=\"tampilbaris_agenda\" style=\"margin-left:5px;float:right;\"><i class='fas fa-eye fa-sm text-white-100'></i></button>
				<input type=\"number\" class=\"form-control kotak\" id=\"quantity_agenda\" name=\"quantity_agenda\" min=\"1\" value=\"5\" max=\"100000\" style=\"width:50px;float:right;\">
                </th>
				<th colspan=2 data-toggle=\"tooltip\" title=\"Tambahkan agenda baru\"><button  data-toggle=\"modal\" data-target=\"#modal_baca_surat_new_tambah\" style=\"width:100%;\" class=\"btn btn-success shadow-sm kotak\" id=\"tambahkan_item_agenda\"><i class='fas fa-plus fa-sm text-white-100'></i> Agenda</button></th>
                </tr>
                <script>
                $(document).ready(function(){
                    $('#tampilbaris_agenda').click(function(){
                        var loading = $('#pra_tabel');
                        var tampilkan = $('#penampil_tabel');
                        var limit = $('#quantity_agenda').val();
                        tampilkan.hide();
                        loading.fadeIn(); 
                        $.post('".site_url('/Frontoffice/baca_agenda_limit/tbagenda_kerja/0/')."'+limit,{ data:'okbro'},
                        function(data,status){
                            loading.fadeOut();
                            tampilkan.html(data);
                            tampilkan.fadeIn(2000);
                        });
                    });
                    $(\"#tambahkan_item_agenda\").click(function(){
                        var loading = $(\"#pra_baca_surat_new_tambah\");
                        var tampilkan = $(\"#penampil_baca_surat_new_tambah\");
                        var limit=$(\"#quantity\").val();
                        tampilkan.hide();
                        loading.fadeIn(); 
                        $.post('".site_url("/Frontoffice/tambah_data_new_agenda2/".$table)."',{ data:\"okbro\"},
                        function(data,status){
                            loading.fadeOut();
                            tampilkan.html(data);
                            tampilkan.fadeIn(2000);
                        });
                    });

                    $('#tool_agenda').click(function(){
                        $('#head_agenda').toggle(1000);
                    });
                  });
                </script>
			</thead>
			<thead>
				<tr>
				<th>id</th>
				<th>Acara</th>
				<th>Tempat</th>
				<th>Tanggal</th>
				<th>Urgensi</th>
				<th>Rincian</th>
				<th>
                <button class=\"btn btn-danger shadow-sm kotak\" id=\"tool_agenda\" data-toggle=\"tooltip\" title=\"Tampilkan tool tambahan\"><i class='fas fa-cog fa-sm text-white-100'></i></button>
                </th>
				</tr>
			</thead>
			<tbody>";
			$fields=$this->CI->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			$query=$this->CI->model_frommyframework->query_dengan_limit($table,$mulai_rekord,$jumlah_rekord,$fields[0],$order);
			foreach ($query->result() as $row)
			{
					echo "
					<tr>
					<td>".$row->idagenda_kerja."</td>
					<td>".$row->acara_kegiatan."</td>
					<td>".$row->tempat."</td>
					<td>".$row->tanggal."</td>
					<td>".$row->urgensi."</td>
                    <td colspan=2>
                    <button data-toggle=\"tooltip\" title=\"Tampilkan rincian kegiatan\" style=\"width:100%; margin-bottom:5px;\" class=\"btn btn-success shadow-sm kotak\" id=\"rincian_agenda$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-100'></i> Rincian</button>
                    <button data-toggle=\"modal\" data-target=\"#modal_kirim_agenda\" data-toggle=\"tooltip\" title=\"Teruskan ke Kaban\" style=\"width:100%;\" class=\"btn btn-danger shadow-sm kotak\" id=\"teruskan_kaban$row->idagenda_kerja\"><i class='fas fa-paper-plane fa-sm text-white-100'></i> Kirim</button>
                    </td>
					</tr>
					<tr id='tr$row->idagenda_kerja'>
                    <td align=center><i class='fas fa-eye fa-lg text-white-100'></i><br>
                    <button style=\"width:100%; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"tutup_rincian$row->idagenda_kerja\"><i class='fas fa-eye-slash fa-sm text-white-100'></i><br>Tutup</button>
                    <button data-toggle=\"tooltip\" title=\"Perbaharui tampilan acara ini\" style=\"width:100%; margin-bottom:5px;\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"refresh$row->idagenda_kerja\"><span style='color:green;'><i class='fas fa-leaf fa-sm text-white-100'></i><br>Refresh</span></button>
                    <div data-toggle=\"tooltip\" title=\"Buka edit narasi kegiatan\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new_narasi\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"narasi_agenda$row->idagenda_kerja\"><i class='fas fa-sticky-note fa-sm text-white-100'></i><br>Narasi Acara</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Buka surat pendukung kegiatan\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"buka_surat_agenda$row->idagenda_kerja\"><i class='fas fa-envelope-open fa-sm text-white-100'></i><br>Buka Surat</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Lihat foto-foto agenda\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"lihat_foto_agenda$row->idagenda_kerja\"><i class='fas fa-images fa-sm text-white-100'></i><br>Lihat Foto</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Lihat video-video acara\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\" class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm kotak\" id=\"lihat_video_agenda$row->idagenda_kerja\"><i class='fas fa-file-video fa-sm text-white-100'></i><br>Lihat Video</button>
                    </div>
                    <div data-toggle=\"tooltip\" title=\"Buat laporan kegiatan dalam ppt, pdf, word atau excel\"><button style=\"width:100%; margin-bottom:5px;\" data-toggle=\"modal\" data-target=\"#modal_foto_sedang\" class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\" id=\"cetak_laporan_agenda$row->idagenda_kerja\"><i class='fas fa-print fa-sm text-white-100'></i><br>Cetak Laporan</button>
                    </div>
                    </td>
					<td colspan=6 >
                    Rincian:<br>";
                    //foreach($fields as $key=>$nama_kolom){
                    //    echo "<b>".ucwords(implode(' ',explode('_',$nama_kolom))).":</b> ".$row->$nama_kolom."<br>";
                    //}    
                    echo "
                    <center>
                        <div id='pra_rincian_agenda$row->idagenda_kerja' style='width:40%;display:none;' align='center' >
                        <!--<i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>-->
                        <i class='fa-3x fas fa-spinner fa-spin' style='margin-top:100px;'></i>
                        <!--
                        <div class='progress' style='margin-top:50px; height:20px'>
                            <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                            mohon tunggu...
                            </div>
                        </div>
                        -->
                        </div>
                    </center>
                    <div id=penampil_rincian_agenda$row->idagenda_kerja align='left' style='width:100%;overflow:auto;'></div>
                    ";                
                    echo "
					</td>
                    </tr>";
                    
                    
                    $direktori_surat=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->direktori_surat_pendukung));
                    $direktori_foto=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->direktori_foto_yg_menyertai));
                    $direktori_video=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->direktori_video_yang_menyertai));
                    $keterangan_foto=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($row->keterangan_foto));
                    $rekord=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkapsulasiData($row));
                    $video=explode('.',$row->direktori_video_yang_menyertai);
                    $surat=explode('.',$row->direktori_surat_pendukung);
                    $foto=explode('.',$row->direktori_foto_yg_menyertai);
                    $token=$this->CI->enkripsi->enkapsulasiData('andisinra');//ini untuk sementara, rencananya token ini berubah-ubah
					
                    echo "
					<style>
						#tr$row->idagenda_kerja{
							display:none;
						}
                    </style>
                    
                    <script>
                    $(document).ready(function(){
                        $(\"#narasi_agenda$row->idagenda_kerja\").click(function(){
                            var loading = $(\"#pra_baca_surat_new_narasi\");
                            var tampilkan = $(\"#penampil_baca_surat_new_narasi\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/tes_menampilkan_editor_agenda/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#teruskan_kaban$row->idagenda_kerja\").click(function(){
                            var loading1 = $(\"#pra_kirim_agenda1\");
                            var tampilkan1 = $(\"#penampil_kirim_agenda1\");
                            tampilkan1.hide();
                            loading1.fadeIn(); 
                            $.post('".site_url('Frontoffice/tampilkan_pilihan_kirim_foto_video/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading1.fadeOut();
                                tampilkan1.html(data);
                                tampilkan1.fadeIn(2000);
                            });
                        });
                    });
                    </script>

                    <script>
                    var loaded$row->idagenda_kerja = false;
					$(document).ready(function(){
                    if(!loaded$row->idagenda_kerja){
                        $(\"#rincian_agenda$row->idagenda_kerja\").click(function(){
                            var loading = $(\"#pra_rincian_agenda$row->idagenda_kerja\");
                            var tampilkan = $(\"#penampil_rincian_agenda$row->idagenda_kerja\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/buka_rincian_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#refresh$row->idagenda_kerja\").click(function(){
                            var loading = $(\"#pra_rincian_agenda$row->idagenda_kerja\");
                            var tampilkan = $(\"#penampil_rincian_agenda$row->idagenda_kerja\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('/Frontoffice/buka_rincian_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

                        $(\"#cetak_laporan_agenda$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/buka_papan_cetak_agenda/tbagenda_kerja/idagenda_kerja/'.$row->idagenda_kerja)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });

						$(\"#rincian_agenda$row->idagenda_kerja\").click(function(){
                            $('#tr$row->idagenda_kerja').toggle(500);
                            /*Agar fungsi toggle ini bisa bekerja, hapus kelas d-sm-inline-block pada #rincian_agenda */
						});
						$(\"#tutup_rincian$row->idagenda_kerja\").click(function(){
							$('#tr$row->idagenda_kerja').fadeOut(500);
                        });

                        $(\"#buka_surat_agenda$row->idagenda_kerja\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_surat)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#lihat_foto_agenda$row->idagenda_kerja\").click(function(){
							var loading = $(\"#pra_foto_sedang\");
							var tampilkan = $(\"#penampil_foto_sedang\");
							tampilkan.hide();
                            loading.fadeIn(); 

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_foto)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tes_penampil_carousel/'.$direktori_foto.'/'.$keterangan_foto)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#lihat_video_agenda$row->idagenda_kerja\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_video)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\".tutup_suara\").click(function(){
							var tampilkan = $(\"#penampil_baca_surat_new\");
                            tampilkan.html('');
                        });

                        $(\".tutup_suara_perbesar\").click(function(){
                            var tampilkan = $(\"#penampil_baca_surat_new1\");
                            tampilkan.html('');
                        });

                        $(\".tutup_suara_foto\").click(function(){
                            var tampilkan = $(\"#penampil_foto_sedang\");
                            tampilkan.html('');
                        });

                    }
                    loaded$row->idagenda_kerja=true;

                        });
                    </script>";
			}
			echo "
			</tbody>
			</table>
        ";
        echo "
            <!-- Modal FOTO SEDANG-->
            <div class='modal fade' id='modal_foto_sedang' role='dialog' style='z-index:100000;'>
                <div class='modal-dialog'>
                
                <!-- Modal content-->
                <div class='modal-content'>
                    <div class='modal-header'>
                    <h6 class='modal-title'>BKD Provinsi Sulawesi Selatan</h6>
                    <button type='button' class='close tutup_suara_foto' data-dismiss='modal'>&times;</button>
                    </div>
                    <div class='modal-body' >
                    <center>
                    <div id='pra_foto_sedang' style='width:65%;' align='center' >
                    <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                    <!--
                    <div class='progress' style='margin-top:50px; height:20px'>
                        <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                        mohon tunggu...
                        </div>
                    </div>
                    -->
                    </center>
                    <div id=penampil_foto_sedang align='center' style='width:100%;'></div>
                    </div>
                    <div class='modal-footer'>
                    <button type='button' class='btn btn-primary tutup_suara_foto' data-dismiss='modal'>Close</button>
                    </div>
                </div>
                
                </div>
            </div>
        ";
        echo "
            <!-- Modal KIRIM AGENDA-->
            <div class='modal fade' id='modal_kirim_agenda' role='dialog' style='z-index:100000;'>
                <div class='modal-dialog'>
                
                <!-- Modal content-->
                <div class='modal-content'>
                    <div class='modal-header'>
                    <h6 class='modal-title'>BKD Provinsi Sulawesi Selatan</h6>
                    <button type='button' class='close tutup_suara_foto' data-dismiss='modal'>&times;</button>
                    </div>
                    <div class='modal-body' >

                    <!--batas untuk menampilkan pemilihan apakah hendak ikutkan foto, video atau tidak pilihan_foto_video-->
                    <center>
                    <div id='pra_kirim_agenda1' style='width:65%;' align='center' >
                    <!--<i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>-->
                    <div class='progress' style='margin-top:50px; height:20px'>
                        <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                        mohon tunggu...
                        </div>
                    </div>
                    </center>
                    <div id=penampil_kirim_agenda1 align='center' style='width:100%;'></div>     

                    </div>
                    <div class='modal-footer'>
                    <button type='button' class='btn btn-primary tutup_suara_foto' data-dismiss='modal'>Close</button>
                    </div>
                </div>
                
                </div>
            </div>
        ";
        
        echo "
        <!-- Modal Baca Surat -->
        <div class='modal fade' id='modal_baca_surat_new' role='dialog' style='z-index:100000;'>
            <div class='modal-dialog modal-lg'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                <button type='button' class='close tutup_suara' data-dismiss='modal'>&times;</button>
                </div>
                <div id='header_pra_baca_surat_new' class='modal-header' style='width:90%;margin-left:40px;margin-right:20px;overflow:auto;' align='left' >
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new align='center' style='width:100%;height:500px;overflow:auto;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary' id=\"perbesar_modal\" onclick='$(\"#modal_baca_surat_new_perbesar\").modal(\"show\");'>Perbesar</button>
                <button type='button' class='btn btn-primary tutup_suara' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";
    echo "
        <!-- Modal Baca Surat Perbesar -->
        <div class='modal fade' id='modal_baca_surat_new_perbesar' role='dialog' style='z-index:100001;'>
            <div class='modal-dialog modal-lg' style='max-width:100%;'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
                <button type='button' class='close tutup_suara_perbesar' data-dismiss='modal'>&times;</button>
                </div>
                <div id='header_pra_baca_surat_new1' class='modal-header' style='width:90%;margin-left:40px;margin-right:20px;overflow:auto;' align='left' >
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new1' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new1 align='center' style='width:100%;height:500px;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary tutup_suara_perbesar' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";
        
    echo "
    <!-- Modal Edit Narasi Acara -->
        <div class='modal fade' id='modal_baca_surat_new_narasi' role='dialog' style='z-index:100000;'>
            <div class='modal-dialog modal-lg'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                <button type='button' class='close tutup_suara' data-dismiss='modal'>&times;</button>
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new_narasi' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new_narasi align='center' style='width:100%;height:500px;overflow:auto;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary tutup_suara' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";

    echo "
    <!-- Modal Tambah Narasi Acara -->
        <div class='modal fade' id='modal_baca_surat_new_tambah' role='dialog' style='z-index:100000;'>
            <div class='modal-dialog modal-lg'>
            
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                <button type='button' class='close tutup_suara' data-dismiss='modal'>&times;</button>
                </div>
                <div class='modal-body'>
                <center>
                <div id='pra_baca_surat_new_tambah' style='width:65%;' align='center' >
                <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                <!--
                <div class='progress' style='margin-top:50px; height:20px'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                    mohon tunggu...
                    </div>
                </div>
                -->
                </center>
                <div id=penampil_baca_surat_new_tambah align='center' style='width:100%;overflow:auto;'></div>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-primary tutup_suara' data-dismiss='modal'>Close</button>
                </div>
            </div>
            
            </div>
        </div>
    ";
	}

	//==============END FUNGSI-FUNGSI AGENDA==================================================================
    
    //===============================================UNTUK NEW AGENDA========================================================================
    //Maksudnya tidak dirancangan untuk menampilkan foto pegawai atau apa saja, jadi tabel biasa aja.
    function penampil_tabel_untuk_agenda_new($table,$kolom_cari,$nama_kolom_direktori_surat,$array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        //var_dump($Recordset1);
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            //var_dump($Recordset);
        
            unset($Recordset1);
            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                //echo "<h4>Kelola Surat Masuk</h4>";
                //print_r($_SERVER);
                echo "<div ".$array_atribut[0]." ><table width=\"100%\" ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' width=\"150px\" ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    if(in_array($value,$kolom_cari)){
                        echo "<th > $value </th>";
                    }
                } 
                echo "</tr>";
                
                
                $i=0;
                foreach($Recordset as $keyisi=>$isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;//1111
                    echo "<td valign=top>";
                    //$this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Verifikasi',$value_selected_combo='',$submenu='rincian_penampil_tabel',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "
                    <button style=\"width:30px;margin-top:5px;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm kotak\" id=\"eyebuka$keyisi\"><i class='fas fa-eye fa-sm text-white-100'></i></button>
                    <button style=\"width:30px;margin-top:5px;\" class=\"d-sm-inline-block btn btn-sm btn-primary shadow-sm kotak\" id=\"edit$keyisi\" data-toggle=\"modal\" data-target=\"#modal_cruid_new_agenda\"><i class='fas fa-pen-nib fa-sm text-white-100'></i></button>
                    <button style=\"width:30px;margin-top:5px;\" class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\" id=\"hapus$keyisi\" data-toggle=\"modal\" data-target=\"#modal_cruid_new_agenda_kecil\"><i class='fas fa-trash fa-sm text-white-100'></i></button>
                    ";
                    echo "</td>";
                    $data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\",\"nama_tabel\":\"".$table."\",\"nama_kolom_id\":\"".$tampung_key[0]."\" }";
                    $data_json=$this->CI->enkripsi->enkapsulasiData($data_ajax);
                    echo "
                    <script>
                    $(document).ready(function(){
                        $(\"#edit$keyisi\").click(function(){
                        var loading = $(\"#pra_cruid_new_agenda\");
                        var tampilkan = $(\"#penampil_cruid_new_agenda\");
                        var limit=$(\"#quantity\").val();
                        tampilkan.hide();
                        loading.fadeIn(); 
                        $.post('".site_url("/Frontoffice/edit_cruid_agenda")."',{data_json:\"$data_json\" },
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
                    <script>
                    $(document).ready(function(){
                        $(\"#hapus$keyisi\").click(function(){
                        var loading = $(\"#pra_cruid_new_agenda_kecil\");
                        var tampilkan = $(\"#penampil_cruid_new_agenda_kecil\");
                        var limit=$(\"#quantity\").val();
                        tampilkan.hide();
                        loading.fadeIn(); 
                        $.post('".site_url("/Frontoffice/hapus_data_cruid_agenda")."',{data_json:\"$data_json\" },
                        function(data,status){
                            loading.fadeOut();
                            tampilkan.html(data);
                            tampilkan.fadeIn(2000);
                        });
                        });
                        });
                    </script>
                    ";
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        if(in_array($k,$kolom_cari)){
                            echo "<td align=left valign=top>".$isi[$k]."</td>";
                        }
                    } 
                    echo "</tr>";
                    echo "<tr id=\"rincian_surat$keyisi\">";
                    echo "<td width='150px'><div align=center><i class='fas fa-eye fa-lg text-white-100'></i></div><br>
                    <button class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\"  style=\"width:100%;\" id=\"eyeSlashTutup$keyisi\"><i class='fas fa-eye-slash fa-sm text-white-100'></i> Tutup</button><br>
                    <button class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm kotak\" style=\"width:100%;margin-top:5px;\" id=\"isiSurat$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-envelope-open text-white-100'></i> <br>Buka Surat</button><br>
                    <button class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm kotak\" style=\"width:100%;margin-top:5px;\" id=\"berkasSurat$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-images text-white-100'></i> <br>Foto</button><br>
                    <button class=\"d-sm-inline-block btn btn-sm btn-primary shadow-sm kotak\" style=\"width:100%;margin-top:5px;\" id=\"videoPendukung$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-file-video text-white-100'></i> <br>Video</button>
                    </td>";
                    echo "<td colspan=".sizeof($kolom_cari)."><h5>Rincian Agenda</h5>";
                    foreach ($tampung_key as $k) {
                        //if(!in_array($k,$kolom_cari)){
                            echo "<span style=\"font-weight:bold;\">".ucwords(implode(' ',explode('_',$k)))."</span>: ".$isi[$k]."<br>";
                        //}
                    } 
                    echo "</td>";
                    //echo "<td ></td>";
                    echo "</tr>";
                    echo "
                        <style>
                            #rincian_surat$keyisi{
                                display:none;
                            }
                        </style>
                    ";
                    $direktori_surat=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($isi[$nama_kolom_direktori_surat['surat']]));
                    $direktori_foto=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($isi[$nama_kolom_direktori_surat['foto']]));
                    $direktori_video=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($isi[$nama_kolom_direktori_surat['video']]));
                    $video=explode('.',$isi[$nama_kolom_direktori_surat['video']]);
                    $surat=explode('.',$isi[$nama_kolom_direktori_surat['surat']]);
                    $foto=explode('.',$isi[$nama_kolom_direktori_surat['foto']]);

                    echo "
                    <script>
					$(document).ready(function(){
						$(\"#eyebuka$keyisi\").click(function(){
							$('#rincian_surat$keyisi').toggle(1000);
						});
						$(\"#eyeSlashTutup$keyisi\").click(function(){
							$('#rincian_surat$keyisi').fadeOut(1000);
                        });
                        $(\"#isiSurat$keyisi\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_surat)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });
                        ";
                    echo "
                        $(\"#berkasSurat$keyisi\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_foto)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_foto)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });

                        $(\"#videoPendukung$keyisi\").click(function(){
                            var header = $(\"#header_pra_baca_surat_new\");
                            var header1 = $(\"#header_pra_baca_surat_new1\");
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
                            loading.fadeIn(); 
                            header.html('');
                            header1.html('');

                            /*$.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},*/
                            $.post('".site_url('Frontoffice/tampilkan_list_surat_agenda/'.$direktori_video)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                //if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
                                if(in_array($surat[sizeof($surat)-1],array('ogg','pdf','png','jpg','wav','mp4','html','htm','gif','bmp','vid','mp3','sql','txt'))) echo "tampilkan1.html(data)";
					echo "		});
                        });
                        
                        
                                                
						});
					</script>
                    ";

                }
                echo "</table></div>";
                echo "
                    <!-- Modal FOTO SEDANG-->
                    <div class='modal fade' id='modal_foto_sedang' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog'>
                        
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h6 class='modal-title'>BKD Provinsi Sulawesi Selatan</h6>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_foto_sedang' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_foto_sedang align='center' style='width:100%;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary btn-sm' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal CRUID -->
                    <div class='modal fade' id='modal_cruid_new_agenda' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog modal-lg'>
                        
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_cruid_new_agenda' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_cruid_new_agenda align='center' style='width:100%;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal CRUID KECIL-->
                    <div class='modal fade' id='modal_cruid_new_agenda_kecil' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog modal-sm'>
                        
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h6 class='modal-title'>BKD Provinsi Sulawesi Selatan</h6>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_cruid_new_agenda_kecil' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_cruid_new_agenda_kecil align='center' style='width:100%;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary btn-sm' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal Baca Surat -->
                    <div class='modal fade' id='modal_baca_surat_new' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog modal-lg'>
                        
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h5 class='modal-title'>BKD Provinsi Sulawesi Selatan</h5>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div id='header_pra_baca_surat_new' class='modal-header' style='width:90%;margin-left:40px;margin-right:20px;overflow:auto;' align='left' >
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_baca_surat_new' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_baca_surat_new align='center' style='width:100%;height:500px;overflow:auto;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id=\"perbesar_modal\" onclick='$(\"#modal_baca_surat_new_perbesar\").modal(\"show\");'>Perbesar</button>
                            <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal Baca Surat Perbesar -->
                    <div class='modal fade' id='modal_baca_surat_new_perbesar' role='dialog' style='z-index:100001;'>
                        <div class='modal-dialog modal-lg' style='max-width:100%;'>
                        
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div id='header_pra_baca_surat_new1' class='modal-header' style='width:90%;margin-left:40px;margin-right:20px;overflow:auto;' align='left' >
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_baca_surat_new1' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_baca_surat_new1 align='center' style='width:100%;height:500px;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
            } else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }

    function form_general_2_vertikal_non_iframe_new_agenda($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        $perekam_id_untuk_button_ajax=array();
        //print_r($komponen);
        $this->CI->session->set_userdata('i',0);
        $this->CI->session->set_userdata('data_nama_tambahan',NULL);
        $nama_komponen=array();
        if (!$atribut_form) {
        echo "<form id=\"file_upload_form\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" >";
        } else {echo "<form ".$atribut_form." >";}
        echo "<table ".$atribut_table['table']."><tr ".$atribut_table['tr']." ><th ".$atribut_table['th']."><b><center>".$judul."</center></b></th></tr>";
        foreach ($komponen as $key => $k) {
            if (!is_array($k)) {
                //Seluruh bagian ini adalah jika $k bukan array, artinya untuk setiap komponen ke-i, komponen[$i] tidak dirinci lagi menjadi komponen[$i][0]=type, komponen[$i][1]=name, komponen[$i][2]=class, komponen[$i][3]=id,... dan seterusnya.
                //Yaitu bahwa komponen[$i] bukan komponen[$i]=array(type,name,class,id,...)
                //Tetapi langsung bahwa komponen[$i]=tipe-komponen-ke-i=$k, bukan array rincian. secara umum untuk kasus ini sama dengan perilku fungsi form_general().
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$key."</td></tr>";
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                //if ($k=="area" || $k=="combo_manual" || $k=="password" || $k=="text" || $k=="hidden" || $k=="checkbox" || $k=="radio" || $k=="file" || $k=="button" || $k=="submit" || $k=="reset") {
                    $this->buat_komponen_form($k,implode("_",array("name",implode("_",explode(" ",$key)))),$class,implode("_",array("id",implode("_",explode(" ",$key)))),$atribut='',$event='',$label='',$array_option,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                //}
                echo "</td></tr>";
                $perekam_id_untuk_button_ajax[$key]['id']=implode("_",array("id",implode("_",explode(" ",$key))));
                array_push($nama_komponen,implode("_",array("name",implode("_",explode(" ",$key)))));
            } else {
                //echo "OK MASUK SINI BRO";
                //Seluruh bagian ini adalah jika $k adalah array, artinya bahwa sebuah komponen ke-i yaitu komponen[$i] dirinci oleh sebuah array, tidak lagi langsung hanya menyebut type seperti di atas. atau seperti fungsi general_form.
                //Jadi sebuah komponen ke-i yaitu komponen[$i]=array(type,name,class,id,...).
                if ($k==$komponen[0]) {
                    //Karena $k adalah array yaitu array(type,name,class,id,...), jika array $k adalah array komponen[0], yaitu komponen untuk id, yang dalam myframework dijadikan autoincrement pada tabel basisdata
                    //maka dia dijadikan readonly. 
                    //echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                    //echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    //$this->buat_komponen_form($komponen[$key][0],"no_name".$key,$class,$komponen[$key][3],"disabled",$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    $this->buat_komponen_form("hidden",$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    //echo "</td></tr>";
                } else {
                    if($komponen[$key][0]!=='hidden'){
                        echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                        echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    }
                    //if($komponen[$key][0]=='combo_database'){print_r($komponen[$key][5]);}
                    //1111
                    $this->buat_komponen_form($komponen[$key][0],$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    //($type 0,$nama_komponen 1,$class='form-control',$id 3,$atribut 4,$event 5,$label 6,$value 7,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax='',$data_ajax=NULL)
                    if($komponen[$key][0]!=='hidden'){
                        echo "</td></tr>";
                    }
                }
                $perekam_id_untuk_button_ajax[$key]['id']=$komponen[$key][3];
                array_push($nama_komponen,$komponen[$key][1]);
            }
        }
        $data_nama=$this->CI->enkripsi->enkapsulasiData($nama_komponen);
        $this->buat_komponen_form("hidden",'data_nama',$class,'data_nama','','','',$data_nama,'',$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        //Bagian membuat tombol di bagian bawah form, semua tombol ditampung oleh $tombol.
        echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']." align=\"right\">";
        foreach ($tombol as $key => $k) {
            //INGAT! $tombol adalah array tombol, untuk tombol ke-i adalah $tombol[$i]=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            //Jangan $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            $this->buat_komponen_form($tombol[$key][0],$tombol[$key][1],$tombol[$key][2],$tombol[$key][3],$tombol[$key][4],$tombol[$key][5],$tombol[$key][6],$tombol[$key][7],$tombol[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        }
        echo "</td></tr>";
        echo "</table></form>";//print_r($perekam_id_untuk_button_ajax);
    }//form_non_iframe

    //===============================================END UNTUK NEW AGENDA====================================================================
        
    //===============================================UNTUK NEW CRUID========================================================================
    //Maksudnya tidak dirancangan untuk menampilkan foto pegawai atau apa saja, jadi tabel biasa aja.
    function penampil_tabel_no_foto_untuk_surat_masuk_frontoffice_surat_masuk ($kolom_cari,$nama_kolom_direktori_surat,$array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        //var_dump($Recordset1);
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            //var_dump($Recordset);
        
            unset($Recordset1);
            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                //echo "<h4>Kelola Surat Masuk</h4>";
                //print_r($_SERVER);
                echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    if(in_array($value,$kolom_cari)){
                        echo "<th > $value </th>";
                    }
                } 
                echo "</tr>";
                
                
                $i=0;
                foreach($Recordset as $keyisi=>$isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;
                    echo "<td valign=top>";
                    //$this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Verifikasi',$value_selected_combo='',$submenu='rincian_penampil_tabel',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "
                    <button class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm kotak\" id=\"eyebuka$keyisi\"><i class='fas fa-eye fa-sm text-white-100'></i> Lihat</button>
                    ";
                    echo "</td>";
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        if(in_array($k,$kolom_cari)){
                            echo "<td align=left valign=top>".$isi[$k]."</td>";
                        }
                    } 
                    echo "</tr>";
                    echo "<tr id=\"rincian_surat$keyisi\">";
                    echo "<td width='100px'><div align=center><i class='fas fa-eye fa-lg text-white-100'></i></div><br>
                    <button class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm kotak\"  style=\"width:100%;\" id=\"eyeSlashTutup$keyisi\"><i class='fas fa-eye-slash fa-sm text-white-100'></i> Tutup</button><br>
                    <button class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm kotak\" style=\"width:100%;margin-top:5px;\" id=\"isiSurat$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-envelope-open fa-sm text-white-100'></i> <br>Buka Surat</button><br>
                    <button class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm kotak\" style=\"width:100%;margin-top:5px;\" id=\"berkasSurat$keyisi\" data-toggle=\"modal\" data-target=\"#modal_baca_surat_new\"><i class='fas fa-folder-open fa-sm text-white-100'></i> <br>Buka Berkas Pendukung</button>
                    </td>";
                    echo "<td colspan=".sizeof($kolom_cari)."><h5>Rincian Surat</h5>";
                    foreach ($tampung_key as $k) {
                        if(!in_array($k,$kolom_cari)){
                            echo "<span style=\"font-weight:bold;\">".ucwords(implode(' ',explode('_',$k)))."</span>: ".$isi[$k]."<br>";
                        }
                    } 
                    echo "</td>";
                    //echo "<td ></td>";
                    echo "</tr>";
                    echo "
                        <style>
                            #rincian_surat$keyisi{
                                display:none;
                            }
                        </style>
                    ";
                    $direktori_surat=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($isi[$nama_kolom_direktori_surat['surat']]));
                    $direktori_berkas=$this->CI->enkripsi->strToHex($this->CI->enkripsi->enkripSimetri_data($isi[$nama_kolom_direktori_surat['berkas']]));
                    $surat=explode('.',$isi[$nama_kolom_direktori_surat['surat']]);
                    $berkas=explode('.',$isi[$nama_kolom_direktori_surat['berkas']]);

                    echo "
                    <script>
					$(document).ready(function(){
						$(\"#eyebuka$keyisi\").click(function(){
							$('#rincian_surat$keyisi').toggle(1000);
						});
						$(\"#eyeSlashTutup$keyisi\").click(function(){
							$('#rincian_surat$keyisi').fadeOut(1000);
                        });
                        $(\"#isiSurat$keyisi\").click(function(){
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
							loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_surat)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                if($surat[sizeof($surat)-1]=='pdf'||$surat[sizeof($surat)-1]=='png'||$surat[sizeof($surat)-1]=='jpg'||$surat[sizeof($surat)-1]=='html'||$surat[sizeof($surat)-1]=='htm'||$surat[sizeof($surat)-1]=='bmp'||$surat[sizeof($surat)-1]=='gif'||$surat[sizeof($surat)-1]=='mp4'||$surat[sizeof($surat)-1]=='mp3'||$surat[sizeof($surat)-1]=='vid'||$surat[sizeof($surat)-1]=='wav') echo "            tampilkan1.html(data);";
					echo "		});
                        });
                        ";
                    echo "
                        $(\"#berkasSurat$keyisi\").click(function(){
							var loading = $(\"#pra_baca_surat_new\");
							var tampilkan = $(\"#penampil_baca_surat_new\");
							var loading1 = $(\"#pra_baca_surat_new1\");
							var tampilkan1 = $(\"#penampil_baca_surat_new1\");
							tampilkan.hide();
							loading.fadeIn(); 
                            $.post('".site_url('Frontoffice/tesopenpdf/'.$direktori_berkas)."',{ data:\"okbro\"},
							function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                                loading1.fadeOut();
                                ";
                                if($berkas[sizeof($berkas)-1]=='pdf'||$berkas[sizeof($berkas)-1]=='png'||$berkas[sizeof($berkas)-1]=='jpg'||$berkas[sizeof($berkas)-1]=='html'||$berkas[sizeof($berkas)-1]=='htm'||$berkas[sizeof($berkas)-1]=='bmp'||$berkas[sizeof($berkas)-1]=='gif'||$berkas[sizeof($berkas)-1]=='mp4'||$berkas[sizeof($berkas)-1]=='mp3'||$berkas[sizeof($berkas)-1]=='vid'||$berkas[sizeof($berkas)-1]=='wav') echo "            tampilkan1.html(data);";
                    echo "		});
                        });
                        
                                                
						});
					</script>
                    ";

                }
                
                echo "</table></div>";
                echo "
                    <!-- Modal Baca Surat -->
                    <div class='modal fade' id='modal_baca_surat_new' role='dialog' style='z-index:100000;'>
                        <div class='modal-dialog modal-lg'>
                        
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_baca_surat_new' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_baca_surat_new align='center' style='width:100%;height:500px;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id=\"perbesar_modal\" onclick='$(\"#modal_baca_surat_new_perbesar\").modal(\"show\");'>Perbesar</button>
                            <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                echo "
                    <!-- Modal Baca Surat Perbesar -->
                    <div class='modal fade' id='modal_baca_surat_new_perbesar' role='dialog' style='z-index:100001;'>
                        <div class='modal-dialog modal-lg' style='max-width:100%;'>
                        
                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h4 class='modal-title'>BKD Provinsi Sulawesi Selatan</h4>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            </div>
                            <div class='modal-body'>
                            <center>
                            <div id='pra_baca_surat_new1' style='width:65%;' align='center' >
                            <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
                            <!--
                            <div class='progress' style='margin-top:50px; height:20px'>
                                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                                mohon tunggu...
                                </div>
                            </div>
                            -->
                            </center>
                            <div id=penampil_baca_surat_new1 align='center' style='width:100%;height:500px;'></div>
                            </div>
                            <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
            } else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }
    //===============================================END UNTUK NEW CRUID====================================================================
    
    //============================TAMBAHAN BARU 1 ROUND 2==============================================
    
    public function penarik_semua_nama_kolom_sebuah_tabel_view($table){
        return $this->CI->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
    }

    public function penarik_semua_nama_tabel_view(){
        return $this->CI->model_frommyframework->penarik_semua_nama_tabel();
    }

    public function pemeriksa_tabel_ada_kolom_tertentu_view($kolom='password'){
        return $this->CI->model_frommyframework->pemeriksa_tabel_ada_kolom_tertentu($kolom);
    }

    public function table_pemilik_kolom_password_dan_kolom_perujuk_view($kolom='password'){
        $listTable_ada_kolom_password=$this->pemeriksa_tabel_ada_kolom_tertentu_view($kolom);
		$buffer=array();
		foreach($listTable_ada_kolom_password as $key=>$unit){
			$buffer[$key]=array($unit);
		}
		
		//Tampung tabel-tabel pemilik kolom password dengan kolom perujuk misal username atau id($field[0]):
		foreach($buffer as $key=>$unit){
			$fields=$this->penarik_semua_nama_kolom_sebuah_tabel_view($unit[0]);
			in_array('username',$fields)?array_push($buffer[$key],'username'):array_push($buffer[$key],$fields[0]);
        }
        return $buffer;
    }

    //============================END TAMBAHAN BARU 1==========================================
    
    //ALHAMDULILLAH SUDAH DITES SUKSES
    /**
     * @param path, jelas, posisi file di aplikasi kita
     * @param charset, jelas.
     * @param content, jelas
     * @return menghasilkan link script, ref, meta di header halaman depan. 
     */
    function bootstrap_css($path='/login/css/css/bootstrap.css'){
        echo "<link href=\"".base_url($path)."\" rel=\"stylesheet\" >";
    }

    //ALHAMDULILLAH SUDAH DITES SUKSES
    function fontawesome_css($path='/assets/fontawesome-free/css/all.min.css'){
        echo "<link href=\"".base_url($path)."\" rel=\"stylesheet\" type=\"text/css\">";
    }

    //ALHAMDULILLAH SUDAH DITES SUKSES
    function jquery($path='/login/vendor/jquery/jquery-3.2.1.min.js'){
        echo "<script src=\"".base_url($path)."\"> </script>";
    }

    //ALHAMDULILLAH SUDAH DITES SUKSES
    function bootstrap_js($path='/login/vendor/bootstrap/js/bootstrap.min.js'){
        echo "<script src=\"".base_url($path)."\"> </script>";
    }

    //ALHAMDULILLAH SUDAH DITES SUKSES
    function charset($charset='utf-8'){
        echo "<meta charset=\"".$charset."\">";
    }

    //ALHAMDULILLAH SUDAH DITES SUKSES
    function viewport($content='width=device-width, initial-scale=1'){
        echo "<meta name=\"viewport\" content=\"".$content."\">";
    }

    function header_lengkap_bootstrap($charset='utf-8',$content='width=device-width, initial-scale=1',$path_boostrap_js='/login/vendor/bootstrap/js/bootstrap.min.js',$path_jquery='/login/vendor/jquery/jquery-3.2.1.min.js',$path_fontawesome='/assets/fontawesome-free/css/all.min.css',$path_bootstrap_css='/login/css/css/bootstrap.css'){
        $this->charset($charset);
        $this->viewport($content);
        $this->bootstrap_js($path_boostrap_js);
        $this->jquery($path_jquery);
        $this->fontawesome_css($path_fontawesome);
        $this->bootstrap_css($path_bootstrap_css);
    }

    function js_standar_frommyfarmework($path1='assets/js/jcustom/event.js',$path2='assets/js/jquery/jquery-1.4.2.min.js',$path3='assets/js/jcustom/pembungkus_ajax_jquery.js'){
        $this->js_lain($path1);
        $this->js_lain($path2);
        $this->js_lain($path3);
    }

    function css_lain($path){
        echo "<link href=\"".base_url($path)."\" rel=\"stylesheet\">";
    }

    function js_lain($path){
        echo "<script src=\"".base_url($path)."\"> </script>";
    }

    /**
    CONTOH PENGGUNAAN FUNGSI-FUNGSI YANG DIGUNAKAN DI BAWAH:
    $this->header_lengkap_bootstrap_controller();
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
    $this->form_combo_database_controller('tes_combo_database','form-control','tes_combo_database',"style=\"margin:20px\"",array('username','email'),'admin','noeng.hunter@gmail.com');

     */
    //CATATAN JENIS INPUT YANG BISA DIGUNAKAN UNTUK HTML5
    /**
     *<input type="button">
     *<input type="checkbox">
     *<input type="color">
     *<input type="date">
     *<input type="datetime-local">
     *<input type="email">
     *<input type="file">
     *<input type="hidden">
     *<input type="image">
     *<input type="month">
     *<input type="number">
     *<input type="password">
     *<input type="radio">
     *<input type="range">
     *<input type="reset">
     *<input type="search">
     *<input type="submit">
     *<input type="tel">
     *<input type="text">
     *<input type="time">
     *<input type="url">
     *<input type="week">
     */
    /**
     * @param nama_komponen, nama komponen yang digunakan saat mengirim nilai komponen
     * @param class, nama kelas css, bisa menggunakan kelas bootstrap
     * @param id, id dari komponen
     * @param atribut, atribut untuk komponen tetapi lebih umum, misal $atribut="style=\"margin:20px\" class=\"bootstrap_class\" ... dst"
     * @param array_option, menampung nilai-nilai value pada <option value=..> dan nilai-nilai label pada <option value=..>label</option>
     * @param event, label pada combo_database yang ditunjuk sebagai muncul pertama atau terpilih default.
     * @param array_value_label, $array_value_label= array(keycheckbox=>$check), $keycheckbox sebagai nilai dan $check sebagai label dari checkbox
     * @param disable, berisi array $keycheckbox yang mau didisable pada checkbox. $disable=array($keycheckbox), berisi daftar semua keycheckbox yang mau didisable.
     * @return menghasilkan komponen. 
     */
    function form_input($type,$nama_komponen,$class='form-control',$id,$atribut,$event){
        echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event.">";
    }

    function form_input_checkbox($type,$nama_komponen,$class='checkbox',$id,$atribut,$event,$array_value_label_checkbox,$disable_checkbox){
        foreach($array_value_label_checkbox as $keycheckbox => $check){
        echo "<div class=\"".$class."\" ".$atribut." ".$event.">";
        if(in_array($check,$disable_checkbox)){echo "<label><input type=\"".$type."\" id=\"".$id."_".$keycheckbox."\" name=\"".$nama_komponen."_".$keycheckbox."\" value=\"".$keycheckbox."\" disabled>".$check."</label>";} else {
            echo "<label><input type=\"".$type."\" id=\"".$id."_".$keycheckbox."\" name=\"".$nama_komponen."_".$keycheckbox."\" value=\"".$keycheckbox."\">".$check."</label>";
        }
        echo "</div>";
        }
    }

    function form_input_radio($type,$nama_komponen,$class='radio',$id,$atribut,$event,$array_value_label_radio,$disable_checkbox){
        foreach($array_value_label_radio as $keyradio => $check){
        echo "<div class=\"".$class."\" ".$atribut." ".$event.">";
        if(in_array($check,$disable_checkbox)){echo "<label><input type=\"".$type."\" id=\"".$id."_".$keyradio."\" name=\"".$nama_komponen."\" value=\"".$keyradio."\" disabled>".$check."</label>";} else {
            echo "<label><input type=\"".$type."\" id=\"".$id."_".$keyradio."\" name=\"".$nama_komponen."\" value=\"".$keyradio."\">".$check."</label>";
        }
        echo "</div>";
        }
    }

    function form_area($nama_komponen,$class='form-control',$id,$atribut){
        echo "<textarea name=\"".$nama_komponen."\" class=\"".$class."\" id=\"".$id."\" ".$atribut." ></textarea>";
    }

    function form_combo_manual($nama_komponen,$class='form-control',$id,$atribut,$array_option, $selected){
        echo "<select class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut.">";
        foreach ($array_option as $key => $value) {
            if($key==$selected){
                echo "<option value=\"".$value."\" selected>".$key."</option>";
            } else{
                echo "<option value=\"".$value."\">".$key."</option>";
            }
        }
        echo "</select>";}

    function kerangka_tabel1(){

    }

    //ALHAMDULILLAH SUDAH DITES.
    /**
     * @param nama_komponen, nama komponen untuk komponen combo_database
     * @param class, nama kelas css, bisa menggunakan kelas bootstrap
     * @param id, id dari komponen combo_database
     * @param atribut, atribut untuk komponen tetapi lebih umum, misal $atribut="style=\"margin:20px\" class=\"bootstrap_class\" ... dst"
     * @param kolom, array yang menampung kolom tabel yang digunakan pada <option value=$kolom[0]> dan kolom untuk label yaitu <option value=$kolom[0]>$kolom[1]</option>
     * @param tabel, nama tabel yang digunakan untuk mengisi komponen combo_database
     * @param selected, label pada combo_database yang ditunjuk sebagai muncul pertama atau terpilih default.
     * @return menghasilkan komponen combo_database untuk dipasang pada antarmuka atau halaman depan/front.  
     */
    function form_combo_database($type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected){
        $Recordset1=$this->CI->model_frommyframework->penarik_kolom_model($kolom[0], $kolom[1],$tabel);
        //$Recordset=$this->CI->model_frommyframework->konvers_recordset_PDOStatement_to_array($Recordset1);
        echo "<select class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut.">";
        $i=0;
        foreach ($Recordset1 as $k) {
            if($k[$kolom[0]]==$selected){
                echo "<option value=\"".$k[$kolom[0]]."\" selected >".$k[$kolom[1]]."</option>";
            }else{
                echo "<option value=\"".$k[$kolom[0]]."\">".$k[$kolom[1]]."</option>";
            }
            $i++;
        }
        echo "</select>";
    }
    

    /*
        CONTOH PENGGUNAAN SECARA UMUM: form_general_controller()
        $this->header_lengkap_bootstrap_controller();
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
		$this->form_general_controller($komponen,$atribut_form=" class=\"form-group\" ",$array_option,$atribut_table=array('table'=>" class=\"table table-hover\" ",'tr'=>'','td'=>''),$judul,$selected,$class='form-control',$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio);
		echo "</div>";
		*/
    //Form General saja
    /**
     * @param komponen, komponen-komponen yang mau ditampilkan di form pada halaman depan. berupa array tipe-tipe komponen yang mau ditampilkan, dimana $komponen=array($key=>$k) dimana $key=label komponen di form, $k=tipe komponen atau nama jenis komponen.
     * @param atribut_form, atribut untuk form tetapi lebih umum, misal untuk target pengiriman form atau misal $atribut_form="style=\"margin:20px\" class=\"bootstrap_class\" ... dst"
     * @param array_option, array yang menampung kolom tabel yang digunakan pada <option value=$kolom[0]> dan kolom untuk label yaitu <option value=$kolom[0]>$kolom[1]</option>
     * @param atribut_table, atribut untuk tabel, baris (tr), field (td) dan lebih umum, misal $atribut_table="style=\"margin:20px\" class=\"bootstrap_class\" ... dst"
     * @param judul, Judul form yang mau ditampilkan di halaman depan.
     * @param perekam, untuk menyimpan/merekam semua komponen yang telah digunakan pada form, lalu disimpan pada session untuk mungkin digunakan dimasa depan dalam umur aplikasi saat running.
     * @param selected, untuk digunakan combo_manual, dimana selected mendefinisikan label default sekaligus nilai default yang dimunculkan pertama kali pada combo_manual.
     * @return menghasilkan komponen combo_database untuk dipasang pada antarmuka atau halaman depan/front.  
     */
    function form_general($komponen,$atribut_form,$array_option,$atribut_table,$judul,$selected,$class='form-control',$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio) {
        $perekam=array();
        //$perekam=$this->CI->session->userdata('perekam');
        $penanda_awal=FALSE;
        $penanda_akhir=FALSE;
        echo "<form ".$atribut_form." ><table ".$atribut_table['table']." ><tr ><th colspan=2 scope=col align=center><b style=\"margin-bottom:2px;\">".$judul."</b></th></tr>";
        foreach ($komponen as $key => $k) {
        if (!is_array($k)) { //sisi ini sudah dites
            $keyx=explode(' ',$key);
            $id_name=implode("_",array_merge(array($k),$keyx));
            if ($k=="area") {
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']." >".$key."</td><td ".$atribut_table['td'].">";$this->form_area($id_name,$class,$id_name,"");echo "</td></tr>";
                $detail_rekam=array();
                $detail_rekam['nama_komponen']=$id_name;
                $detail_rekam['class']=$class;
                $detail_rekam['id']=$id_name;
                $detail_rekam['atribut']="";
                array_push($perekam,$detail_rekam);unset($detail_rekam);
            } 
            else if ($k=="combo_manual") {echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td'].">".$key."</td><th ".$atribut_table['td'].">";$this->form_combo_manual($id_name,$class,$id_name,"",$array_option,$selected);echo "</td></tr>";
                $detail_rekam=array();
                $detail_rekam['nama_komponen']=$id_name;
                $detail_rekam['class']=$class;
                $detail_rekam['id']=$id_name;
                $detail_rekam['atribut']="";
                $detail_rekam['selected']=$selected;array_push($perekam,$detail_rekam);unset($detail_rekam);
            }
            else if ($k=="image" || $k=="password" || $k=="text" || $k=="hidden" || $k=="url" || $k=="search" || $k=="file" || $k=="range" || $k=="tel") {
                echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td'].">".$key."</td><td ".$atribut_table['td'].">";$this->form_input($k,$id_name,$class,$id_name,"size=60", $event='');echo "</td></tr>";
                $detail_rekam=array();
                $detail_rekam['nama_komponen']=$id_name;
                $detail_rekam['class']=$class;
                $detail_rekam['id']=$id_name;
                $detail_rekam['atribut']="size=60 ";array_push($perekam,$detail_rekam);unset($detail_rekam);
            }
            else if ($k=="button" || $k=="submit" || $k=="reset") {
                if($k=='submit'){$class_button='btn btn-primary';}
                if($k=='reset'){$class_button='btn btn-info';}
                if($k=='button'){$class_button='btn btn-warning';}
                if(!$penanda_awal){echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']."></td><td ".$atribut_table['td'].">";$penanda_awal=TRUE;}
                $this->form_input($k,$id_name,$class_button,$id_name," style=\"width:20%;float:right;margin-left:2px;\" value=\"".$key."\"", $event='');$penanda_akhir=TRUE;
                $detail_rekam=array();
                $detail_rekam['nama_komponen']=$id_name;
                $detail_rekam['class']=$class_button;
                $detail_rekam['id']=$id_name;
                $detail_rekam['atribut']="width=20px";array_push($perekam,$detail_rekam);unset($detail_rekam);
            }
            else if ($k=="email" || $k=="date" || $k=="color" || $k=="datetime-local" || $k=="time" || $k=="week" || $k=="number" || $k=="month") {
                echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td'].">".$key."</td><td ".$atribut_table['td'].">";
                $this->form_input($k,$id_name,$class,$id_name," style=\"width:20%;min-width:150px;\" ", $event='');
                echo "</td></tr>";
                $detail_rekam=array();
                $detail_rekam['nama_komponen']=$id_name;
                $detail_rekam['class']=$class;
                $detail_rekam['id']=$id_name;
                $detail_rekam['atribut']="width=20px";array_push($perekam,$detail_rekam);unset($detail_rekam);
            }
            else if ($k=="checkbox") {
                if($k=='checkbox'){$class_checkradio='checkbox-inline';}
                echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td'].">".$key."</td><td ".$atribut_table['td'].">";
                //$this->form_input($k,$id_name,$class_checkradio,$id_name," style=\"width:20%;float:right;margin-left:2px;\" value=\"".$key."\"", $event='');
                $this->form_input_checkbox($k,$id_name,$class_checkradio,$id_name,$atribut='',$event='',$array_value_label_checkbox,$disable_checkbox);
                echo "</td></tr>";
                $detail_rekam=array();
                $detail_rekam['nama_komponen']=$id_name;
                $detail_rekam['class']=$class_checkradio;
                $detail_rekam['id']=$id_name;
                $detail_rekam['atribut']="width=20px";array_push($perekam,$detail_rekam);unset($detail_rekam);
            }
            else if ($k=="radio" ) {
                if($k=='radio'){$class_checkradio='radio-inline';}
                echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td'].">".$key."</td><td ".$atribut_table['td'].">";
                //$this->form_input($k,$id_name,$class_checkradio,$id_name," style=\"width:20%;float:right;margin-left:2px;\" value=\"".$key."\"", $event='');
                $this->form_input_radio($k,$id_name,$class_checkradio,$id_name,$atribut='',$event='',$array_value_label_radio,$disable_radio);
                echo "</td></tr>";
                $detail_rekam=array();
                $detail_rekam['nama_komponen']=$id_name;
                $detail_rekam['class']=$class_checkradio;
                $detail_rekam['id']=$id_name;
                $detail_rekam['atribut']="width=20px";array_push($perekam,$detail_rekam);unset($detail_rekam);
            }
        } else {
        } // is_array 
        }//$foreach
        if($penanda_akhir){echo "</td></tr>";}
        echo "</table></form>";
        $perekam_id_untuk_button_ajax=&$perekam;
        //print_r($perekam_id_untuk_button_ajax);
        return $perekam_id_untuk_button_ajax; //untuk diberikan ke button_ajax jika dia digunakan setelah form_general ini
        //$this->CI->session->set_userdata('perekam',$perekam);
    }

    /** CONTOH PENGGUNAAN FUNGSI SECARA UMUM UNTUK: buat_komponen_form().
        $this->header_lengkap_bootstrap_controller();
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
        
        /** CONTOH PENGGUNAAN FUNGSI UNTUK BUTTON_AJAX
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
        
        Button_ajax menggunakan fungsi javascript untuk pemanggilan ajax yang didefinisikan pada fungsi $this->js_standar_frommyfarmework().
        Rencana kedepan, menggantinya dengan js moderen (async, fetch atau promise secara umum).
        */
    //Fungsi General untuk pencetak komponen. ALHAMDULILLAH FUNGSI INI OK.
    function buat_komponen_form($type,$nama_komponen,$class='form-control',$id,$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax='',$data_ajax=NULL) {
        //static $perekam_id=array();
        switch ($type) {
        case ("date"):
            if(is_array($value)){$value='';};
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("email"):
            if(is_array($value)){$value='';};
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("image"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("tel"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("range"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("time"):
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("week"):
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("month"):
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("text"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("number"):
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("color"):
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("datetime-local"):
            if(is_array($value)){$value='';};
            $atribut=" style=\"width:20%; min-width:150px;\" ";
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("search"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("url"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;   
        case ("password"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("hidden"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("checkbox"):
            $class_checkbox='checkbox';
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class_checkbox."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("radio"):
            $class_radio='radio';
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class_radio."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("file"):
            if(is_array($value)){$value='';};
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event." value=\"".$value."\">";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value=''));
            break;
        case ("multi-file"):
            is_array($value)?$value='':NULL;
            $i='';
            $j=$this->CI->session->userdata('i');
            $this->CI->session->set_userdata('i',$j+1);
            echo "<input type=\"file\" style=\"margin-bottom:5px;width:100%;\" class=\"".$class."\" id=\"".$id.$i."\" name=\"".$nama_komponen.$i."\" ".$atribut." ".$event." value=\"".$value."\"><button type=\"button\" id=\"tambah".$id.$i."\" style=\"width:100%;\" class=\"btn btn-sm btn-success shadow-sm\"><i class='fas fa-plus fa-sm text-white-100'></i> Tambah file</button>";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value=''));
            $class_transfer=$this->CI->enkripsi->strToHex($class);
            $id_transfer=$this->CI->enkripsi->strToHex($id);
            $nama_komponen_transfer=$this->CI->enkripsi->strToHex($nama_komponen);
            echo "
            <center>
              <div id='pra_tambah_file".$id.$i."' style='width:40%;display:none;' align='center' >
              <div class='progress' style='margin-bottom:10px;height:20px;'>
                <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
                mohon tunggu...
                </div>
              </div>
              </div>
            </center>
            <div id=penampil_tambah_file".$id.$i." align='center' style='width:100%;'></div>
            ";
            echo "
			<script>
                $(document).ready(function(){
                    $(\"#tambah".$id.$i."\").click(function(){
                    var loading = $(\"#pra_tambah_file".$id.$i."\");
                    var tampilkan = $(\"#penampil_tambah_file".$id.$i."\");
                    var button = $(\"#tambah".$id.$i."\");
                    var limit=$(\"#quantity\").val();
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('".site_url("/Frontoffice/tambah_file")."',{ class:\"".$class_transfer."\", id:\"".$id_transfer."\", nama_komponen:\"".$nama_komponen_transfer."\" },
                    function(data,status){
                        loading.fadeOut();
                        button.fadeOut();
                        tampilkan.html(data);
                        tampilkan.fadeIn(2000);
                    });
                    });
                    });
                </script>
            ";    
            break;
        case ("area"):
            if(is_array($value)){$value='';};
            echo "<textarea class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event." >".$value."</textarea>";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));	
            break;
        case ("combo_manual"):
            echo "<select class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event." >";
            foreach ($value as $key => $nilai) {
            if ($nilai==$value_selected_combo) {echo "<option value=\"".$nilai."\"  selected=\"selected\">".$nilai."</option>";} else {
            echo "<option value=\"".$nilai."\">".$nilai."</option>";}
            }echo "</select>";
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("combo_database"):
            $kolom=array($value[0],$value[1]);
            $tabel=$value[2];
            $selected=$value_selected_combo;
            $this->form_combo_database($type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected);
            //array_push($perekam_id,rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value));
            break;
        case ("button_iframe"):
            //Muat parameter ke dalam cara URI CI:
            //Hati-hati menggunakan ini, karena itu berarti method yang mau dipanggil pada $target_ajax harus memiliki jumlah argumen yang sama dengan ukuran $perekam_id_untuk_button_ajax. 
            $target_ajax.="/";
            foreach ($perekam_id_untuk_button_ajax as $isi){
                $target_ajax.=$isi."/";
            }
            $target_ajax=rtrim($target_ajax,"/");

            echo "
                <link href=".base_url('/login/css/css/bootstrap.css')." rel=\"stylesheet\" type=\"text/css\">
                <script src=".base_url('/login/vendor/jquery/jquery-3.2.1.min.js')."></script>
                <script src=".base_url('/login/vendor/bootstrap/js/bootstrap.min.js')."></script>
                ";
                
            echo "<a href=\"".site_url($target_ajax)."\" target=\"upload_target\" ><input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." data-toggle=\"modal\" data-target=\"#myModal\" ".$event." value=\"".$value."\"/></a>";
            echo "
                    <!-- Modal -->
                    <div class=\"modal fade\" id=\"myModal\" role=\"dialog\">
                        <div class=\"modal-dialog modal-lg\">
                        
                        <!-- Modal content-->
                        <div class=\"modal-content\">
                            <div class=\"modal-header\">
                            <h4 class=\"modal-title\">Modal Header</h4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                            </div>
                            <div class=\"modal-body\">
                            <iframe id=\"upload_target\" name=\"upload_target\" src=\"\" style=\"width:100%;height:100%;border:0px solid #fff;\"></iframe>
                            </div>
                            <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
            echo "";
            break;
        case ("submit"):
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." data-toggle=\"modal\" data-target=\"#myModal_submit\" ".$event."  value=\"".$value."\">";
            //$this->CI->session->userdata('teks_modal')!==NULL?$teks_modal=$this->CI->session->userdata('teks_modal'):$teks_modal='';
            echo "
                <!-- Modal Submit -->
                <div class=\"modal fade\" id=\"myModal_submit\" role=\"dialog\" style=\"z-index:10000000;\">
                    <div class=\"modal-dialog modal-sm\">
                    
                    <!-- Modal content-->
                    <div class=\"modal-content\">
                        <div class=\"modal-body\">
                        <center>
                        <div id='pra_submit' style='width:65%;' align='center' >
                        <div>$label</div>
                        <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                            <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                            mohon tunggu...
                            </div>
                        </div>
                        </div>
                        </center>
                        <div id=penampil1_submit align=\"center\" style='width:100%;'></div>
                        </div>
                        <div class=\"modal-footer\">
                        <!--<button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\" >Close</button>-->
                        </div>
                    </div>
                    
                    </div>
                </div>
                ";
                //$this->CI->session->set_userdata('teks_modal',NULL);
            break;
        case ("reset"):
            echo "<input type=\"".$type."\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." ".$event."  value=\"".$value."\">";
            break;
        case ("button_ajax"):
            echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." value=\"".$value."\" ";
            echo "onclick='";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "var ".$isi['id']."=$(\"#".$isi['id']."\").val();";}echo "tampilkandata(\"GET\",\"".site_url($target_ajax)."\",\"pilihan=".$submenu."&aksi=".$aksi."&proses=proses";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "&".$isi['id']."=".$isi['id'];}echo "\"";echo ",\"#penampil\",\"#pra\")'";
            echo " />";
            break;	
        case ("button_ajax2"):
            echo "<script>";
            echo "
            function tampilkandata (metode,url_file,data,target,pra) {
                var loading = $(pra);
                var tampilkan = $(target);
                tampilkan.hide();
                loading.fadeIn(); 
                
                $.ajax(
                       {
                           type:metode,
                           url: url_file,
                           data:data,
                           success: function(data) {
                            loading.fadeOut();
                            tampilkan.html(data);
                            tampilkan.fadeIn(2000);
                           }
                       }
                       ); 
                }
            ";
            echo "</script>";
            echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." value=\"".$value."\" ";
            echo "onclick='";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "var ".$isi['id']."=$(\"#".$isi['id']."\").val();";}echo "tampilkandata(\"GET\",\"".site_url($target_ajax)."\",\"pilihan=".$submenu."&aksi=".$aksi."&proses=proses";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "&".$isi['id']."=".$isi['id'];}echo "\"";echo ",\"#penampil\",\"#pra\")'";
            echo " />";
            echo "
                <center>
                <div id='pra' style='width:50%;' align='center' style=\"width:0;\">
                <div class=\"progress\">
                    <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                    mohon tunggu...
                    </div>
                </div>
                </center>
                <div id=penampil align=\"center\" style='width:100%; height:135px'></div>
            ";
            break;
            case ("button_ajax_pdf"):
                echo "<script>";
                echo "
                function tampilkandata (metode,url_file,data,target,pra) {
                    var loading = $(pra);
                    var tampilkan = $(target);
                    tampilkan.hide();
                    loading.fadeIn(); 
                    
                    $.ajax(
                           {
                               type:metode,
                               url: url_file,
                               data:data,
                               success: function(data) {
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                               }
                           }
                           ); 
                    }
                ";
                echo "</script>";
                //disini variabel $value_selected_combo atau $tombol[$i][8], jadi target bisa berbeda dengan target form nya.
                //variabel $label untuk menulis header modal atau $tombol[$i][7].
                //variabel $event untuk memberi nama modal atau $tombol[$i][6]
                echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut."  value=\"".$value."\" ";
                echo "onclick='$(\"#".$event."\").modal(\"show\"); ";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "var ".$isi['id']."=$(\"#".$isi['id']."\").val();";}echo "tampilkandata(\"GET\",\"".site_url($value_selected_combo)."\",\"pilihan=".$submenu."&aksi=".$aksi."&proses=proses";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "&".$isi['id']."=".$isi['id'];}echo "\"";echo ",\"#penampil_".$event."\",\"#pra_".$event."\")'";
                echo " />";
                //$this->CI->session->userdata('teks_modal')!==NULL?$teks_modal=$this->CI->session->userdata('teks_modal'):$teks_modal='';
                echo "
                    <!-- Modal Submit -->
                    <div class=\"modal fade\" id=\"".$event."\" role=\"dialog\" style=\"z-index:100000000;\">
                        <div class=\"modal-dialog modal-lg\" id=\"dialog_pdf\" >
                        
                        <!-- Modal content-->
                        <div class=\"modal-content\" id=\"tes\">
                            <div class=\"modal-header\">
                            <h5 class=\"modal-title\"><div align=center>$label</div></h5>
                            <button type=\"button\" class=\"close\" onclick='$(\"#".$event."\").modal(\"hide\");'>&times;</button>
                            </div>
                            <div class=\"modal-body\">
                            <center>
                            <div id=\"pra_".$event."\" style='width:65%;' align='center' >
                            <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                mohon tunggu...
                                </div>
                            </div>
                            </div>
                            </center>
                            <div id=\"penampil_".$event."\" align=\"center\" style='width:100%;height:500px;'></div>
                            </div>
                            <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-primary\" onclick='$(\"#".$event."\").modal(\"hide\");' >Close</button>";
                            echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut."  value=\"Perbesar\" ";
                            echo "onclick='$(\"#sub_".$event."\").modal(\"show\"); ";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "var ".$isi['id']."=$(\"#".$isi['id']."\").val();";}echo "tampilkandata(\"GET\",\"".site_url($value_selected_combo)."\",\"pilihan=".$submenu."&aksi=".$aksi."&proses=proses";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "&".$isi['id']."=".$isi['id'];}echo "\"";echo ",\"#penampil_sub_".$event."\",\"#pra_sub_".$event."\")'";
                            echo " />";
                            echo "
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    ";

                    echo "
                    <!-- Modal Submit -->
                    <div class=\"modal fade\" id=\"sub_".$event."\" role=\"dialog\" style=\"z-index:100000000;\">
                        <div class=\"modal-dialog modal-lg\" id=\"dialog_pdf\" style=\"max-width:100%;\" >
                        
                        <!-- Modal content-->
                        <div class=\"modal-content\" id=\"tes\">
                            <div class=\"modal-header\">
                            <h5 class=\"modal-title\"><div align=center>$label</div></h5>
                            <button type=\"button\" class=\"close\" onclick='$(\"#sub_".$event."\").modal(\"hide\");'>&times;</button>
                            </div>
                            <div class=\"modal-body\">
                            <center>
                            <div id=\"pra_sub_".$event."\" style='width:65%;' align='center' >
                            <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                mohon tunggu...
                                </div>
                            </div>
                            </div>
                            </center>
                            <div id=\"penampil_sub_".$event."\" align=\"center\" style='width:100%;height:500px;'></div>
                            </div>
                            <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-primary\" onclick='$(\"#sub_".$event."\").modal(\"hide\");' >Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    ";
                    //$this->CI->session->set_userdata('teks_modal',NULL);
            break;
            case ("submit_multi"):
                echo "<input type='button' class='$class' name='$nama_komponen' id='$id'  onclick='javascript: return SubmitForm()' data-toggle='modal' data-target='#myModal_submit' value='".$value."'>";
                //$this->CI->session->userdata('teks_modal')!==NULL?$teks_modal=$this->CI->session->userdata('teks_modal'):$teks_modal='';
                echo "
                    <!-- Modal Submit -->
                    <div class=\"modal fade\" id=\"myModal_submit\" role=\"dialog\" style=\"z-index:10000000;\">
                        <div class=\"modal-dialog modal-sm\">
                        
                        <!-- Modal content-->
                        <div class=\"modal-content\">
                            <div class=\"modal-body\">
                            <center>
                            <div id='pra_submit' style='width:65%;' align='center' >
                            <div>$label</div>
                            <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                mohon tunggu...
                                </div>
                            </div>
                            </div>
                            </center>
                            <div id=penampil1_submit align=\"center\" style='width:100%;'></div>
                            </div>
                            <div class=\"modal-footer\">
                            <!--<button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\" >Close</button>-->
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    ";
                
                    echo "
                        <script language=javascript>
                        function SubmitForm()
                        {";
                                echo "document.forms['form_unggah_berkas'].target='frame_result';";
                                echo "document.forms['form_unggah_berkas'].submit();";

                                foreach($event as $key=>$unit){
                                    echo "document.forms['form_unggah_berkas'].action='$unit';";
                                    echo "document.forms['form_unggah_berkas'].target='frame_result$key';";
                                    echo "document.forms['form_unggah_berkas'].submit();";
                                }
                    echo "
                        }
                        </script>
            
                    ";
            
                    echo "
                    <div style='display:none;'>";
                        echo "<div class='iframe_container'>";
                        echo "<iframe name='frame_result' width='350px' height='100px' frameborder='0'></iframe>";
                        echo "</div>";

                        foreach($event as $key=>$unit){
                            echo "<div class='iframe_container'>";
                            echo "<iframe name='frame_result$key' width='350px' height='100px' frameborder='0'></iframe>";
                            echo "</div>";
                        }
                    echo "
                    </div>
                    ";
                    //$this->CI->session->set_userdata('teks_modal',NULL);
            break;
            case ("submit_multi_2"):
                echo "<input type='button' class='$class' name='$nama_komponen' id='$id'  onclick='javascript: return SubmitForm_2()' data-toggle='modal' data-target='#myModal_submit_2' value='".$value."'>";
                //$this->CI->session->userdata('teks_modal')!==NULL?$teks_modal=$this->CI->session->userdata('teks_modal'):$teks_modal='';
                echo "
                    <!-- Modal Submit_2 -->
                    <div class=\"modal fade\" id=\"myModal_submit_2\" role=\"dialog\" style=\"z-index:10000000;\">
                        <div class=\"modal-dialog modal-sm\">
                        
                        <!-- Modal content-->
                        <div class=\"modal-content\">
                            <div class=\"modal-body\">
                            <center>
                            <div id='pra_submit_2' style='width:65%;' align='center' >
                            <div>$label</div>
                            <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                mohon tunggu...
                                </div>
                            </div>
                            </div>
                            </center>
                            <div id=penampil1_submit_2 align=\"center\" style='width:100%;'></div>
                            </div>
                            <div class=\"modal-footer\">
                            <!--<button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\" >Close</button>-->
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    ";
                
                    echo "
                        <script language=javascript>
                        function SubmitForm_2()
                        {";
                                echo "document.forms['form_unggah_berkas_balasan'].target='frame_result_2';";
                                echo "document.forms['form_unggah_berkas_balasan'].submit();";

                                foreach($event as $key=>$unit){
                                    echo "document.forms['form_unggah_berkas_balasan'].action='$unit';";
                                    echo "document.forms['form_unggah_berkas_balasan'].target='frame_result_2$key';";
                                    echo "document.forms['form_unggah_berkas_balasan'].submit();";
                                }
                    echo "
                        }
                        </script>
            
                    ";
            
                    echo "
                    <div style='display:none;'>";
                        echo "<div class='iframe_container'>";
                        echo "<iframe name='frame_result_2' width='350px' height='100px' frameborder='0'></iframe>";
                        echo "</div>";

                        foreach($event as $key=>$unit){
                            echo "<div class='iframe_container'>";
                            echo "<iframe name='frame_result_2$key' width='350px' height='100px' frameborder='0'></iframe>";
                            echo "</div>";
                        }
                    echo "
                    </div>
                    ";
                    //$this->CI->session->set_userdata('teks_modal',NULL);
            break;
            case ("button_ajax_unggahberkas"):
                echo "<script>";
                echo "
                function tampilkandata (metode,url_file,data,target,pra) {
                    var loading = $(pra);
                    var tampilkan = $(target);
                    tampilkan.hide();
                    loading.fadeIn(); 
                    
                    $.ajax(
                           {
                               type:metode,
                               url: url_file,
                               data:data,
                               success: function(data) {
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                               }
                           }
                           ); 
                    }
                ";
                echo "</script>";
                echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut."  value=\"".$value."\" ";
                echo "onclick='$(\"#".$event."\").modal(\"show\"); ";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "var ".$isi['id']."=$(\"#".$isi['id']."\").val();";}echo "tampilkandata(\"GET\",\"".site_url($value_selected_combo)."\",\"pilihan=".$submenu."&aksi=".$aksi."&proses=proses";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "&".$isi['id']."=".$isi['id'];}echo "\"";echo ",\"#penampil_".$event."\",\"#pra_".$event."\")'";
                echo " />";
                echo "
                    <!-- Modal Submit -->
                    <div class=\"modal fade\" id=\"".$event."\" role=\"dialog\" style=\"z-index:100000000;\">
                        <div class=\"modal-dialog modal-lg\" >
                        
                        <!-- Modal content-->
                        <div class=\"modal-content\" style=\"background:#F9E79F;\">
                            <div class=\"modal-header\">
                            <h5 class=\"modal-title\"><div align=center>$label</div></h5>
                            <button type=\"button\" class=\"close\" onclick='$(\"#".$event."\").modal(\"hide\");'>&times;</button>
                            </div>
                            <div class=\"modal-body\">
                            <center>
                            <div id=\"pra_".$event."\" style='width:65%;' align='center' >
                            <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                mohon tunggu...
                                </div>
                            </div>
                            </div>
                            </center>
                            <div id=\"penampil_".$event."\" align=\"center\" ></div>
                            </div>
                            <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-primary\" onclick='$(\"#".$event."\").modal(\"hide\");' >Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    ";
                    //$this->CI->session->set_userdata('teks_modal',NULL);
            break;
            case ("button_ajax_get_CI"):
                //Button ini bisa mengirim dalam metode GET dan sekaligus dengan URI CI.
                /*
                echo "
                <link href=".base_url('/login/css/css/bootstrap.css')." rel=\"stylesheet\" type=\"text/css\">
                <script src=".base_url('/login/vendor/jquery/jquery-3.2.1.min.js')."></script>
                <script src=".base_url('/login/vendor/bootstrap/js/bootstrap.min.js')."></script>
                ";
                */
                $this->bootstrap_js();
                echo "<script>";
                echo "
                function tampilkandata (metode,url_file,data,target,pra) {
                    var loading = $(pra);
                    var tampilkan = $(target);
                    tampilkan.hide();
                    loading.fadeIn(); 
                    
                    $.ajax(
                           {
                               type:metode,
                               url: url_file,
                               data:data,
                               success: function(data) {
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                               }
                           }
                           ); 
                    }
                ";
                echo "</script>";
                echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." data-toggle=\"modal\" data-target=\"#myModal\" value=\"".$value."\" ";
                echo "onclick='";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "var ".$isi['id']."=$(\"#".$isi['id']."\").val();";}echo "tampilkandata(\"GET\",\"".site_url($target_ajax)."\",\"pilihan=".$submenu."&aksi=".$aksi."&proses=proses";foreach ($perekam_id_untuk_button_ajax as $isi) {echo "&".$isi['id']."=".$isi['id'];}echo "\"";echo ",\"#penampil\",\"#pra\")'";
                echo " />";
                echo "
                    <!-- Modal -->
                    <div class=\"modal fade\" id=\"myModal\" role=\"dialog\">
                        <div class=\"modal-dialog modal-lg\">
                        
                        <!-- Modal content-->
                        <div class=\"modal-content\">
                            <div class=\"modal-header\">
                            <h4 class=\"modal-title\">Modal Header</h4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                            </div>
                            <div class=\"modal-body\">
                            <center>
                            <div id='pra' style='width:65%;' align='center' >
                                <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                mohon tunggu...
                                </div>
                            </div>
                            </center>
                            <div id=penampil align=\"center\" style='width:100%; height:135px'></div>
                            </div>
                            <div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                ";
                break;
                case ("button_ajax_post_CI"):
                    //Button ini bisa mengirim dengan metode POST sekaligus dengan URI CI.
                    //Button ini menerima $data_ajax secara umum dalam bentuk json dan diberi nama secara default sebagai data_json
                    //file target hanya perlu menggunakan nama itu, data_json untuk mengaksesnya dan melakukan unserialize dan decode_json.
                    //Jika $data_ajax=NULL maka Button mengirim data menggunakan $perekam_id_untuk_button_ajax yang mengambil semua data dari 
                    //nilai-nilai input pada form. semua nama parameter disimpan di nama_parameter, sehingga cukup menggunakan nama_parameter untuk membuka semua nilai yang lain
                    //untuk mengirim data menggunakan URI CI, data dapat ditumpangkan pada variabel URI $target_ajax.
                    //CONTOH PENGGUNAAN:
                    /*
                    $this->header_lengkap_bootstrap_controller();
                    $judul="Tambahkan Kandidat";
                    $tabel="admin";
                    $coba=array();
                    $id='idadmin';
                    $aksi='tambah';
                    if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
                    //deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
                    $coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
                    $coba[1][4]='';
                    $coba[2][4]='';                    
                    $coba[2][4]='';
                    $coba[3][4]='';
                    $coba[3][4]='';
                    $komponen=$coba;
                    $atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
                    //deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
                    $tombol[2]=array('submit','submit','btn btn-primary','submit','','','','Tombol Submit');
                    //$tombol[0]=array('button_ajax2','button_ajax2','btn btn-info','button_ajax2','','','','Tombol Ajax2');
                    $tombol[0]=array('button_ajax_post_CI','button_ajax_post_CI','btn btn-info','button_ajax_post_CI','','','','Tombol Ajax4');
                    //$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Tombol Ajax4');

                    $tombol1[0]=array('button_ajax','button_ajax','btn btn-info','button_ajax','','','','Tombol Ajax');
                    $value_selected_combo='';
                    $target_action='target_action';
                    $submenu='ini_pesan_submenu';
                    $aksi='ini_pesan_tambah';
                    $perekam_id_untuk_button_ajax='';
                    $class='form-control';
                    jika $data-ajax=NULL (data diambil bukan di dalam konteks form, tetapi penggunaan sembarang)
                    $this->form_general_2_view_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='Frontoffice/tes1/123/234',$data_ajax=NULL);
                    jikaa $data-ajax= tidak NULL (data diambil dari nilai2 form)
                    $this->form_general_2_view_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='Frontoffice/tes1/123/234',$data_ajax={OKBRO:"YUP SIAP"});
                    
                    ADAPUN DI FILE YANG BERBEDA, DI FILE $target_ajax (untuk $data_ajax=NULL, tetapi data di ambil dalam konteks adanya form):
                    echo "OK BRO MASUK";
                    echo "INI DATA name: ".$this->enkripsi->dekapsulasiData($_POST['data_json']);
                    echo "<br>INI DATA username: ".$_POST['username'];
                    echo "<br>INI DATA ok: ".$ok;
                    echo "<br>INI DATA ok1: ".$ok1;
                    */

                    /*
                    echo "
                    <!--<link href=".base_url('/login/css/css/bootstrap.css')." rel=\"stylesheet\" type=\"text/css\">-->
                    <!--<script src=".base_url('/login/vendor/jquery/jquery-3.2.1.min.js')."></script>-->
                    <!--<script src=".base_url('/login/vendor/bootstrap/js/bootstrap.min.js')."></script>-->
                    ";
                    */
                    $this->bootstrap_js();
                    //$this->header_lengkap_bootstrap();
                    echo "<script>";      
                    echo "
                    $(document).ready(function(){
                        $(\"#".$id."\").click(function(){
                            var loading = $(\"#pra_post\");
                            var tampilkan = $(\"#penampil_post\");
                            tampilkan.hide();
                            loading.fadeIn(); 
                            $.post(\"".site_url($target_ajax)."\",";
                            if($data_ajax) {echo "{ data_json:\"".$this->CI->enkripsi->enkapsulasiData($data_ajax)."\"},";}
                            else {
                                echo "{";
                                    $string='';
                                    $nama='';
                                    foreach ($perekam_id_untuk_button_ajax as $isi) {
                                        $string.=$isi['id'].": $('#".$isi['id']."').val(),";
                                        $nama.= $isi['id']." ";             
                                    }
                                    $nama=rtrim($nama,";");
                                    $string=rtrim($string,",");
                                    echo "data_json:\"".$this->CI->enkripsi->enkapsulasiData($nama)."\",";
                                    echo $string;
                                echo "},";    
                            }
                            echo "
                            function(data,status){
                                loading.fadeOut();
                                tampilkan.html(data);
                                tampilkan.fadeIn(2000);
                            });
                        });
                      });
                    ";
                    echo "</script>";
                    echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." data-toggle=\"modal\" data-target=\"#myModal_post\" value=\"".$value."\" />";
                    echo "
                        <!-- Modal -->
                        <div class=\"modal fade\" id=\"myModal_post\" role=\"dialog\">
                            <div class=\"modal-dialog modal-lg\">
                            
                            <!-- Modal content-->
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                <h4 class=\"modal-title\">BKD Provinsi Sulawesi Selatan</h4>
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                                </div>
                                <div class=\"modal-body\">
                                <center>
                                <div id='pra_post' style='width:65%;' align='center' >
                                <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                    <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                    mohon tunggu...
                                    </div>
                                </div>
                                </center>
                                <div id=penampil_post align=\"center\" style='width:100%;'></div>
                                </div>
                                <div class=\"modal-footer\">
                                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                </div>
                            </div>
                            
                            </div>
                        </div>
                    ";
                    break;	
                    case ("button_ajax_post2_CI"):
                        //Button ini bisa mengirim dengan metode POST sekaligus dengan URI CI.
                        //Button ini menerima $data_ajax secara umum dalam bentuk json dan diberi nama secara default sebagai data_json
                        //file target hanya perlu menggunakan nama itu, data_json untuk mengaksesnya dan melakukan unserialize dan decode_json.
                        //Jika $data_ajax=NULL maka Button mengirim data menggunakan $perekam_id_untuk_button_ajax yang mengambil semua data dari 
                        //nilai-nilai input pada form. semua nama parameter disimpan di nama_parameter, sehingga cukup menggunakan nama_parameter untuk membuka semua nilai yang lain
                        //untuk mengirim data menggunakan URI CI, data dapat ditumpangkan pada variabel URI $target_ajax.
                        //CONTOH PENGGUNAAN:
                        /*
                        $this->header_lengkap_bootstrap_controller();
                        $judul="Tambahkan Kandidat";
                        $tabel="admin";
                        $coba=array();
                        $id='idadmin';
                        $aksi='tambah';
                        if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
                        //deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
                        $coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
                        $coba[1][4]='';
                        $coba[2][4]='';                    
                        $coba[2][4]='';
                        $coba[3][4]='';
                        $coba[3][4]='';
                        $komponen=$coba;
                        $atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
                        //deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
                        $tombol[2]=array('submit','submit','btn btn-primary','submit','','','','Tombol Submit');
                        //$tombol[0]=array('button_ajax2','button_ajax2','btn btn-info','button_ajax2','','','','Tombol Ajax2');
                        $tombol[0]=array('button_ajax_post_CI','button_ajax_post_CI','btn btn-info','button_ajax_post_CI','','','','Tombol Ajax4');
                        //$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Tombol Ajax4');
    
                        $tombol1[0]=array('button_ajax','button_ajax','btn btn-info','button_ajax','','','','Tombol Ajax');
                        $value_selected_combo='';
                        $target_action='target_action';
                        $submenu='ini_pesan_submenu';
                        $aksi='ini_pesan_tambah';
                        $perekam_id_untuk_button_ajax='';
                        $class='form-control';
                        jika $data-ajax=NULL (data diambil bukan di dalam konteks form, tetapi penggunaan sembarang)
                        $this->form_general_2_view_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='Frontoffice/tes1/123/234',$data_ajax=NULL);
                        jikaa $data-ajax= tidak NULL (data diambil dari nilai2 form)
                        $this->form_general_2_view_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='Frontoffice/tes1/123/234',$data_ajax={OKBRO:"YUP SIAP"});
                        
                        ADAPUN DI FILE YANG BERBEDA, DI FILE $target_ajax (untuk $data_ajax=NULL, tetapi data di ambil dalam konteks adanya form):
                        echo "OK BRO MASUK";
                        echo "INI DATA name: ".$this->enkripsi->dekapsulasiData($_POST['data_json']);
                        echo "<br>INI DATA username: ".$_POST['username'];
                        echo "<br>INI DATA ok: ".$ok;
                        echo "<br>INI DATA ok1: ".$ok1;
                        */
    
                        /*
                        echo "
                        <!--<link href=".base_url('/login/css/css/bootstrap.css')." rel=\"stylesheet\" type=\"text/css\">-->
                        <!--<script src=".base_url('/login/vendor/jquery/jquery-3.2.1.min.js')."></script>-->
                        <!--<script src=".base_url('/login/vendor/bootstrap/js/bootstrap.min.js')."></script>-->
                        ";
                        */
                        //$this->bootstrap_js();
                        //$this->header_lengkap_bootstrap();
                        echo "<script>";      
                        echo "
                        $(document).ready(function(){
                            $(\"#".$id."\").click(function(){
                                var loading = $(\"#pra_post\");
                                var tampilkan = $(\"#penampil_post\");
                                tampilkan.hide();
                                loading.fadeIn(); 
                                $.post(\"".site_url($target_ajax)."\",";
                                if($data_ajax) {echo "{ data_json:\"".$this->CI->enkripsi->enkapsulasiData($data_ajax)."\"},";}
                                else {
                                    echo "{";
                                        $string='';
                                        $nama='';
                                        foreach ($perekam_id_untuk_button_ajax as $isi) {
                                            $string.=$isi['id'].": $('#".$isi['id']."').val(),";
                                            $nama.= $isi['id']." ";             
                                        }
                                        $nama=rtrim($nama,";");
                                        $string=rtrim($string,",");
                                        echo "data_json:\"".$this->CI->enkripsi->enkapsulasiData($nama)."\",";
                                        echo $string;
                                    echo "},";    
                                }
                                echo "
                                function(data,status){
                                    loading.fadeOut();
                                    tampilkan.html(data);
                                    tampilkan.fadeIn(2000);
                                });
                            });
                          });
                        ";
                        echo "</script>";
                        echo "<input type=\"button\" class=\"".$class."\" id=\"".$id."\" name=\"".$nama_komponen."\" ".$atribut." data-toggle=\"modal\" data-target=\"#myModal_post\" value=\"".$value."\" />";
                        echo "
                            <!-- Modal -->
                            <div class=\"modal fade\" id=\"myModal_post\" role=\"dialog\">
                                <div class=\"modal-dialog modal-lg\">
                                
                                <!-- Modal content-->
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                    <h4 class=\"modal-title\">BKD Provinsi Sulawesi Selatan</h4>
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                                    </div>
                                    <div class=\"modal-body\">
                                    <center>
                                    <div id='pra_post' style='width:65%;' align='center' >
                                    <div class=\"progress\" style=\"margin-top:50px; height:20px\">
                                        <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
                                        mohon tunggu...
                                        </div>
                                    </div>
                                    </center>
                                    <div id=penampil_post align=\"center\" style='width:100%;'></div>
                                    </div>
                                    <div class=\"modal-footer\">
                                    <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                    </div>
                                </div>
                                
                                </div>
                            </div>
                        ";
                        break;	
        } //switch
    } //end function

    //ALHAMDULILLAH SUDAH DITES, SUKSES.
    //CONTOH PENGGUNAAN FUNGSI form_general_2 dan form_general_2_vertikal (tetapi diekspresikan/dinyatakan ulang di dalam controller dengan menambahkan akhiran "_controller")
    //SEMUA PERNGGUNAAN PERNYATAAN ULANG FUNGSI ADA DI FILE Frontoffice.php di folder controller.
    //KEDUA FUNGSI MENGEMBALIKAN PESAN SUKSES ATAU GAGAL PADA SEBUAH IFRAME TERSEMBUNYI, SEHINGGA HALAMAN BERSIFAT SEPERTI SINGLE PAGE APPLICATION (SPA).
    /** 
		$this->header_lengkap_bootstrap_controller();
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
		$atribut_form='';
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[2]=array('submit','submit','btn btn-primary','submit','','','','Tombol Submit');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Tombol Reset');
		$tombol[0]=array('button_ajax','button_ajax','btn btn-info','button_ajax','','','','Tombol Ajax');
		$value_selected_combo='';
		$target_action='target_action';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		$this->form_general_2_vertikal_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		*/
    /**
     * @param komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7), deskripsi komponen. $komponen[$k][$i], $k=komponen ke-k, $i=rincian ke-i komponen ke-k (misal type, nama, label, event dan sebagainya)
     */
    //Fungsi Form Input Data: format $komponen=array($type,$nama_komponen,$class,$id,$atribut,$event,$label,$nilai_awal) $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
    //$value_selected_combo adalah variabel yg memberitahukan nilai yg terkirim dari id tertentu, diambil dari nilai komponen yg sudah terisi.
    function form_general_2($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        $perekam_id_untuk_button_ajax=array();
        if (!$atribut_form) {
        echo "<form target=\"upload_target\" id=\"file_upload_form\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" >";
        } else {echo "<form ".$atribut_form." >";}
        
        echo "<table ".$atribut_table['table']."><tr ".$atribut_table['tr']." ><th ".$atribut_table['th']." colspan=\"2\" ><b><center>".$judul."</center></b></th></tr>";
        foreach ($komponen as $key => $k) {
            if (!is_array($k)) {
                //Seluruh bagian ini adalah jika $k bukan array, artinya untuk setiap komponen ke-i, komponen[$i] tidak dirinci lagi menjadi komponen[$i][0]=type, komponen[$i][1]=name, komponen[$i][2]=class, komponen[$i][3]=id,... dan seterusnya.
                //Yaitu bahwa komponen[$i] bukan komponen[$i]=array(type,name,class,id,...)
                //Tetapi langsung bahwa komponen[$i]=tipe-komponen-ke-i=$k, bukan array rincian. secara umum untuk kasus ini sama dengan perilku fungsi form_general().
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']." style=\"width:20%;max-width:200px;\">".$key."</td><td ".$atribut_table['td'].">";
                //if ($k=="area" || $k=="combo_manual" || $k=="password" || $k=="text" || $k=="hidden" || $k=="checkbox" || $k=="radio" || $k=="file" || $k=="button" || $k=="submit" || $k=="reset") {
                    $this->buat_komponen_form($k,implode("_",array("name",implode("_",explode(" ",$key)))),$class,implode("_",array("id",implode("_",explode(" ",$key)))),$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                //}
                echo "</td></tr>";
                $perekam_id_untuk_button_ajax[$key]['id']=implode("_",array("id",implode("_",explode(" ",$key))));
            } else {
                //Seluruh bagian ini adalah jika $k adalah array, artinya bahwa sebuah komponen ke-i yaitu komponen[$i] dirinci oleh sebuah array, tidak lagi langsung hanya menyebut type seperti di atas. atau seperti fungsi general_form.
                //Jadi sebuah komponen ke-i yaitu komponen[$i]=array(type,name,class,id,...).
                if ($k==$komponen[0]) {
                    //Karena $k adalah array yaitu array(type,name,class,id,...), jika array $k adalah array komponen[0], yaitu komponen untuk id, yang dalam myframework dijadikan autoincrement pada tabel basisdata
                    //maka dia dijadikan readonly. 
                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']." style=\"width:20%;max-width:200px;\">".$k[6]."</td><td ".$atribut_table['td'].">";
                    $this->buat_komponen_form($komponen[$key][0],"no_name",$class,$komponen[$key][3],"disabled",$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    $this->buat_komponen_form("hidden",$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    echo "</td></tr>";
                } else {
                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td><td ".$atribut_table['td'].">";
                    $this->buat_komponen_form($komponen[$key][0],$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    echo "</td></tr>";
                }
                $perekam_id_untuk_button_ajax[$key]['id']=$komponen[$key][3];
            }
        }
        //Bagian membuat tombol di bagian bawah form, semua tombol ditampung oleh $tombol.
        echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']."></td><td ".$atribut_table['td']." align=\"right\">";
        foreach ($tombol as $key => $k) {
            //INGAT! $tombol adalah array tombol, untuk tombol ke-i adalah $tombol[$i]=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            //Jangan $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            $this->buat_komponen_form($tombol[$key][0],$tombol[$key][1],$tombol[$key][2],$tombol[$key][3],$tombol[$key][4],$tombol[$key][5],$tombol[$key][6],$tombol[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        }
        echo "</td></tr>";
        echo "</table></form>";
        echo "<iframe id=\"upload_target\" name=\"upload_target\" src=\"\" style=\"width:0px;height:0px;border:0px solid #000;\"></iframe>";
        
    
    }//form_iframe
    
    //ALHAMDULILLAH SUDAH DITES, SUKSES. COMTOH PENGGUNAAN ADA DI ATAS.
    /**
     * @param komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7), deskripsi komponen. $komponen[$k][$i], $k=komponen ke-k, $i=rincian ke-i komponen ke-k (misal type, nama, label, event dan sebagainya)
     */
    //Fungsi Form Input Data: format $komponen=array($type,$nama_komponen,$class,$id,$atribut,$event,$label,$nilai_awal) $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
    //$value_selected_combo adalah variabel yg memberitahukan nilai yg terkirim dari id tertentu, diambil dari nilai komponen yg sudah terisi.
    function form_general_2_vertikal($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        $perekam_id_untuk_button_ajax=array();
        if (!$atribut_form) {
        echo "<form target=\"upload_target\" id=\"file_upload_form\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" >";
        } else {echo "<form ".$atribut_form." >";}
        echo "<table ".$atribut_table['table']."><tr ".$atribut_table['tr']." ><th ".$atribut_table['th']."><b><center>".$judul."</center></b></th></tr>";
        foreach ($komponen as $key => $k) {
            if (!is_array($k)) {
                //Seluruh bagian ini adalah jika $k bukan array, artinya untuk setiap komponen ke-i, komponen[$i] tidak dirinci lagi menjadi komponen[$i][0]=type, komponen[$i][1]=name, komponen[$i][2]=class, komponen[$i][3]=id,... dan seterusnya.
                //Yaitu bahwa komponen[$i] bukan komponen[$i]=array(type,name,class,id,...)
                //Tetapi langsung bahwa komponen[$i]=tipe-komponen-ke-i=$k, bukan array rincian. secara umum untuk kasus ini sama dengan perilku fungsi form_general().
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$key."</td></tr>";
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                //if ($k=="area" || $k=="combo_manual" || $k=="password" || $k=="text" || $k=="hidden" || $k=="checkbox" || $k=="radio" || $k=="file" || $k=="button" || $k=="submit" || $k=="reset") {
                    $this->buat_komponen_form($k,implode("_",array("name",implode("_",explode(" ",$key)))),$class,implode("_",array("id",implode("_",explode(" ",$key)))),$atribut='',$event='',$label='',$array_option,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                //}
                echo "</td></tr>";
                $perekam_id_untuk_button_ajax[$key]['id']=implode("_",array("id",implode("_",explode(" ",$key))));
            } else {
                //Seluruh bagian ini adalah jika $k adalah array, artinya bahwa sebuah komponen ke-i yaitu komponen[$i] dirinci oleh sebuah array, tidak lagi langsung hanya menyebut type seperti di atas. atau seperti fungsi general_form.
                //Jadi sebuah komponen ke-i yaitu komponen[$i]=array(type,name,class,id,...).
                if ($k==$komponen[0]) {
                    //Karena $k adalah array yaitu array(type,name,class,id,...), jika array $k adalah array komponen[0], yaitu komponen untuk id, yang dalam myframework dijadikan autoincrement pada tabel basisdata
                    //maka dia dijadikan readonly. 
                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    $this->buat_komponen_form($komponen[$key][0],"no_name",$class,$komponen[$key][3],"disabled",$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    $this->buat_komponen_form("hidden",$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    echo "</td></tr>";
                } else {
                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    $this->buat_komponen_form($komponen[$key][0],$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    echo "</td></tr>";
                }
                $perekam_id_untuk_button_ajax[$key]['id']=$komponen[$key][3];
            }
        }
        //Bagian membuat tombol di bagian bawah form, semua tombol ditampung oleh $tombol.
        echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']." align=\"right\">";
        foreach ($tombol as $key => $k) {
            //INGAT! $tombol adalah array tombol, untuk tombol ke-i adalah $tombol[$i]=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            //Jangan $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            $this->buat_komponen_form($tombol[$key][0],$tombol[$key][1],$tombol[$key][2],$tombol[$key][3],$tombol[$key][4],$tombol[$key][5],$tombol[$key][6],$tombol[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        }
        echo "</td></tr>";
        echo "</table></form>";
        echo "<iframe id=\"upload_target\" name=\"upload_target\" src=\"\" style=\"width:0px;height:0px;border:0px solid #000;\"></iframe>";
        //print_r($perekam_id_untuk_button_ajax);
    }//form_iframe

    function form_general_2_vertikal_non_iframe($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        $perekam_id_untuk_button_ajax=array();
        //print_r($komponen);
        $nama_komponen=array();
        if (!$atribut_form) {
        echo "<form id=\"file_upload_form\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" >";
        } else {echo "<form ".$atribut_form." >";}
        echo "<table ".$atribut_table['table']."><tr ".$atribut_table['tr']." ><th ".$atribut_table['th']."><b><center>".$judul."</center></b></th></tr>";
        foreach ($komponen as $key => $k) {
            if (!is_array($k)) {
                //Seluruh bagian ini adalah jika $k bukan array, artinya untuk setiap komponen ke-i, komponen[$i] tidak dirinci lagi menjadi komponen[$i][0]=type, komponen[$i][1]=name, komponen[$i][2]=class, komponen[$i][3]=id,... dan seterusnya.
                //Yaitu bahwa komponen[$i] bukan komponen[$i]=array(type,name,class,id,...)
                //Tetapi langsung bahwa komponen[$i]=tipe-komponen-ke-i=$k, bukan array rincian. secara umum untuk kasus ini sama dengan perilku fungsi form_general().
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$key."</td></tr>";
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                //if ($k=="area" || $k=="combo_manual" || $k=="password" || $k=="text" || $k=="hidden" || $k=="checkbox" || $k=="radio" || $k=="file" || $k=="button" || $k=="submit" || $k=="reset") {
                    $this->buat_komponen_form($k,implode("_",array("name",implode("_",explode(" ",$key)))),$class,implode("_",array("id",implode("_",explode(" ",$key)))),$atribut='',$event='',$label='',$array_option,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                //}
                echo "</td></tr>";
                $perekam_id_untuk_button_ajax[$key]['id']=implode("_",array("id",implode("_",explode(" ",$key))));
                array_push($nama_komponen,implode("_",array("name",implode("_",explode(" ",$key)))));
            } else {
                //Seluruh bagian ini adalah jika $k adalah array, artinya bahwa sebuah komponen ke-i yaitu komponen[$i] dirinci oleh sebuah array, tidak lagi langsung hanya menyebut type seperti di atas. atau seperti fungsi general_form.
                //Jadi sebuah komponen ke-i yaitu komponen[$i]=array(type,name,class,id,...).
                if ($k==$komponen[0]) {
                    //Karena $k adalah array yaitu array(type,name,class,id,...), jika array $k adalah array komponen[0], yaitu komponen untuk id, yang dalam myframework dijadikan autoincrement pada tabel basisdata
                    //maka dia dijadikan readonly. 
                    //echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                    //echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    //$this->buat_komponen_form($komponen[$key][0],"no_name".$key,$class,$komponen[$key][3],"disabled",$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    $this->buat_komponen_form("hidden",$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    //echo "</td></tr>";
                } else {
                    if($komponen[$key][0]!=='hidden'){
                        echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                        echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    }
                    //if($komponen[$key][0]=='combo_database'){print_r($komponen[$key][5]);}
                    
                    $this->buat_komponen_form($komponen[$key][0],$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    //($type 0,$nama_komponen 1,$class='form-control',$id 3,$atribut 4,$event 5,$label 6,$value 7,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax='',$data_ajax=NULL)
                    if($komponen[$key][0]!=='hidden'){
                        echo "</td></tr>";
                    }
                }
                $perekam_id_untuk_button_ajax[$key]['id']=$komponen[$key][3];
                array_push($nama_komponen,$komponen[$key][1]);
            }
        }
        $data_nama=$this->CI->enkripsi->enkapsulasiData($nama_komponen);
        $this->buat_komponen_form("hidden",'data_nama',$class,'data_nama','','','',$data_nama,'',$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        //Bagian membuat tombol di bagian bawah form, semua tombol ditampung oleh $tombol.
        echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']." align=\"right\">";
        foreach ($tombol as $key => $k) {
            //INGAT! $tombol adalah array tombol, untuk tombol ke-i adalah $tombol[$i]=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            //Jangan $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            $this->buat_komponen_form($tombol[$key][0],$tombol[$key][1],$tombol[$key][2],$tombol[$key][3],$tombol[$key][4],$tombol[$key][5],$tombol[$key][6],$tombol[$key][7],$tombol[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        }
        echo "</td></tr>";
        echo "</table></form>";//print_r($perekam_id_untuk_button_ajax);
    }//form_non_iframe

    function form_general_2_vertikal_non_iframe_berganda($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        $perekam_id_untuk_button_ajax=array();
        //print_r($komponen);
        $nama_komponen=array();
        /*
        if (!$atribut_form) {
        echo "<form id=\"file_upload_form\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" >";
        } else {echo "<form ".$atribut_form." >";}
        */
        echo "<table ".$atribut_table['table']."><tr ".$atribut_table['tr']." ><th ".$atribut_table['th']."><b><center>".$judul."</center></b></th></tr>";
        foreach ($komponen as $key => $k) {
            if (!is_array($k)) {
                //Seluruh bagian ini adalah jika $k bukan array, artinya untuk setiap komponen ke-i, komponen[$i] tidak dirinci lagi menjadi komponen[$i][0]=type, komponen[$i][1]=name, komponen[$i][2]=class, komponen[$i][3]=id,... dan seterusnya.
                //Yaitu bahwa komponen[$i] bukan komponen[$i]=array(type,name,class,id,...)
                //Tetapi langsung bahwa komponen[$i]=tipe-komponen-ke-i=$k, bukan array rincian. secara umum untuk kasus ini sama dengan perilku fungsi form_general().
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$key."</td></tr>";
                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                //if ($k=="area" || $k=="combo_manual" || $k=="password" || $k=="text" || $k=="hidden" || $k=="checkbox" || $k=="radio" || $k=="file" || $k=="button" || $k=="submit" || $k=="reset") {
                    $this->buat_komponen_form($k,implode("_",array("name",implode("_",explode(" ",$key)))),$class,implode("_",array("id",implode("_",explode(" ",$key)))),$atribut='',$event='',$label='',$array_option,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                //}
                echo "</td></tr>";
                $perekam_id_untuk_button_ajax[$key]['id']=implode("_",array("id",implode("_",explode(" ",$key))));
                array_push($nama_komponen,implode("_",array("name",implode("_",explode(" ",$key)))));
            } else {
                //Seluruh bagian ini adalah jika $k adalah array, artinya bahwa sebuah komponen ke-i yaitu komponen[$i] dirinci oleh sebuah array, tidak lagi langsung hanya menyebut type seperti di atas. atau seperti fungsi general_form.
                //Jadi sebuah komponen ke-i yaitu komponen[$i]=array(type,name,class,id,...).
                if ($k==$komponen[0]) {
                    //Karena $k adalah array yaitu array(type,name,class,id,...), jika array $k adalah array komponen[0], yaitu komponen untuk id, yang dalam myframework dijadikan autoincrement pada tabel basisdata
                    //maka dia dijadikan readonly. 
                    //echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                    //echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    //$this->buat_komponen_form($komponen[$key][0],"no_name".$key,$class,$komponen[$key][3],"disabled",$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    $this->buat_komponen_form("hidden",$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    //echo "</td></tr>";
                } else {
                    if($komponen[$key][0]!=='hidden'){
                        echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">".$k[6]."</td></tr>";
                        echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td'].">";
                    }
                    //if($komponen[$key][0]=='combo_database'){print_r($komponen[$key][5]);}
                    
                    $this->buat_komponen_form($komponen[$key][0],$komponen[$key][1],$class,$komponen[$key][3],$komponen[$key][4],$komponen[$key][5],$komponen[$key][6],$komponen[$key][7],$komponen[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                    //($type 0,$nama_komponen 1,$class='form-control',$id 3,$atribut 4,$event 5,$label 6,$value 7,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax='',$data_ajax=NULL)
                    if($komponen[$key][0]!=='hidden'){
                        echo "</td></tr>";
                    }
                }
                $perekam_id_untuk_button_ajax[$key]['id']=$komponen[$key][3];
                array_push($nama_komponen,$komponen[$key][1]);
            }
        }
        $data_nama=$this->CI->enkripsi->enkapsulasiData($nama_komponen);
        $this->buat_komponen_form("hidden",'data_nama',$class,'data_nama','','','',$data_nama,'',$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        
        
        
        //Bagian membuat tombol di bagian bawah form, semua tombol ditampung oleh $tombol.
        echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']." align=\"right\">";
        foreach ($tombol as $key => $k) {
            //INGAT! $tombol adalah array tombol, untuk tombol ke-i adalah $tombol[$i]=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            //Jangan $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            $this->buat_komponen_form($tombol[$key][0],$tombol[$key][1],$tombol[$key][2],$tombol[$key][3],$tombol[$key][4],$tombol[$key][5],$tombol[$key][6],$tombol[$key][7],$tombol[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        }
        echo "</td></tr>";
        echo "</table><!--</form>-->";//print_r($perekam_id_untuk_button_ajax);
    }//form_non_iframe

    function form_general_2_vertikal_non_iframe_berganda_digunakan($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        $perekam_id_untuk_button_ajax=array();
        //print_r($komponen);
        $nama_komponen=array();
        if (!$atribut_form) {
        echo "<form id=\"file_upload_form\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" >";
        } else {echo "<form ".$atribut_form." >";}
        $this->form_general_2_vertikal_non_iframe_berganda($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL);
        
        $data_nama=$this->CI->enkripsi->enkapsulasiData($nama_komponen);
        $this->buat_komponen_form("hidden",'data_nama',$class,'data_nama','','','',$data_nama,'',$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        
        //Bagian membuat tombol di bagian bawah form, semua tombol ditampung oleh $tombol.
        echo "<table ".$atribut_table['table'].">";
        echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']." align=\"right\">";
        foreach ($tombol as $key => $k) {
            //INGAT! $tombol adalah array tombol, untuk tombol ke-i adalah $tombol[$i]=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            //Jangan $tombol=array("button",$nama_komponen,$class,$id,$atribut,$event,$pilihan,$data)
            $this->buat_komponen_form($tombol[$key][0],$tombol[$key][1],$tombol[$key][2],$tombol[$key][3],$tombol[$key][4],$tombol[$key][5],$tombol[$key][6],$tombol[$key][7],$tombol[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
        }
        echo "</td></tr>";
        echo "</table></form>";//print_r($perekam_id_untuk_button_ajax);
    }//form_non_iframe

    //BELUM DITES
    /**
     * Deskripsi Panel:
     * $panel[0]['judul']='Judul Panel ke-0';
     * $panel[0]['elemen']=array() elemen yang dimiliki panel ke-0;
     * contoh:
     * $panel[0]['elemen']['type-elemen-1']=$isi ke-1='text'
     * $panel[0]['elemen']['type-elemen-2']=$isi ke-2='text'
     * $panel[0]['elemen']['type-elemen-3']=$isi ke-3='checkbox'
     * 
     * $panel[1]['judul']='Judul Panel ke-1';
     * $panel[1]['elemen']=array() elemen yang dimiliki panel ke-1;
     * contoh:
     * $panel[1]['elemen']['type-elemen-1']=$isi ke-1='text'
     * $panel[1]['elemen']['type-elemen-2']=$isi ke-2='area'
     * $panel[1]['elemen']['type-elemen-3']=$isi ke-3='combo-manual'
     * -----
     * Bisa lebih dari 1 panel, tiap-tiap panel memiliki elemen.
     * elemen adalah komponen, yaitu diambil dari list komponen pada variabel $komponen.
     * $komponen mengambil komponen dari nama-nama kolom sebuah tabel. Setiap nama kolom dari sebuah tabel dibuatkan komponen input.
     * Ini artinya untuk n buah panel, membuat elemen-elemen dari sebuah tabel.
     * -----
     * Fungsi ini semacam fungsi form_general_2(), kalau di form_general_2() langsung membuat form dari $komponen.
     * Fungsi form_general_2_view_panel() tidak langsung membuat form dari komponen. form_general_2_view_panel() terlebih dulu memeriksa apakah kita membuat panel?
     * Jika kita membuat panel dengan mendefinisikan $panel seperti di atas, maka form_general_2_view_panel() membuat n buah panel dan mengisinya dengan komponen-komponen
     * yang diambil dari $komponen. Artiny $komponen dibagi-bagi ke dalamn buah panel.
     * Jika $panel tidak didefinisikan, maka dia langsung berperilaku seperti form_general_2().
     */
    //OBSELET: Struktur panel kurang bagus. Seharusnya Panel memiliki struktur sebagai berikut: $panel['komponen'][$i][$k], dimana komponen didefinisikan saat panel di buat.
    function form_general_2_view_panel_obselet($panel,$komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        $perekam_id_untuk_button_ajax=array();
        $i=0;
        if (!$atribut_form) {
            echo "<form target=\"upload_target\" id=\"file_upload_form\" method=\"POST\" enctype=\"multipart/form-data\" action=\"".site_url($target_action)."\" >";
            } else {
            }
        
        echo "<table ".$atribut_table['table']."><tr ".$atribut_table['tr']." ><th ".$atribut_table['th']."  colspan=\"2\" ><div style=\"\"><b><center>".$judul."</center></b></div></th></tr>";
        if($panel){
            foreach($panel as $kunci => $p){
                echo "<tr ".$atribut_table['tr']." ><th ".$atribut_table['th']." colspan=\"2\" ><div style=\"margin-left:10px;\" align=left><h4>".$p['judul']."</h4></div></th></tr>";
                foreach($p['elemen'] as $isi){
                    foreach ($komponen as $key => $k) { 
                        foreach($k as $kkey=>$kk){    
                            if($isi==$kkey){
                                if (!is_array($k)) {
                                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']." >".$kkey."</td><td ".$atribut_table['td'].">";
                                    //if ($kk=="area" || $kk=="combo_manual" || $kk=="password" || $kk=="text" || $kk=="hidden" || $kk=="checkbox" || $kk=="radio" || $kk=="file" || $kk=="button" || $kk=="submit" || $kk=="reset") {
                                        $this->buat_komponen_form($kk,implode("_",array("name",implode("_",explode(" ",$kkey)))),$class,implode("_",array("id",implode("_",explode(" ",$kkey)))),$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                                    //}
                                    echo "</td></tr>";
                                    $perekam_id_untuk_button_ajax[$key]['id']=implode("_",array("id",implode("_",explode(" ",$kkey))));
                                    
                                } else {//FOKUS DISINI
                                    if ($kk==$k[0]) {//KENAPA TERJEBAK DISINI?
                                        echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']." ><div align=right style=\"margin-right:20px;\">".$k[6]."</div></td><td ".$atribut_table['td']." style=\"padding:5px;\">";
                                        $this->buat_komponen_form($k[0],"no_name",$class,$k[3],"disabled",$k[5],$k[6],$k[7],$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                                        $this->buat_komponen_form("hidden",$k[1],$class,$k[3],$k[4],$k[5],$k[6],$k[7],$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                                        echo "</td></tr>";
                                    } else {
                                            echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']."><div align=right style=\"margin-right:20px;\">".$k[6]."</div></td><td ".$atribut_table['td']."> <div style=\"padding:5px;\">";
                                            $this->buat_komponen_form($k[0],$k[1],$class,$k[3],$k[4],$k[5],$k[6],$k[7],$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                                            echo "</td></tr>";
                                    }
                                    $perekam_id_untuk_button_ajax[$key]['id']=$k[3];
                                }
                            }
                        }
                    }
                    
                }
            }
        } else {
            foreach ($komponen as $key => $k) { 
                foreach($k as $kkey=>$kk){    
                    if($isi==$kkey){echo "MASUK BRO";
                        if (!is_array($k)) {
                            //jika $k bukan array maka $kk langsung menyatakan nilai type dari komponen, sama seperti sifat fungsi form_general().
                            //Ini berarti kita langsung dapat membuat komponen hanya dengan informasi type komponen, sama seperti form_general membuat komponen, hanya bedanya disini digunakan
                            //fungsi buat_komponen_form untuk membuat komponen.
                            echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']." >".$kkey."</td><td ".$atribut_table['td'].">";
                            //if ($kk=="area") {
                                $this->buat_komponen_form($kk,implode("_",array("name",implode("_",explode(" ",$kkey)))),$class,implode("_",array("id",implode("_",explode(" ",$kkey)))),$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                            //}
                            echo "</td></tr>";
                            $perekam_id_untuk_button_ajax[$key]['id']=implode("_",array("id",implode("_",explode(" ",$kkey))));
                        } else {
                            //Jika $k array, maka proses berlangsung seperti di fungsi form_general_2() dimana $k adalah array.
                            if ($kk==$k[0]) {
                                echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']." ><div align=right style=\"margin-right:20px;\">".$k[6]."</div></td><td ".$atribut_table['td']." style=\"padding:5px;\">";
                                $this->buat_komponen_form($k[0],"no_name",$class,$k[3],"disabled",$k[5],$k[6],$k[7],$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                                $this->buat_komponen_form("hidden",$k[1],$class,$k[3],$k[4],$k[5],$k[6],$k[7],$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                                echo "</td></tr>";
                                } else {
                                    echo "<tr ".$atribut_table['tr']." ><td ".$atribut_table['td']."><div align=right style=\"margin-right:20px; \">".$k[6]."</div></td><td ".$atribut_table['td']."> <div style=\"padding:5px;\">";
                                    $this->buat_komponen_form($k[0],$k[1],$class,$k[3],$k[4],$k[5],$k[6],$k[7],$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
                                    echo "</div></td></tr>";
                                }
                            }
                            $perekam_id_untuk_button_ajax[$key]['id']=$k[3];
                    }
                }
            }
        }
        
        //deskripsi $komponen[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
        echo "<tr ".$atribut_table['tr']."><td ".$atribut_table['td']."></td><td ".$atribut_table['td']." align=\"right\"><div style=\"margin-left:5px;padding:5px;color:white;\">";
        foreach ($tombol as $key => $k) {
            $this->buat_komponen_form($tombol[$key][0],$tombol[$key][1],$tombol[$key][2],$tombol[$key][3],$tombol[$key][4],$tombol[$key][5],$tombol[$key][6],$tombol[$key][7],$tombol[$key][8],$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
            //$type,$nama_komponen,$class='form-control',$id,$atribut,$event,$label,$value,$value_selected_combo
        }
        echo "</div></td></tr>";echo "</table></form>";
        
        echo "<iframe id=\"upload_target\" name=\"upload_target\" src=\"\" style=\"width:0px;height:0px;border:0px solid #000;\"></iframe>";
        
    }//form_ajax

    //=============================================================================================================================
    //Inshaa Allah di bawah ini adalah perbaikan fungsi form_general_2_view_panel_obselet() sebelumnya yang sudah obselet.
    //$judul disini mesti berupa array dari judul tiap-tiap panel, misal $judul=array('judul_panel1','judul_panel2','judul_panel3')
    //$panel[$i] = panel ke-i
    //$panel[$i]['komponen'] = komponen-komponen panel ke-i, dalam hal ini $panel[$i]['komponen'] berupa array, serupa array $komponen
    //Selanjutnya $komponen terurai menjadi $komponen[$k][$g], jadi secara umum adalah $panel[$i]['komponen'][$k][$g] = panel ke-i, dengan komponen adalah komponen ke-k dan property komponen ke-g
    /**
     *  CONTOH: PENGGUNAAN FUNGSI form_general_2_view_panel():
     *  $this->header_lengkap_bootstrap_controller();
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
		$tombol[0]=array('button_ajax','button_ajax','btn btn-info','button_ajax','','','','Tombol Ajax');

		$tombol1[0]=array('button_ajax','button_ajax','btn btn-info','button_ajax','','','','Tombol Ajax');
		$value_selected_combo='';
		$target_action='target_action';
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//$this->form_general_2_vertikal_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//print_r($komponen);
		
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
		

		$this->form_general_2_view_panel_controller($panel,$perekam_id_untuk_button_ajax,$class='form-control');
     */
    function form_general_2_view_panel($panel,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        //global $perekam1;
        if($panel){        
            foreach($panel as $kunci => $p){
                $this->form_general_2_vertikal($p['komponen'],$p['atribut_form'],$p['array_option'],$p['atribut_table'],$p['judul'],$p['tombol'],$p['value_selected_combo'],$p['target_action'],$p['submenu'],$p['aksi'],$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
            }
        } else {
            alert("Panel tidak didefinisikan");
        }
    }//form_ajax

    //KARENA FUNGSI INI ISINYA SAMA DENGAN form_general_2() yang sudah diperbaiki maka untuk kompatibilitas kode sebelumnya, fungsi ini
    //tetapi didefinisikan hanya saja sebagai alias dari fungsi form_general_2().
    function form_general_2_view($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        $this->form_general_2($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);  
    }

    //FUNGSI INI HANYA PERLUASAN ALIAS form_general_2_view() ke bentuk vertikal.
    function form_general_2_view_vertikal($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
        $this->form_general_2_vertikal($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);    
    }

    /**
     * @param kolom_direktori= nama kolom dari tabel yang memuat path direktori dari gambar.
     * @param direktori_avatar= path direktori avatar no-image, gambar umum pengganti jika foto belum ada. ditampilkan di salah satu kolom di row tabel penampil data.
     * @param Recordset1= Objek bertipe FETCH_ASSOC dari query basisdata.
     * @param row_Recordset1= array yang menampung Recordset1.
     * @param submenu= nilai switch di file yang dituju, dimana pada nilai itu ada potongan kode untuk memproses data.
     * @param array_atribut= array_atribut[0] untuk atribut div penampung tabel, array_atribut[1] untuk atribut tabel, array_atribut[2] untuk atribut baris tr.
     * @return menghasilkan tabel yang menampilkan data.
     */
    //CONTOH PENGGUNAAN:
    /**
     *  Tulis di controller, gunakan alias di controller yaitu penampil_tabel_controller().
	 *	$this->header_lengkap_bootstrap_controller();
	 *	$this->penampil_tabel_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan='select * from admin',$submenu='',$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg');
     */
    function penampil_tabel ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);
            $key_query=array_keys($Recordset[0]);

            
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {

                }
            }       
            
            //$this->header_lengkap_bootstrap();
            echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
            echo "<th colspan='2' scope='col' ><span >Pilih Aksi</span></th>";
            if(in_array($kolom_direktori,$tampung_key)){
                echo "<th ><span >Foto</span></th>";
            }
            foreach ($tampung_key as $value) {
                echo "<th > $value </th>";
            } 
            echo "</tr>";
            
            
            $i=0;
            foreach($Recordset as $isi){
                echo "<tr style=\"";
                
                if($i%2){
                    echo "background:#1F8A78;";
                } else {
                    echo "background:#EACE46;";
                }
                
                echo "\">";
                //$tampung=array_values($isi); 
                $i++;
                echo "<td valign=top>";
                $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='rincian_penampil_tabel',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                echo "</td>";
                echo "<td valign=top>";
                $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                echo "</td>";
                if(in_array($kolom_direktori,$tampung_key)) {
                    $direktori=$isi[$kolom_direktori];
                    if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                    echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                } else {
                    echo "<td valign=top><img src=\"".base_url($direktori_avatar)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                }
                foreach ($tampung_key as $k) {
                    echo "<td align=left valign=top>".$isi[$k]."</td>";
                } 
                echo "</tr>";

            }
            
            echo "</table></div>";
        }
    }

    //Maksudnya tidak dirancangan untuk menampilkan foto pegawai atau apa saja, jadi tabel biasa aja.
    function penampil_tabel_no_foto($table,$nama_kolom_id,$array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        //var_dump($Recordset1);
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            //var_dump($Recordset);
        
            unset($Recordset1);
            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                //echo "<h4>Kelola Tabel</h4>";
                //print_r($_SERVER);
                echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' colspan=1 ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    echo "<th > $value </th>";
                } 
                echo "</tr>";
                
                $i=0;
                foreach($Recordset as $isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;
                    echo "<td valign=top>";//xxxx
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut=' style="width:75px;" ',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='rincian_penampil_tabel_rincian',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\",\"nama_tabel\":\"".$table."\",\"nama_kolom_id\":\"".$nama_kolom_id."\" }");
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$class='btn btn-primary btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut=' style="width:75px;" ',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel_edit',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\",\"nama_tabel\":\"".$table."\",\"nama_kolom_id\":\"".$nama_kolom_id."\" }");
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI_hapus'.$isi[$tampung_key[0]],$class='btn btn-danger btn-xs',$id='button_ajax_post_CI_hapus'.$isi[$tampung_key[0]],$atribut=' style="width:75px;" ',$event='',$label='',$value='Hapus',$value_selected_combo='',$submenu='hapus_aja_tapi_ingat_peringatan_dulu',$aksi='hapus',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\",\"nama_tabel\":\"".$table."\",\"nama_kolom_id\":\"".$nama_kolom_id."\" }");
                    echo "</td>";
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        echo "<td align=left valign=top>".$isi[$k]."</td>";
                    } 
                    echo "</tr>";

                }
                
                echo "</table></div>";
            } else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }

    function penampil_tabel_akun_admin($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg',$target_action){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);
            $key_query=array_keys($Recordset[0]);

            
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {

                }
            }       
            
            //$this->header_lengkap_bootstrap();
            echo "<div ".$array_atribut[0]." >
            <table><tr>
            <td valign=top>";
            if(in_array($kolom_direktori,$tampung_key)) {
                $direktori=$isi[$kolom_direktori];
                if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                echo "<img src=\"".base_url($direktori)."\" width=\"200px\" height=\"auto\" onmouseover=\"this.style.width='250px';this.style.height='auto';\" onmouseout=\"this.style.width='200px';this.style.height='auto';\" />";
            } else {
                echo "<img src=\"".base_url($direktori_avatar)."\" width=\"200px\" height=\"auto\" onmouseover=\"this.style.width='250px';this.style.height='auto';\" onmouseout=\"this.style.width='200px';this.style.height='auto';\" />";
            }
            echo "<br><button type='button' id=\"ganti_foto\" data-toggle=\"modal\" data-target=\"#modal_ganti_foto\" style=\"cursor:pointer;color:white;width:200px;margin-top:5px;\" class=\"btn btn-sm btn-success shadow-sm\" ><i class=\"fas fa-image text-white-50\" style=\"color:white\"></i> Ganti Foto</button>
            ";
            echo "<br><button type='button' id=\"edit_profil\" style=\"cursor:pointer;color:white;width:200px;margin-top:5px;\" class=\"btn btn-sm btn-success shadow-sm\" ><i class=\"fas fa-user-edit text-white-50\" style=\"color:white\"></i> Edit Profil</button>
            <script>      
                $(document).ready(function(){
                  $(\"#edit_profil\").click(function(){
                    var loading = $(\"#pra_tabel\");
                    var tampilkan = $(\"#penampil_tabel\");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('".site_url('/Frontoffice/edit_profil')."/".$Recordset[0]['idprofil']."',{ data:\"okbro\"},
                    function(data,status){
                      loading.fadeOut();
                      tampilkan.html(data);
                      tampilkan.fadeIn(2000);
                    });
                  });
                  });
                </script>
            ";
            echo "<br><button type='button' id=\"refresh_ganti_foto\" style=\"cursor:pointer;color:white;width:200px;margin-top:5px;\" class=\"btn btn-sm btn-danger shadow-sm\" ><i class=\"fas fa-sync text-white-50\" style=\"color:white\"></i> Refresh lihat perubahan</button>
            <script>      
                $(document).ready(function(){
                  $(\"#refresh_ganti_foto\").click(function(){
                    var loading = $(\"#pra_tabel\");
                    var tampilkan = $(\"#penampil_tabel\");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('".site_url('/Frontoffice/tampilkan_profil2')."',{ data:\"okbro\"},
                    function(data,status){
                      loading.fadeOut();
                      tampilkan.html(data);
                      tampilkan.fadeIn(2000);
                    });
                  });
                  });
                </script>
            ";
            echo "<br><button type='button' id=\"balik_dashboard\" style=\"cursor:pointer;color:white;width:200px;margin-top:5px;\" class=\"btn btn-sm btn-primary shadow-sm\" ><i class=\"fas fa-backward text-white-50\" style=\"color:white\"></i> Kembali Dashboard</button>
            <script>      
                $(document).ready(function(){
                  $(\"#balik_dashboard\").click(function(){
                    var loading = $(\"#pra_tabel\");
                    var tampilkan = $(\"#penampil_tabel\");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('".site_url('/Frontoffice/tampilkan_tombol_baca_surat')."',{ data:\"okbro\"},
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
            </td>
            <td>
            <table ".$array_atribut[1]." >";
            foreach($Recordset as $isi){
                foreach ($tampung_key as $k) {
                    echo "<tr><td align=right>$k</td><td align=left valign=top>".$isi[$k]."</td></tr>";
                } 
            }
            
            echo "</table>
            
            </td></tr></table></div>";

            echo "
                <!-- Modal Ganti Foto Profil-->
                <div class='modal fade' id='modal_ganti_foto' role='dialog' style='z-index:100000;'>
                    <div class='modal-dialog'>
                    
                    <!-- Modal content-->
                    <div class='modal-content'>
                        <div class='modal-header'>
                        <h6 class='modal-title'>BKD Provinsi Sulawesi Selatan</h6>
                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                        </div>
                        <div class='modal-body' >
                        <center>
                        <div id='pra_foto_sedang' style='width:65%;' align='center' >
                        Unggah Foto Baru
                        <form target='target_ganti_foto' action=\"".$target_action."/".$Recordset[0]['idprofil']."\" method='post' enctype='multipart/form-data'>
                        <input type='file' name='file_ganti_foto' style=\"width:100%; margin-top:5px;\" class=\"btn btn-warning btn-sm\" id='file_ganti_foto'>
                        <button type=\"submit\" class=\"btn btn-success btn-sm\" style=\"width:100%; margin-top:5px;\" id=\"submit_ganti_foto\"><i class='fas fa-upload-cloud text-white-100'></i> Unggah</button>
                        </form> 
                        <iframe name='target_ganti_foto' id='target_ganti_foto' width='100%' height='0' frameborder=''></iframe>
                        </center>
                        <div id=penampil_foto_sedang align='center' style='width:100%;'></div>
                        </div>
                        <div class='modal-footer'>
                        <button type='button' class='btn btn-primary tutup_suara_foto' data-dismiss='modal'>Close</button>
                        </div>
                    </div>
                    
                    </div>
                </div>
            ";
        }
    }

    function penampil_tabel_akun_tamu_pegawai ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);
            $key_query=array_keys($Recordset[0]);

            
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {

                }
            }       
            
            //$this->header_lengkap_bootstrap();
            echo "<div ".$array_atribut[0]." >
            <table><tr>
            <td valign=top>";
            if(in_array($kolom_direktori,$tampung_key)) {
                $direktori=$isi[$kolom_direktori];
                if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                echo "<img src=\"".base_url($direktori)."\" width=\"200px\" height=\"auto\" onmouseover=\"this.style.width='250px';this.style.height='auto';\" onmouseout=\"this.style.width='200px';this.style.height='auto';\" />";
            } else {
                echo "<img src=\"".base_url($direktori_avatar)."\" width=\"200px\" height=\"auto\" onmouseover=\"this.style.width='250px';this.style.height='auto';\" onmouseout=\"this.style.width='200px';this.style.height='auto';\" />";
            }
            echo "
            </td>
            <td>
            <table ".$array_atribut[1]." >";
            foreach($Recordset as $isi){
                foreach ($tampung_key as $k) {
                    echo "<tr><td align=right>$k</td><td align=left valign=top>".$isi[$k]."</td></tr>";
                } 
            }
            
            echo "</table>
            
            </td></tr></table></div>";
        }
    }

    //Maksudnya tidak dirancangan untuk menampilkan foto pegawai atau apa saja, jadi tabel biasa aja.
    function penampil_tabel_no_foto_untuk_surat_masuk_frontoffice ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        //var_dump($Recordset1);
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            //var_dump($Recordset);
        
            unset($Recordset1);
            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                echo "<h4>Kelola Surat Masuk</h4>";
                //print_r($_SERVER);
                echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    echo "<th > $value </th>";
                } 
                echo "</tr>";
                
                
                $i=0;
                foreach($Recordset as $isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Verifikasi',$value_selected_combo='',$submenu='rincian_penampil_tabel',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        echo "<td align=left valign=top>".$isi[$k]."</td>";
                    } 
                    echo "</tr>";

                }
                
                echo "</table></div>";
            } else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }

    //Maksudnya tidak dirancangan untuk menampilkan foto pegawai atau apa saja, jadi tabel biasa aja.
    function penampil_tabel_no_foto_untuk_surat_keluar ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        //var_dump($Recordset1);
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            //var_dump($Recordset);
        
            unset($Recordset1);
            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                echo "<h4>Kelola Arsip Surat Keluar Semua Bidang dan Sekretariat</h4>";
                //print_r($_SERVER);
                echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    echo "<th > $value </th>";
                } 
                echo "</tr>";
                
                
                $i=0;
                foreach($Recordset as $isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='rincian_penampil_tabel_surat_keluar',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        echo "<td align=left valign=top>".$isi[$k]."</td>";
                    } 
                    echo "</tr>";

                }
                
                echo "</table></div>";
            } else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }

    //Maksudnya tidak dirancangan untuk menampilkan foto pegawai atau apa saja, jadi tabel biasa aja.
    function penampil_tabel_no_foto_untuk_surat_terusan ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);

            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                echo "<h4>Kelola Surat Terusan/Disposisi</h4>";
                echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    echo "<th > $value </th>";
                } 
                echo "</tr>";
                
                
                $i=0;
                foreach($Recordset as $isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-warning btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='rincian_penampil_tabel_terusan',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        echo "<td align=left valign=top>".$isi[$k]."</td>";
                    } 
                    echo "</tr>";

                }
                
                echo "</table></div>";
            }else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }

    //Maksudnya tidak dirancangan untuk menampilkan foto pegawai atau apa saja, jadi tabel biasa aja.
    function penampil_tabel_no_foto_untuk_surat_balasan ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);

            if(isset($Recordset[0])){
                $key_query=array_keys($Recordset[0]);
                
                $tampung_key=array();
                foreach($key_query as $index_array){
                    if(is_string($index_array)) {
                        array_push($tampung_key,$index_array);
                    }else {

                    }
                }       
                
                //$this->header_lengkap_bootstrap();
                echo "<h4>Kelola Surat Balasan Sekretariat</h4>";
                echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
                echo "<th scope='col' ><span >Aksi</span></th>";
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<th ><span >Foto</span></th>";
                }
                foreach ($tampung_key as $value) {
                    echo "<th > $value </th>";
                } 
                echo "</tr>";
                
                
                $i=0;
                foreach($Recordset as $isi){
                    echo "<tr>";
                    //$tampung=array_values($isi); 
                    $i++;
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-warning btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='rincian_penampil_tabel_balasan',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    /*
                    echo "<td valign=top>";
                    $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                    echo "</td>";
                    */
                    if(in_array($kolom_direktori,$tampung_key)) {
                        $direktori=$isi[$kolom_direktori];
                        if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                        echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                    } 
                    foreach ($tampung_key as $k) {
                        echo "<td align=left valign=top>".$isi[$k]."</td>";
                    } 
                    echo "</tr>";

                }
                
                echo "</table></div>";
            }else{
                echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
            }
        }
    }

    function penampil_tabel_no_foto_untuk_surat_balasan_akun ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);
            $key_query=array_keys($Recordset[0]);

            
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {

                }
            }       
            
            //$this->header_lengkap_bootstrap();
            echo "<h4>Kelola Surat Terusan/Disposisi</h4>";
            echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
            echo "<th scope='col' ><span >Aksi</span></th>";
            if(in_array($kolom_direktori,$tampung_key)){
                echo "<th ><span >Foto</span></th>";
            }
            foreach ($tampung_key as $value) {
                echo "<th > $value </th>";
            } 
            echo "</tr>";
            
            
            $i=0;
            foreach($Recordset as $isi){
                echo "<tr>";
                //$tampung=array_values($isi); 
                $i++;
                echo "<td valign=top>";
                $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-warning btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='rincian_penampil_tabel_terusan',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                echo "</td>";
                /*
                echo "<td valign=top>";
                $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                echo "</td>";
                */
                if(in_array($kolom_direktori,$tampung_key)) {
                    $direktori=$isi[$kolom_direktori];
                    if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                    echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                } 
                foreach ($tampung_key as $k) {
                    echo "<td align=left valign=top>".$isi[$k]."</td>";
                } 
                echo "</tr>";

            }
            
            echo "</table></div>";
        }
    }


    //Maksudnya query tidak terjadi di dalam fungsi, tetapi diluar fungsi, hasilnya masuk sebagai argumen $Recordset.
    function penampil_tabel_with_no_query ($array_atribut,$Recordset1,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
        //$Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);
            $key_query=array_keys($Recordset[0]);

            
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {

                }
            }       
            
            //$this->header_lengkap_bootstrap();
            echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
            echo "<th colspan='2' scope='col' ><span >Pilih Aksi</span></th>";
            if(in_array($kolom_direktori,$tampung_key)){
                echo "<th ><span >Foto</span></th>";
            }
            foreach ($tampung_key as $value) {
                echo "<th > $value </th>";
            } 
            echo "</tr>";
            
            
            $i=0;
            foreach($Recordset as $isi){
                echo "<tr style=\"";
                
                if($i%2){
                    echo "background:#1F8A78;";
                } else {
                    echo "background:#EACE46;";
                }
                
                echo "\">";
                //$tampung=array_values($isi); 
                $i++;
                echo "<td valign=top>";
                $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_rincian'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='rincian_penampil_tabel',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                echo "</td>";
                echo "<td valign=top>";
                $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi[$tampung_key[0]],$class='btn btn-success btn-xs',$id='button_ajax_post_CI_edit'.$isi[$tampung_key[0]],$atribut='',$event='',$label='',$value='Edit',$value_selected_combo='',$submenu='edit_penampil_tabel',$aksi='edit',$perekam_id_untuk_button_ajax=NULL,$target_ajax="Frontoffice/gerbang/".$submenu,$data_ajax="{\"".$tampung_key[0]."\":\"".$isi[$tampung_key[0]]."\" }");
                echo "</td>";
                if(in_array($kolom_direktori,$tampung_key)) {
                    $direktori=$isi[$kolom_direktori];
                    if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                    echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                } else {
                    echo "<td valign=top><img src=\"".base_url($direktori_avatar)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                }
                foreach ($tampung_key as $k) {
                    echo "<td align=left valign=top>".$isi[$k]."</td>";
                } 
                echo "</tr>";

            }
            
            echo "</table></div>";
        }
    }

    //FUNGSI INI HANYA ALIAS penampil_tabel() UNTUK MENJAGA KOMPATIBILITAS
    public function penampil_tabel_LAMA ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg'){
        $this->penampil_tabel ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg');
    }

    //CONTOH PENGGUNAAN:
    //$this->penampil_tabel_tanpa_CRUID_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan='select * from tbchat',$submenu='',$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg');
    //Gunakan aliasnya di controller yaitu penampil_tabel_tanpa_CRUID_controller().
    function penampil_tabel_tanpa_CRUID ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);
            $key_query=array_keys($Recordset[0]);

            
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {

                }
            }       
            echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
            //echo "<th colspan='3' scope='col' ><span >Pilih Aksi</span></th>";
            if(in_array($kolom_direktori,$tampung_key)){
                echo "<th ><span >Foto</span></th>";
            }
            foreach ($tampung_key as $value) {
                echo "<th > $value </th>";
            } 
            echo "</tr>";
            
            foreach($Recordset as $isi){
                if(in_array($kolom_direktori,$tampung_key)){
                $direktori=$isi[$kolom_direktori];
                    if ((!$direktori) or ($direktori=="../../public/img/pegawai/")){
                        $direktori=$direktori_avatar;
                    } else {
                        $direktori=implode("/",array("..",$direktori));//terjemahkan ini ke base_url() karena ini dalam konteks CI.
                    }
                }
                echo "<tr>";
                //$tampung=array_values($isi); 
                /*
                echo "<td width='30' valign=top><div style=\"cursor:pointer;\" align='center' style=\"background:brown;margin:10px; border-radius:3px 3px 3px 3px; color:white;\" onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&aksi=rincian&id=".$isi[$tampung_key[0]]."\",\"#penampil\",\"#pra\")' >Rincian</div></td>";
                echo "<td width='30' valign=top><div style=\"cursor:pointer;\" align='center' style=\"background:brown;margin:10px; border-radius:3px 3px 3px 3px; color:white;\" onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&aksi=edit&id=".$isi[$tampung_key[0]]."\",\"#penampil\",\"#pra\")'>Edit</div></td>";
                echo "<td width='30' valign=top><div style=\"cursor:pointer;\" align='center' style=\"background:brown;margin:10px; border-radius:3px 3px 3px 3px; color:white;\" onclick='if(confirm(\"Apakah anda benar ingin menghapus rekord ini?\")==true){tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&aksi=hapus&id=".$isi[$tampung_key[0]]."\",\"#penampil\",\"#pra\")}'>Hapus</div></td>";
                */
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td>";
                }
                foreach ($tampung_key as $k) {
                    echo "<td align=left valign=top>".$isi[$k]."</td>";
                } 
                echo "</tr>";
            }
        }
    }

    function penampil_tabel_tanpa_CRUID_vertikal ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='public/img/no-image.jpg'){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($query_yang_mau_ditampilkan,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            unset($Recordset1);
            $key_query=array_keys($Recordset[0]);

            
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {

                }
            }       

            echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." >";
            foreach($Recordset as $isi){
                if(in_array($kolom_direktori,$tampung_key)){
                $direktori=$isi[$kolom_direktori];
                    if ((!$direktori) or ($direktori=="../../public/img/pegawai/")){
                        $direktori=$direktori_avatar;
                    } else {
                        $direktori=implode("/",array("..",$direktori));//terjemahkan ini ke base_url() karena ini dalam konteks CI.
                    }
                }
                
                if(in_array($kolom_direktori,$tampung_key)){
                    echo "<tr>";
                    echo "<td ><span >Foto</span></td><td valign=top><img src=\"".base_url($direktori)."\" width=\"50px\" height=\"50px\" onmouseover=\"this.style.width='100px';this.style.height='100px';\" onmouseout=\"this.style.width='50px';this.style.height='50px';\" /> </td></tr>";
                }
                foreach ($tampung_key as $k) {
                    echo "<tr>";
                    echo "<td>$k</td><td align=left valign=top>".$isi[$k]."</td></tr>";
                } 
            }
        }
    }

    //FUNGSI INI KHUSUS UNTUK TABEL tbchat dengan struktur: idchat|nama_komentator|komentar|tgl_komentar|group_chat
    /**
     * CONTOH PENGGUNAAN:
     * $this->header_lengkap_bootstrap_controller();
	 * $count_tbchat=$this->model_frommyframework->jumlah_rekord('tbchat');
	 * $this->penampil_tabel_komentar_controller($array_atribut=array(""," class=\"table table-hover\"",""),$query_chat='SELECT * FROM `tbchat` order by idchat ASC',$count_tbchat,$jumlah_komen_ditampilkan=3,$submenu='');
     */
    function penampil_tabel_komentar ($array_atribut,$query_chat='SELECT * FROM `tbchat` order by idchat DESC',$count_tbchat,$jumlah_komen_ditampilkan,$submenu){
        $awal=$count_tbchat-$jumlah_komen_ditampilkan;
        $query_chat=$query_chat." limit ".$awal.",".$jumlah_komen_ditampilkan;
        $Recordset=$this->CI->model_frommyframework->user_defined_query_model($query_chat,$token='andisinra');
        if (!$Recordset) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." >";
            foreach($Recordset as $isi){
                    echo "<tr>";
                    echo "<td align=left valign=top width=100px>".$isi['nama_komentator']."<br />".$isi['tgl_komentar']."</td>";
                    echo "<td align=left valign=top>".$isi['komentar']."</td>";
                    echo "</tr>";
            }
            echo "</table></div>";
        }
    }

    //UNTUK KOMPATIBILITAS
    function penampil_tombol_add ($add,$toolbar,$src_wh) 
    {echo "<div ".$add."><div ".$toolbar."><img ".$src_wh." /></div></div>";}

    //UNTUK KOMPATIBILITAS
    function penampil_bar_searching ($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3) 
    {echo "<div ".$cari."><table ".$tabel_cari."><tr ".$tabel_ctr."><td ".$tabel_cd1."><form >Cari : <input ".$input1."><input ".$input2."><input ".$input3."></form></td></tr></table></div>";}

    //UNTUK KOMPATIBILITAS
    function penampil_bar_judul ($judul,$style) 
    {echo "<div ".$style."> ".$judul." </div>";}

    
    //Fungsi ini khusus untuk menampilkan list pegawai tetapi hanya beberapa kolom, ada tombol rincian untuk melihat lebih rinci, ada tombol edit untuk pegawai mengedit sendiri secara terbatas (kolom tertentu saja)
    //Jika tombol edit dan rincian diklik rencananya ada modal username dan password muncul. tidak ada tombol delete.
    //Form Input ajax:$array_atribut[0]=atribut div dari penampung tabel, $array_atribut[1]=atribut tabel, $array_atribut[2] atribut tr
    //CONTOH PENGGUNAAN DI CONTROLLER, BUAT FUNGSI TES:
    /*
    public function tes4(){
		$this->header_lengkap_bootstrap_controller();
		$this->penampil_tabel_tab_pegawai_controller ($array_atribut=array(""," class=\"table table-condensed\"",""),$Query_pegawai_terbatas='select * from identpeg limit 1,20',$submenu='',$tab='',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');
    }
    */
    function penampil_tabel_tab_pegawai ($array_atribut,$Query_pegawai_terbatas,$submenu,$tab,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg',$target_ajax){
        $Recordset1=$this->CI->model_frommyframework->user_defined_query_model($Query_pegawai_terbatas,$token='andisinra');
        if (!$Recordset1) {
            echo "<center>TABEL YANG BERSESUAIAN KOSONG, SILAHKAN DIISI DULU</center>";
        } else {
            //$this->header_lengkap_bootstrap();
            echo "<font style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11pt;\">";
            $Recordset=array();
            //ambil nama-nama key dari hasil query
            foreach ($Recordset1 as $isi) {
                array_push($Recordset,$isi);
            }
            
            //hapus objek PDOStatement hasil query basisdata, untuk mengemat memory.
            unset($Recordset1);

            //ambil semua key tetapi masih campur key berupa bilangan dan key berupa string.
            $key_query=array_keys($Recordset[0]);

            //ambil semua key yang berupa indeks string, bukan bilangan.
            $tampung_key=array();
            foreach($key_query as $index_array){
                if(is_string($index_array)) {
                    array_push($tampung_key,$index_array);
                }else {
                }
            }       

            echo "<div ".$array_atribut[0]." ><table ".$array_atribut[1]." ><tr ".$array_atribut[2]." >";
            foreach($Recordset as $isi){
                $k3=$isi[$tampung_key[0]];
                
                //$k0=explode("/",$k3);
                //$k0=implode("/",array($k0[1],$k0[2],$k0[3],$k0[4],$k0[5],$k0[6],$k0[7]));
                echo "<tr>";
                if(in_array($kolom_direktori,$tampung_key)) {
                    $direktori=$isi[$kolom_direktori];
                    if ((!$direktori) or ($direktori=="../../public/img/")){$direktori=$direktori_avatar;}
                    echo "<td valign=top width=51><hr color=\"#CCCCCC\" size=\"5\" /><img src=\"".base_url($direktori)."\" width=\"100px\" height=\"100px\" /> </td>";
                } else {
                    echo "<td valign=top width=51><hr color=\"#CCCCCC\" size=\"5\" /><img src=\"".base_url($direktori_avatar)."\" width=\"100px\" height=\"100px\" /> </td>";
                }
                echo "<td valign=top style=\"padding-left:10px;\"><hr color=\"#CCCCCC\" />NIP:".$isi['nipbaru']."<br>Satker:&nbsp;- &nbsp;&nbsp;&nbsp;<br />Email:&nbsp;".$isi['email']."
                <br />
                <!--<a style=\"text-decoration:none;\"><font style=\"cursor:pointer;\" onclick='tampilkandata(\"GET\",\"".site_url('/Frontoffice/gerbang')."\",\"pilihan=rincian_box&idpegawai=$k3\",\"#penampil\",\"#pra\");'>Rincian</font></a>-->
                <!--<a style=\"text-decoration:none;\"><font style=\"cursor:pointer;\" onclick='tampilkandata(\"GET\",\"".site_url('/Frontoffice/gerbang')."\",\"pilihan=edit_box&nipbaru=".$isi['nipbaru']."\",\"#penampil\",\"#pra\");'>Edit</font></a>-->";
                $this->buat_komponen_form($type='button_ajax_post_CI',$nama_komponen='button_ajax_post_CI'.$isi['nipbaru'],$class='btn btn-success btn-xs',$id='button_ajax_post_CI'.$isi['nipbaru'],$atribut='',$event='',$label='',$value='Rincian',$value_selected_combo='',$submenu='',$aksi='rincian',$perekam_id_untuk_button_ajax=NULL,$target_ajax,$data_ajax="{\"nipbaru\":\"".$isi['nipbaru']."\" }");
                echo "</td>";
                echo "<td valign=top style=\"padding-left:10px;\"><hr color=\"#CCCCCC\" size=\"5\" />Nama:<br>".$isi['gldepan']." ".$isi['nama']." ".$isi['glblk']."</td>";
                echo "</tr>";
            }
            echo "</table></div>";
            }//!$row_Recordset1
            echo "</font>";
            
    }


}
