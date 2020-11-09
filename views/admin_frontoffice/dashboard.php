<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->library('viewfrommyframework');
$this->load->library('enkripsi');
//$listfieldtes=$this->viewfrommyframework->penarik_semua_nama_kolom_sebuah_tabel_view('identpeg');
?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Ruang Kaban BKD Prov. Sulawesi Selatan</title>

  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url('/dashboard/vendor/fontawesome-free/css/all.min.css');?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo base_url('/dashboard/css/sb-admin-2.min.css')?>" rel="stylesheet">
  <!--<link href="<?php echo base_url('/public/Suyati_Technologies/editor.css'); ?>" type="text/css" rel="stylesheet"/>-->

  <!--<link href="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">-->
  <script src="<?php echo base_url('/public/vendor3.4.1/jquery/3.4.1/jquery.min.js'); ?>"></script>
  <script src="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/js/bootstrap.min.js'); ?>"></script>
  <!--<script src="<?php echo base_url('/public/Suyati_Technologies/editor.js'); ?>"></script>-->

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo site_url('Frontoffice/frontoffice_admin'); ?>">
        <div class="sidebar-brand-icon">
          <img src="<?php echo base_url('/assets/images/logo_sulsel.png');?>" alt="" width="50px">
        </div>
        <div class="sidebar-brand-text mx-3"><font size="1.5pt">Admin Ruang Kaban</font></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" onclick='$("#cetak_laporan").show();$("#cetak_laporan_periodik_agenda").hide();' href="<?php echo site_url('Frontoffice/frontoffice_admin'); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span id="dashboard_home">Dashboard Admin</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface
      </div>

      <!-- Tambahan 26/10/2020 -->
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAgenda_histori" aria-expanded="true" aria-controls="collapseAgenda_histori">
          <i class="fas fa-fw fa-book"></i>
          <span>Lacak History Surat</span>
        </a>
        <div id="collapseAgenda_histori" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded" onclick='$("#cetak_laporan").show();$("#cetak_laporan_periodik_agenda").hide();'>
            <h6 class="collapse-header">History surat frontoofice:</h6>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_histori_frontoffice" >History Surat Front Office</a>
            <!--<a class="collapse-item" style="cursor:pointer;" id="lihat_bankdata" >Lihat Bankdata</a>-->
          </div>
        </div>
      </li>

      <script>      
      $(document).ready(function(){
        $("#lihat_histori_frontoffice").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
          posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('bank_data');?>/index.php/Frontoffice/tampilkan_tabel_log_surat_masuk_new_verifikasi/',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('History >> Jejak Surat Front Office');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>

      <!-- Tambahan 30/09/2020 -->
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAgenda_pelacakan" aria-expanded="true" aria-controls="collapseAgenda_pelacakan">
          <i class="fas fa-fw fa-book"></i>
          <span>Ruang Surat Bidang</span>
        </a>
        <div id="collapseAgenda_pelacakan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded" onclick='$("#cetak_laporan").show();$("#cetak_laporan_periodik_agenda").hide();'>
            <h6 class="collapse-header">Ruang Virtual:</h6>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_frontoffice" >Front Office</a>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_sekretariat" >Sekretariat</a>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_bidangkk" >Bid. Kesj. & Kinerja</a>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_bidangpi" >Bid. Pengendalian</a>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_bidangpk" >Bid. Pengemb. Karier</a>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_bidangpp" >Bid. Perenc. & Informasi</a>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_bidangupt" >UPT PPK</a>
            <a class="collapse-item" style="cursor:pointer;" id="lihat_bidangbank" >Bank Data</a>
            <!--<a class="collapse-item" style="cursor:pointer;" id="lihat_bankdata" >Lihat Bankdata</a>-->
          </div>
        </div>
      </li>

      <script>      
			$(document).ready(function(){
				$("#lihat_frontoffice").click(function(){
					var loading = $("#pra_tabel");
					var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
					tampilkan.hide();
					loading.fadeIn(); 
          $.post('<?php echo $this->config->item('alamat_frontoffice');?>/index.php/Frontoffice/api_tampilkan_surat_frontoffice',{ data:"okbro"},
					function(data,status){
						loading.fadeOut();
						tampilkan.html(data);
						tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat Front Office');
            posisi.fadeIn(2000);
					});
				});
			  });
			</script>

      <script>      
        $(document).ready(function(){
          $('#unggah_fronoffice_99').click(function(){
            var loading = $('#pra_tabel');
            var tampilkan = $('#penampil_tabel');
            tampilkan.hide();
            loading.fadeIn(); 
            $.post('<?php echo site_url('/Frontoffice/buka_frontoffice'); ?>',{ data:'okbro'},
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
        $("#lihat_sekretariat").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('alamat_sekretariat');?>/index.php/Frontoffice/api_tampilkan_surat_sekretariat',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat Sekretariat');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>

      <script>      
      $(document).ready(function(){
        $("#lihat_bidangkk").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('alamat_kkpegawai');?>/index.php/Frontoffice/api_tampilkan_surat_kkpegawai',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat Bidang Kesejahteraan dan Kinerja');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>

      <script>      
      $(document).ready(function(){
        $("#lihat_bidangpi").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('alamat_pipegawai');?>/index.php/Frontoffice/api_tampilkan_surat_pipegawai',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat Bidang Perencanaan dan Informasi');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>


      <script>      
      $(document).ready(function(){
        $("#lihat_bidangpk").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('alamat_pkpegawai');?>/index.php/Frontoffice/api_tampilkan_surat_pkpegawai',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat Bidang Pengembangan Karier');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>
      

      <script>      
      $(document).ready(function(){
        $("#lihat_bidangpp").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('alamat_ppegawai');?>/index.php/Frontoffice/api_tampilkan_surat_ppegawai',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat Bidang Pengendalian Pegawai');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>
      

      <script>      
      $(document).ready(function(){
        $("#lihat_bidangupt").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('alamat_uptppk');?>/index.php/Frontoffice/api_tampilkan_surat_uptppk',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat UPT PPK');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>

      <script>      
      $(document).ready(function(){
        $("#lihat_bidangbank").click(function(){
          var loading = $("#pra_tabel");
          var tampilkan = $("#penampil_tabel");
          var posisi= $("#penampil_tabel_header_atas");
			  	posisi.fadeOut(500);
          tampilkan.hide();
          loading.fadeIn(); 
          $.post('<?php echo $this->config->item('bank_data');?>/index.php/Frontoffice/api_tampilkan_surat_bankdata',{ data:"okbro"},
          function(data,status){
            loading.fadeOut();
            tampilkan.html(data);
            tampilkan.fadeIn(2000);
            posisi.html('Posisi >> Ruang Surat Bank Data');
            posisi.fadeIn(2000);
          });
        });
        });
      </script>
      <!-- End tambahan 30/09/2020 -->

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAgenda" aria-expanded="true" aria-controls="collapseAgenda">
          <i class="fas fa-fw fa-book"></i>
          <span>Agenda Kaban</span>
        </a>
        <div id="collapseAgenda" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded" onclick='$("#cetak_laporan").show();$("#cetak_laporan_periodik_agenda").hide();'>
            <h6 class="collapse-header">Ruang Virtual:</h6>
            <a class="collapse-item" style="cursor:pointer;" id="agenda_hari_ini" >Agenda hari ini</a>
            <a class="collapse-item" style="cursor:pointer;" id="kelola_agenda" >Kelola Agenda</a>
            <a class="collapse-item" style="cursor:pointer;" id="kirim_undangan_acara" data-toggle="modal" data-target="#myModal_suratbaru"><span class="badge badge-primary badge-counter" style="font-size:12px;">Kirim Undangan Acara</span></a>
          </div>
        </div>
      </li>

      <script>      
			$(document).ready(function(){
				$("#kirim_undangan_acara").click(function(){
					var loading = $("#pra_myModal_suratbaru");
					var tampilkan = $("#penampil_myModal_suratbaru");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/sekretariat_unggahsuratbaru');?>',{ data:"okbro"},
					function(data,status){
						loading.fadeOut();
						tampilkan.html(data);
						tampilkan.fadeIn(2000);
					});
				});
			  });
			</script>

      <?php
      $tabel_ok=$this->session->userdata('tabel');
      $fields = $this->viewfrommyframework->penarik_semua_nama_kolom_sebuah_tabel_view('tbagenda_kerja');
      ?>
      <script>      
			$(document).ready(function(){
				$("#agenda_hari_ini").click(function(){
					var loading = $("#pra_tabel");
					var tampilkan = $("#penampil_tabel");
          $("#cetak_laporan").hide();
          $("#cetak_laporan_periodik_agenda").show();
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/baca_agenda_9001');?>',{ data:"okbro"},
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
				$("#kelola_agenda").click(function(){
					var loading = $("#pra_tabel");
					var tampilkan = $("#penampil_tabel");
          $("#cetak_laporan").hide();
          $("#cetak_laporan_periodik_agenda").show();
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_agenda_new/tbagenda_kerja/'.$fields[0].'/desc');?>',{ data:"okbro"},
					function(data,status){
						loading.fadeOut();
						tampilkan.html(data);
						tampilkan.fadeIn(2000);
					});
				});
			  });
			</script>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-envelope"></i>
          <span>Kelola Surat</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded" onclick='$("#cetak_laporan").show();$("#cetak_laporan_periodik_agenda").hide();'>
            <h6 class="collapse-header">Ruang Virtual:</h6>
            <a class="collapse-item" style="cursor:pointer;" id="surat_berkas_terusan" >Surat Terusan FrontOffice</a>
            <a class="collapse-item" style="cursor:pointer;" id="surat_berkas" >Surat Masuk Bidang</a>
            <a class="collapse-item" style="cursor:pointer;" id="surat_berkas_balasan" >Surat Balasan Sekretariat</a>
            <!--<a class="collapse-item" style="cursor:pointer;" id="catatan_surat_keluar" >Arsip Surat Keluar Bidang</a>-->
            <a style="cursor:pointer;color:white;margin-left:20px;margin-bottom:10px;" class="d-sm-inline-block btn btn-sm btn-warning shadow-sm" data-toggle="modal" data-target="#myModal_suratbaru" id="unggah_surat_berkas" ><i class="fas fa-paper-plane fa-sm text-white-100"></i> Kirim Surat Baru </a>
            <a style="cursor:pointer;color:white;margin-left:20px;margin-bottom:10px;" class="d-sm-inline-block btn btn-sm btn-success shadow-sm buat_surat_baru" id="buat_surat_baru" data-toggle="modal" data-target="#myModal_suratbaru"><i class="fas fa-edit fa-sm text-white-100"></i> Menulis Surat Baru</a>
            <a class="collapse-item" style="cursor:pointer;" id="catatan_surat_keluar" >Catatan Surat Keluar</a>
          </div>
        </div>
      </li>

      <script>      
			$(document).ready(function(){
				$(".buat_surat_baru").click(function(){
					var loading = $("#pra_myModal_suratbaru");
					var tampilkan = $("#penampil_myModal_suratbaru");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/iframe_editor');?>',{ data:"okbro"},
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
				$("#unggah_surat_berkas").click(function(){
					var loading = $("#pra_myModal_suratbaru");
					var tampilkan = $("#penampil_myModal_suratbaru");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/sekretariat_unggahsuratbaru');?>',{ data:"okbro"},
					function(data,status){
						loading.fadeOut();
						tampilkan.html(data);
						tampilkan.fadeIn(2000);
					});
				});
			  });
			</script>

      <!-- Script untuk pemanggilan ajax -->
      <script>      
          $(document).ready(function(){
            $("#catatan_surat_keluar").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_surat_keluar_new');?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 

      <!-- Modal -->
      <div class="modal fade" id="myModal_suratbaru" role="dialog" ><!--//xx8-->
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Ruang Kaban BKD Sulsel</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
          <center>
          <div id='pra_myModal_suratbaru' style='width:65%;' align='center' >
          <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
          <!--
          <div class="progress" style="margin-top:50px; height:20px">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            mohon tunggu...
            </div>
          </div>
          -->
          </center>
          <div id=penampil_myModal_suratbaru align="center" style='width:100%;overflow:auto;'></div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
        </div>
      </div> 

      <!-- Modal -->
      <div class="modal fade" id="myModal_suratbaru_sedang" role="dialog" style="z-index:100000;"><!--//xx8-->
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Ruang Kaban BKD Sulsel</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
          <center>
          <div id='pra_myModal_suratbaru_sedang' style='width:65%;' align='center' >
          <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
          <!--
          <div class="progress" style="margin-top:50px; height:20px">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            mohon tunggu...
            </div>
          </div>
          -->
          </center>
          <div id=penampil_myModal_suratbaru_sedang align="center" style='width:100%;overflow:auto;'></div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
        </div>
      </div> 

      <!-- Nav Item - Utilities Collapse Menu -->
      <!-- Tambahan Script -->
      <script>
      $(document).ready(function(){
        $("#searchTable").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#myDIV *").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
      </script>

      <?php 
      $tables = $this->viewfrommyframework->penarik_semua_nama_tabel_view();
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-database"></i>
          <span>CRUID Basisdata</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar" >
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Nama Tabel:</h6>
            <input class="form-control" style="width:80%; margin-left:20px;" id="searchTable" type="text" placeholder="Search table...">
            <div id="myDIV" onclick='$("#cetak_laporan").show();$("#cetak_laporan_periodik_agenda").hide();'>
            <?php foreach ($tables as $key=>$table) { 
              if($table!=='admin' && $table!=='induk_pegawai'){
              ?>
            <?php if($table!=='surat_balasan_tamupegawai'){?>
            <a class="collapse-item" style="cursor:pointer;" id="<?php echo $table; ?>"><?php 
              $ok=explode('_',$table);
              $ok_lagi=ucwords(implode(' ',$ok));
              echo $ok_lagi; ?></a>
            <?php }else{ ?>
            <a class="collapse-item" style="cursor:pointer;" id="<?php echo $table; ?>">Surat Balasan TamuPeg</a>
          <?php }
              }
        } ?>
            </div><!--myDIV-->
          </div>
        </div>
      </li>

      
      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#ubahpassword" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-user-secret"></i>
          <span>Kelola Password</span>
        </a>
        <div id="ubahpassword" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Kelola Password:</h6>
            <?php $listTable=$this->viewfrommyframework->table_pemilik_kolom_password_dan_kolom_perujuk_view('password'); //xx4
                  foreach($listTable as $key=>$unit){
                    if($unit[0]!=='induk_pegawai' && $unit[0]!=='admin'){
                      if($unit[0]=='identpeg'){
            ?>
                        <a class="collapse-item" style="cursor:pointer;" id="<?php echo "password_".$unit[0]; ?>"><?php echo "Password Pegawai"; ?></a> 
            <?php     }else{ ?>
                        <a class="collapse-item" style="cursor:pointer;" id="<?php echo "password_".$unit[0]; ?>"><?php echo "Password ".ucwords($unit[0]); ?></a> 
            <?php
                      }
                    }
                  }
            ?>
          </div>
        </div>
      </li>
      
      
      <?php
            foreach($listTable as $key=>$unit){//xx2
              if($unit[0]!=='induk_pegawai' && $unit[0]!=='admin'){
                echo "<script> 
                      $(document).ready(function(){
                        $(\"#password_".$unit[0]."\").click(function(){
                          var loading = $(\"#pra_tabel\");
                          var tampilkan = $(\"#penampil_tabel\");
                          tampilkan.hide();
                          loading.fadeIn(); 
                          $.post('".site_url("/Frontoffice/tampilkan_form_ubah_password/".$unit[0]."/".$unit[1]."/desc")."',{ data:\"okbro\"},
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
            }
            ?>
      
      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsevirtual" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-desktop"></i>
          <span>Meja Kerja Virtual</span>
        </a>
        <div id="collapsevirtual" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded" onclick='$("#cetak_laporan").show();$("#cetak_laporan_periodik_agenda").hide();'>
            <h6 class="collapse-header">Kelola:</h6>
            <a class="collapse-item" style="cursor:pointer;" id="user_unggah_ok" data-toggle="modal" data-target="#myModal_suratbaru">User Unggah</a>
            <a class="collapse-item" style="cursor:pointer;" id="notifikasi_surat_ok">Notifikasi Surat</a>
            <a class="collapse-item" style="cursor:pointer;" id="notifikasi_nota">Notifikasi Nota</a>
            <a class="collapse-item" style="cursor:pointer;" id="buka_frontoffice"><button class="btn btn-info btn-xs">Front Office</button></a>
          </div>
        </div>
      </li>

      <?php 
      $base=base_url();
      $alamat=$this->enkripsi->enkapsulasiData($base);
      ?>
      <script>      
        $(document).ready(function(){
          $('#buka_frontoffice').click(function(){
            var loading = $('#pra_tabel');
            var tampilkan = $('#penampil_tabel');
            tampilkan.hide();
            loading.fadeIn(); 
            $.post('<?php echo site_url('/Frontoffice/buka_frontoffice/'.$alamat); ?>',{ data:'okbro'},
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
				$("#user_unggah_ok").click(function(){
					var loading = $("#pra_myModal_suratbaru");
					var tampilkan = $("#penampil_myModal_suratbaru");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/sekretariat_unggahsuratbaru');?>',{ data:"okbro"},
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
          $('#notifikasi_nota').click(function(){
            var loading = $('#pra_tabel');
            var tampilkan = $('#penampil_tabel');
            tampilkan.hide();
            loading.fadeIn(); 
            $.post('<?php echo site_url('/Frontoffice/pesan_rencana_konstruksi'); ?>',{ data:'okbro'},
            function(data,status){
              loading.fadeOut();
              tampilkan.html(data);
              tampilkan.fadeIn(2000);
            });
          });
        });
      </script> 

      <!-- Script untuk pemanggilan ajax -->
      <script>      
        $(document).ready(function(){
          $('#notifikasi_surat_ok').click(function(){
            var loading = $('#pra_tabel');
            var tampilkan = $('#penampil_tabel');
            tampilkan.hide();
            loading.fadeIn(); 
            $.post('<?php echo site_url('/Frontoffice/notifikasi_surat_total'); ?>',{ data:'okbro'},
            function(data,status){
              loading.fadeOut();
              tampilkan.html(data);
              tampilkan.fadeIn(2000);
            });
          });
        });
      </script> 

      
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Addons
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Login Screens:</h6>
            <a class="collapse-item" href="#">Login</a>
            <a class="collapse-item" href="#">Register</a>
            <a class="collapse-item" href="#">Forgot Password</a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Other Pages:</h6>
            <a class="collapse-item" href="#">404 Page</a>
            <a class="collapse-item" href="#">Blank Page</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="#"><!--charts.html">-->
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="#"><!--tables.html">-->
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" id="kolom_cari_umum" class="form-control bg-light border-0 small" placeholder="Search...untuk pencarian global di seluruh tabel basisdata" aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="cari_umum">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#cari_umum").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              var kolom_cari_umum = $('#kolom_cari_umum').val();
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/search_general');?>',{ data:kolom_cari_umum},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw" style="font-size:17px;"></i>
                <!-- Counter - Messages -->
                <span id="counter_surat_masuk" class="badge badge-danger badge-counter" style="margin-top:-15px;"></span>
              </a>

              <script>      
              $(document).ready(function(){
                  var tampilkan = $("#counter_surat_masuk");
                  $.post('<?php echo site_url('/Frontoffice/baca_counter_surat_total/echo');?>',{ data:"okbro"},
                  function(data,status){
                    tampilkan.html(data);
                  });
                });
                
              </script> 

              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Notifikasi inbox untuk setiap jenis surat
                </h6>

                <a class="dropdown-item d-flex align-items-center" style="cursor:pointer;" id="notif_surat_masuk">
                  <div class="dropdown-list-image mr-3">
                    <i class="fas fa-envelope fa-fw" style="font-size:30px;color:#2C9FAF"></i>
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="">
                    <div class="text-truncate">Inbox surat masuk
                    <span id="counter_surat_masuk_masuk" class="badge badge-danger badge-counter"></span></div>
                    <div class="small text-gray-500">Jumlah surat belum terbaca <span id="surat_masuk_kecil"></span></div>
                  </div>
                </a>
                <script>      
                $(document).ready(function(){
                    var tampilkan = $("#counter_surat_masuk_masuk");
                    var tampilan_kecil = $("#surat_masuk_kecil");
                    $.post('<?php echo site_url('/Frontoffice/baca_counter_surat_masuk/echo');?>',{ data:"okbro"},
                    function(data,status){
                      tampilkan.html(data);
                      if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
                    });
                  });
                </script> 

                <script>      
                $(document).ready(function(){
                  $("#notif_surat_masuk").click(function(){
                    var loading = $("#pra_tabel");
                    var tampilkan = $("#penampil_tabel");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_new');?>',{ data:"okbro"},
                    function(data,status){
                      loading.fadeOut();
                      tampilkan.html(data);
                      tampilkan.fadeIn(2000);
                    });
                  });
                  });
                </script> 

                <a class="dropdown-item d-flex align-items-center" style="cursor:pointer;" id="notif_surat_terusan">
                  <div class="dropdown-list-image mr-3">
                    <i class="fas fa-envelope fa-fw" style="font-size:30px;color:#17A673"></i>
                    <div class="status-indicator"></div>
                  </div>
                  <div class="">
                    <div class="text-truncate">Inbox surat terusan
                    <span id="counter_surat_masuk_terusan" class="badge badge-danger badge-counter" style="margin-top:-15px;"></span></div>
                    <div class="small text-gray-500">Jumlah surat belum terbaca <span id="surat_masuk_terusan"></span></div>
                  </div>
                </a>
                <script>      
                $(document).ready(function(){
                    var tampilkan = $("#counter_surat_masuk_terusan");
                    var tampilan_kecil = $("#surat_masuk_terusan");
                    $.post('<?php echo site_url('/Frontoffice/baca_counter_surat_terusan/echo');?>',{ data:"okbro"},
                    function(data,status){
                      tampilkan.html(data);
                      if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
                    });
                  });
                </script> 

                <script>      
                $(document).ready(function(){
                  $("#notif_surat_terusan").click(function(){
                    var loading = $("#pra_tabel");
                    var tampilkan = $("#penampil_tabel");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_terusan_new_verifikasi');?>',{ data:"okbro"},
                    function(data,status){
                      loading.fadeOut();
                      tampilkan.html(data);
                      tampilkan.fadeIn(2000);
                    });
                  });
                  });
                </script> 

                <a class="dropdown-item d-flex align-items-center" style="cursor:pointer;" id="notif_surat_balasan">
                  <div class="dropdown-list-image mr-3">
                  <i class="fas fa-envelope fa-fw" style="font-size:30px;color:#F4B619"></i>
                    <div class="status-indicator bg-warning"></div>
                  </div>
                  <div class="">
                    <div class="text-truncate">Inbox surat balasan
                    <span id="counter_surat_masuk_balasan" class="badge badge-danger badge-counter" style="margin-top:-15px;"></span></div>
                    <div class="small text-gray-500">Jumlah surat belum terbaca <span id="surat_masuk_balasan"></span></div>
                  </div>
                </a>
                <script>      
                $(document).ready(function(){
                    var tampilkan = $("#counter_surat_masuk_balasan");
                    var tampilan_kecil = $("#surat_masuk_balasan");
                    $.post('<?php echo site_url('/Frontoffice/baca_counter_surat_balasan/echo');?>',{ data:"okbro"},
                    function(data,status){
                      tampilkan.html(data);
                      if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
                    });
                  });
                </script> 

                <script>      
                $(document).ready(function(){
                  $("#notif_surat_balasan").click(function(){
                    var loading = $("#pra_tabel");
                    var tampilkan = $("#penampil_tabel");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_surat_balasan_new');?>',{ data:"okbro"},
                    function(data,status){
                      loading.fadeOut();
                      tampilkan.html(data);
                      tampilkan.fadeIn(2000);
                    });
                  });
                  });
                </script> 
                <!--
                <a class="dropdown-item d-flex align-items-center" style="cursor:pointer;" id="notif_surat_arsip">
                  <div class="dropdown-list-image mr-3">
                    <i class="fas fa-envelope fa-fw" style="font-size:30px;color:#2653D4"></i>
                    <div class="status-indicator bg-info"></div>
                  </div>
                  <div class="">
                    <div class="text-truncate">Inbox arsip surat
                    <span id="counter_surat_masuk_arsip" class="badge badge-danger badge-counter" style="margin-top:-15px;"></span></div>
                    <div class="small text-gray-500">Jumlah surat belum terbaca <span id="surat_masuk_arsip"></span></div>
                  </div>
                </a>
                -->
                <script>      
                $(document).ready(function(){
                    var tampilkan = $("#counter_surat_masuk_arsip");
                    var tampilan_kecil = $("#surat_masuk_arsip");
                    $.post('<?php echo site_url('/Frontoffice/baca_counter_surat_arsip/echo');?>',{ data:"okbro"},
                    function(data,status){
                      tampilkan.html(data);
                      if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
                    });
                  });
                </script> 

                <script>      
                $(document).ready(function(){
                  $("#notif_surat_arsip").click(function(){
                    var loading = $("#pra_tabel");
                    var tampilkan = $("#penampil_tabel");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_surat_keluar_new');?>',{ data:"okbro"},
                    function(data,status){
                      loading.fadeOut();
                      tampilkan.html(data);
                      tampilkan.fadeIn(2000);
                    });
                  });
                  });
                </script> 

                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-sticky-note fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" style="margin-top:-15px;">3+</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Pesan | Nota Masuk
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">Dalam rencana konstruksi</span>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-success">
                      <i class="fas fa-donate text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    Dalam rencana konstruksi
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Dalam rencana konstruksi
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Tunjukkan semua nota</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin Ruang Kaban</span>
                <img class="img-profile rounded-circle" src="<?php echo base_url('login/p1.png');?>" alt="">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" style="cursor:pointer;" id="profil">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <!-- Script untuk memanggil halaman ptofil -->
                <script>      
                $(document).ready(function(){
                  $("#profil").click(function(){
                    var loading = $("#pra_tabel");
                    var tampilkan = $("#penampil_tabel");
                    tampilkan.hide();
                    loading.fadeIn(); 
                    $.post('<?php echo site_url('/Frontoffice/tampilkan_profil2');?>',{ data:"okbro"},
                    function(data,status){
                      loading.fadeOut();
                      tampilkan.html(data);
                      tampilkan.fadeIn(2000);
                    });
                  });
                  });
                </script> 
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#settingmodal">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <!--
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Keluar
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!--bariskode 01-->
          <!-- Page Heading -->
          <style>
            #cetak_laporan{
              display:none;
            }
            #cetak_laporan_periodik_agenda{
              display:none;
            }
          </style>
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h5 class="h5 mb-0 text-gray-800" id='penampil_tabel_header_atas'>Ruang Kaban BKD Prov. Sulawesi Selatan</h5>
            <!--INGAT UNTUK MENGHAPUS STYLE CLASS d-none  AGAR BISA TETAP ADA DI DALAM MODE MOBILE/HP-->
            <span><span id='tombol_tambahan'></span>
            <a style="cursor:pointer;color:white;" class="btn btn-sm btn-primary shadow-sm" id="cetak_laporan" data-toggle="modal" data-target="#myModal_suratbaru_sedang"><i class="fas fa-download fa-sm text-white-50"></i> Cetak Laporan</a></span>
            <a style="cursor:pointer;color:white;" class="btn btn-sm btn-danger shadow-sm" id="cetak_laporan_periodik_agenda" data-toggle="modal" data-target="#myModal_suratbaru_sedang"><i class="fas fa-download fa-sm text-white-50"></i> Cetak Agenda Periodik</a>
            
          </div>
          <?php
              $flag_oknew=$this->session->userdata('modal');
              if($flag_oknew!=='ok_new2'){
                echo "
                    <script>
                    $(document).ready(function(){
                      $(\"#cetak_laporan\").show();
                      $(\"#cetak_laporan_periodik_agenda\").hide();
                    });
                    </script>
                ";
              }else{
                echo "
                    <script>
                    $(document).ready(function(){
                      $(\"#cetak_laporan\").hide();
                      $(\"#cetak_laporan_periodik_agenda\").show();
                    });
                    </script>
                ";
              }
          ?>

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#cetak_laporan").click(function(){
              var loading = $("#pra_myModal_suratbaru_sedang");
              var tampilkan = $("#penampil_myModal_suratbaru_sedang");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/cetak_laporan');?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
              
          $(document).ready(function(){
            $("#cetak_laporan_periodik_agenda").click(function(){
              var loading = $("#pra_myModal_suratbaru_sedang");
              var tampilkan = $("#penampil_myModal_suratbaru_sedang");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/cetak_laporan_periodik_agenda');?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 
          <!--endbariskode 01-->
          


          <!-- Content Row -->
          <div class="row">

            
          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#surat_berkas").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_new');?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
          </script> 

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#surat_berkas_terusan").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_terusan_new_verifikasi');?>',{ data:"okbro"},
              //OLD
              //$.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_surat_terusan_new');?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#surat_berkas_balasan").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_surat_balasan_new');?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script>

          <!-- Script untuk pemanggilan ajax -->
          <?php 
          $tabel_ok=$this->session->userdata('tabel');
          if($tabel_ok)$fields = $this->viewfrommyframework->penarik_semua_nama_kolom_sebuah_tabel_view($tabel_ok);
          //echo "<br>OK BROOOOOO DALAM";
			    //echo "<br>INI NILAI MODAL tabel SETELAH: ".$this->session->userdata('modal');
			    //echo "<br>INI NILAI SESSION tabel SETELAH: ".$tabel_ok;
          ?>
          <script>      
          $(document).ready(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              <?php 
              if($this->session->userdata('modal')=='ok') {
              ?>
              $.post('<?php echo site_url("/Frontoffice/tampil_tabel_cruid/".$tabel_ok."/".$fields[0]."/desc");?>',{ data:"okbro"},//xxx ("/Frontoffice/tampil_tabel_cruid/".$table."/".$fields[0]."/desc")
              <?php $this->session->set_userdata('modal',NULL);} else if($this->session->userdata('modal')=='ok_new') { ?>
                $.post('<?php echo site_url('/Frontoffice/tampilkan_tabel_agenda_new/'.$tabel_ok.'/'.$fields[0].'/desc');?>',{ data:"okbro"},
              <?php $this->session->set_userdata('modal',NULL);
              }else if($this->session->userdata('modal')=='ok_new2') { ?>
                $.post('<?php echo site_url('/Frontoffice/baca_agenda_9001');?>',{ data:"okbro"},
              <?php $this->session->set_userdata('modal',NULL);$this->session->set_userdata('flag_9001',NULL);
              }else {?>
              $.post('<?php echo site_url('/Frontoffice/tampilkan_tombol_baca_surat');?>',{ data:"okbro"},
              <?php }?>
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            
          </script> 
          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#dashboard_home").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url('/Frontoffice/tampilkan_tombol_baca_surat');?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 

          <!--Skrip untuk menampilkan modal saat window onload-->
          <script type="text/javascript">
              var ok=2;
              $(window).on('load',function(){
                  <?php (isset($data_post_enkrip_hex)|| isset($pesan_kirim_surat) || isset($pesan_kirim_berkas))?$ok=2:$ok=3;  ?>
                  let antara=<?php echo $ok ?>;
                  $('#myModal').modal('show');
                  let loading = $("#pra");
                  let loading_api = $("#pra_api");
                  let tampilkan = $("#penampil");
                  let tampilkan_api = $("#penampil_api");
                  tampilkan.hide();
                  tampilkan_api.hide();
                  loading.fadeIn(); 
                  if(antara==2){
                    loading.fadeOut();
                    tampilkan.fadeIn(2000);
                    /*$.post('<?php echo site_url('/Frontoffice/tes71');?>',{ data:"okbro"},*/
                    <?php 
                      $this->load->library('enkripsi');
                      $token=$this->enkripsi->enkapsulasiData('andisinra')
                    ?>
                    $.post('<?php echo $this->config->item('bank_data');?>/index.php/Frontoffice/read_alamat_web/<?php echo $token; ?>',{ data:"okbro"},
                    function(data,status){
                      loading_api.fadeOut();
                      tampilkan_api.html(data);
                      tampilkan_api.fadeIn(2000);
                    });
                  }
              });
          </script>

           
			<?php
      foreach($tables as $table){//xx2
        $fields = $this->viewfrommyframework->penarik_semua_nama_kolom_sebuah_tabel_view($table);
        echo "<script> 
              $(document).ready(function(){
                $(\"#".$table."\").click(function(){
                  var loading = $(\"#pra_tabel\");
                  var tampilkan = $(\"#penampil_tabel\");
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$fields[0]."/desc")."',{ data:\"okbro\"},
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
      ?>

          <!-- Content Row -->
          <?php //echo "OK BROW DISINIMI MAIN";?>
          <div class="row">

            <!-- Content Column -->
            <div class="col-xl-3 col-md-6 mb-4" id="okbro" style='overflow:auto;' >
            <!-- Untuk header penampil_tabel -->
            <center>
              <div id='pra_tabel' style='width:40%;display:none;' align='center' >
              <i class="fa-3x fas fa-spinner fa-pulse" style="color:#97BEE4"></i>
              <!--
              <div class="progress" style="margin-top:50px; height:20px">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                mohon tunggu...
                </div>
              </div>
              -->
              </div>
            </center>
            <div id='penampil_tabel' align="center" style='width:100%;overflow:auto;'></div>
            
            </div>

            
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Sistem Terintegrasi BKD Provinsi Sulawesi Selatan</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Apakah benar-benar hendak meninggalkan aplikasi?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Klik tombol "Keluar" di bawah untuk mengakhiri sesi anda.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <a class="btn btn-primary" href="<?php echo base_url('index.php/login/login/logout') ?>">Keluar</a>
        </div>
      </div>
    </div>
  </div>

  
  <!-- Logout Modal-->
  <div class="modal fade" id="settingmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Mohon maaf halaman Setting dalam rencana konstruksi</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Direncanakan untuk mengalihkan dashboard admin ke pustaka javascript yang berbeda dan boostrap 5.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

<?php
  if(isset($data_post_enkrip_hex) || isset($pesan_kirim_surat) || isset($pesan_kirim_berkas)) {
			//alert('Selamat:\nSurat dan Berkas pendukung sukses diunggah');
			echo "
      <!-- Modal -->
      <div class=\"modal fade\" id=\"myModal\" role=\"dialog\" style=\"z-index:100000;\">
        <div class=\"modal-dialog modal-lg\">
        
        <!-- Modal content-->
        <div class=\"modal-content\">
          <div class=\"modal-header\">
          <center>Rincian total file surat dan berkas yang hendak dikirim</center>
          <!--<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>-->
          </div>
          <div class=\"modal-body\">
          <center>
          <div id='pra' style='width:65%;' align='center' >
          <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
          <!--
          <div class=\"progress\" style=\"margin-top:50px; height:20px\">
            <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
            mohon tunggu...
            </div>
          </div>
          -->
          </div>

          <div id='pra_api' style='width:65%;' align='center' >
          <i class='fa-3x fas fa-spinner fa-pulse' style='color:#97BEE4'></i>
          <!--
          <div class=\"progress\" style=\"margin-top:50px; height:20px\">
            <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
            mohon tunggu data dari bank data...
            </div>
          </div>
          -->
          </div>
          </center>

          <div id=penampil_api align=\"center\" style='width:100%;'></div>
          <div id=penampil align=\"center\" style='width:100%;'>
          ";
            
          //if($pesan_kirim_surat!==FALSE){echo "<br>INI OKSURAT: ".$pesan_kirim_surat;}
          //if($pesan_kirim_berkas!==FALSE){echo "<br>INI OKBERKAS: ".$pesan_kirim_berkas;}
          if(!(isset($pesan_kirim_surat) || isset($pesan_kirim_berkas))) {
            //baca menggunakan API disini:


            echo "<br>INI UKURAN POST: ".strlen($data_post_enkrip_hex)." bytes<br>";
            $ok=trim(ini_get('post_max_size'),'M');
            $ok=$ok*1024*1024;
            echo "BATAS MAKSIMUM ADALAH: ".$ok." bytes";
            if(strlen($data_post_enkrip_hex)>$ok) {alert('file anda melampaui batas upload\nbatas ukuran kirim file terkirim adalah 40M\nanda dapat menyampaikan ke admin server \nuntuk merubah nilai post_max_size pada PHP.ini');} else{
              echo "
              <form name=\"myform\" id=\"kirim_terusan\" action=\"".site_url('Frontoffice/coba_kirim')."\" method=\"POST\">
                <input type=\"hidden\" name=\"data_post_enkrip_hex\" value=\"".$data_post_enkrip_hex."\">
                <input type=\"hidden\" name=\"terusan\" value=\"TRUE\">
                <input type=\"hidden\" name=\"asal_surat\" value=\"".$_SERVER['HTTP_REFERER']."\">
                <button id=\"Link\" class=\"btn btn-primary\" onclick=\"document.myform.submit()\" >Kirim</button>
              </form>


              ";
            }
          } else {
            if(isset($pesan_kirim_surat)) {
              echo('Info Surat: Surat anda sukses terkirim<br>');
            }else{
              echo('Info Surat: Surat anda gagal terkirim atau tidak ada surat yang diunggah sebelumnya');}
            if(isset($pesan_kirim_berkas)) {
              echo('Info Berkas: Berkas anda sukses terkirim');
            }else{
              echo('Info Berkas: Berkas anda gagal terkirim atau tidak ada berkas yang diunggah sebelumnya');}
          }
          echo "
          </div>
          <div class=\"modal-footer\">
          <button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\">Close</button>
          </div>
        </div>
        
        </div>
      </div>
      ";
		} else {
			//alert('Maaf Surat dan Berkas Anda Gagal di unggah \natau Anda Belum Unggah Surat dan Berkas');
		}
  ?>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
  
  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo base_url('/dashboard/vendor/jquery/jquery.min.js');?>"></script>
  <script src="<?php echo base_url('/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo base_url('/dashboard/vendor/jquery-easing/jquery.easing.min.js');?>"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url('/dashboard/js/sb-admin-2.min.js');?>"></script>

  <!-- Page level plugins -->
  <script src="<?php echo base_url('/dashboard/vendor/chart.js/Chart.min.js');?>"></script>

  <!-- Page level custom scripts -->
  <script src="<?php echo base_url('/dashboard/js/demo/chart-area-demo.js');?>"></script>
  <script src="<?php echo base_url('/dashboard/js/demo/chart-pie-demo.js');?>"></script>
  
</body>

</html>
