<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Bank Data BKD Prov. Sulawesi Selatan</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="<?php echo base_url('assets/assets/css/main.css');?>" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<link href="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
		<script src="<?php echo base_url('/public/vendor3.4.1/jquery/3.4.1/jquery.min.js'); ?>"></script>
		<script src="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/js/bootstrap.min.js'); ?>"></script>
	</head>
	<body class="landing">

		<!-- Header -->
			<header id="header" class="alt">
				<a style="float:left;margin-left:5px;">Sistem Terintegrasi BKD Sulsel</a>
				<a href="#nav">Menu</a>
			</header>

		<!-- Nav -->
			<nav id="nav">
				<ul class="links">
					<li><a href="index.html">Home</a></li>
					<li><a href="<?php echo site_url('Akuntamupegawai/index'); ?>">Lihat Akun Saya</a></li>
					<!--<li><a style="cursor:pointer;" id="loginakun" onclick="jQuery.noConflict();$('#myModal1').modal('show');">Lihat Akun Saya</a></li>-->
					<li><a href="#four">Kirim Pesan ke Admin</a></li>
					<li><a style="cursor:pointer;" data-toggle="modal" data-target="#myModal" id="register" onclick="jQuery.noConflict();$('#myModal').modal('show');">Register (By Admin)</a></li>
					<li><a style="cursor:pointer;" id="loginbyadmin" onclick="jQuery.noConflict();$('#myModal1').modal('show');">Login Admin Bank Data</a></li>
					<li><a href="<?php echo site_url('login/login/logout'); ?>">Logout</a></li>
				</ul>
			</nav>

		<!-- Banner -->
			<section id="banner">
				<i><img src="<?php echo base_url('/assets/images/logo_sulsel.png');?>" class="logo_sulsel" /></i>
				
				<h2>Bank Data</h2>
				<p>Badan Kepegawaian Daerah Pemprov Sulsel</p>
				<ul class="actions">
					<li><a href="#" class="button big special" data-toggle="modal" data-target="#myModal" id="unggahberkas">Unggah Berkas</a></li>
					
				</ul>
			</section>

		<!-- One -->
<!---
			<section id="one" class="wrapper style1">
				<div class="inner">
					<article class="feature left">
						<span class="image"><img src="<?php echo base_url('assets/images/pic01.jpg');?>" alt="" /></span>
						<div class="content">
							<h2>Integer vitae libero acrisus egestas placerat  sollicitudin</h2>
							<p>Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est.</p>
							<ul class="actions">
								<li>
									<a href="#" class="button alt">More</a>
								</li>
							</ul>
						</div>
					</article>
					<article class="feature right">
						<span class="image"><img src="<?php echo base_url('assets/images/pic02.jpg');?>" alt="" /></span>
						<div class="content">
							<h2>Integer vitae libero acrisus egestas placerat  sollicitudin</h2>
							<p>Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est.</p>
							<ul class="actions">
								<li>
									<a href="#" class="button alt">More</a>
								</li>
							</ul>
						</div>
					</article>
				</div>
			</section>
-->
		<!-- Four -->
			<section id="four" class="wrapper style2 special">
				<div class="inner">
					<header class="major narrow">
						<h2>Kirim Pesan ke Admin</h2>
						<p>Tulis kritik dan saran anda</p>
					</header>
					<form action="#" method="POST">
						<div class="container 75%">
							<div class="row uniform 50%">
								<div class="6u 12u$(xsmall)">
									<input name="name" placeholder="Name" type="text" />
								</div>
								<div class="6u$ 12u$(xsmall)">
									<input name="email" placeholder="Email" type="email" />
								</div>
								<div class="12u$">
									<textarea name="message" placeholder="Message" rows="4"></textarea>
								</div>
							</div>
						</div>
						<ul class="actions">
							<li><input type="submit" class="special" value="Submit" /></li>
							<li><input type="reset" class="alt" value="Reset" /></li>
						</ul>
					</form>
				</div>
			</section>

		<!-- Footer -->
			<footer id="footer">
				<div class="inner">
					<ul class="icons">
						<li><a href="#" class="icon fa-facebook">
							<span class="label">Facebook</span>
						</a></li>
						<li><a href="#" class="icon fa-twitter">
							<span class="label">Twitter</span>
						</a></li>
						<li><a href="#" class="icon fa-instagram">
							<span class="label">Instagram</span>
						</a></li>
						<li><a href="#" class="icon fa-linkedin">
							<span class="label">LinkedIn</span>
						</a></li>
					</ul>
					<ul class="copyright">
						<li>&copy; Sistem Terintegrasi BKD Sulsel.</li>
						<li>Design: <a href="http://templated.co">TEMPLATED</a>.</li>
					</ul>
				</div>
			</footer>

		<!-- Scripts -->
			<script src="<?php echo base_url('assets/assets/js/jquery.min.js');?>"></script>
			<script src="<?php echo base_url('assets/assets/js/skel.min.js');?>"></script>
			<script src="<?php echo base_url('assets/assets/js/util.js');?>"></script>
			<!--[if lte IE 8]><script src="<?php echo base_url('assets/assets/js/ie/respond.min.js');?>"></script><![endif]-->
			<script src="<?php echo base_url('assets/assets/js/main.js');?>"></script>

			<script>      
			$(document).ready(function(){
				$("#unggahberkas").click(function(){
					var loading = $("#pra");
					var tampilkan = $("#penampil");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/frontoffice_unggahberkas');?>',{ data:"okbro"},
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
				$("#register").click(function(){
					var loading = $("#pra");
					var tampilkan = $("#penampil");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/frontoffice_register1');?>',{ data:"okbro"},
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
				$("#loginbyadmin").click(function(){
					var loading = $("#pra1");
					var tampilkan = $("#penampil1");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/frontoffice_login/loginbyadmin/');?>',{ data:"okbro"},
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
				$("#loginakun").click(function(){
					var loading = $("#pra1");
					var tampilkan = $("#penampil1");
					tampilkan.hide();
					loading.fadeIn(); 
					$.post('<?php echo site_url('/Frontoffice/frontoffice_login/loginakun/');?>',{ data:"okbro"},
					function(data,status){
						loading.fadeOut();
						tampilkan.html(data);
						tampilkan.fadeIn(2000);
					});
				});
			});
			
			</script>


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" style="z-index:100000;">
	<div class="modal-dialog modal-lg">
	
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		<h4 class="modal-title">BKD Provinsi Sulawesi Selatan</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
		<center>
		<div id='pra' style='width:65%;' align='center' >
		<div class="progress" style="margin-top:50px; height:20px">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:100%">
			mohon tunggu...
			</div>
		</div>
		</center>
		<div id=penampil align="center" style='width:100%;'></div>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
		</div>
	</div>
	
	</div>
</div>

<!-- Modal1 -->
<div class="modal fade" id="myModal1" role="dialog" style="z-index:100000;">
	<div class="modal-dialog modal-sm">
	
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		<!--<h4 class="modal-title"><center>Login Admin Front</center></h4>-->
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
		<center>
		<div id='pra1' style='width:65%;' align='center' >
		<div class="progress" style="margin-top:50px; height:20px">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:100%">
			mohon tunggu...
			</div>
		</div>
		</center>
		<div id=penampil1 align="center" style='width:100%;'></div>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal" >Close</button>
		</div>
	</div>
	
	</div>
</div>

<!--layer untuk modalku untuk login admin-->
<div id='modalku_login' style='background: #777; position:fixed; left:0;right:0;top:0;bottom:0;z-index:90000; opacity:0.9;'>
			</div>
			<div id='panel_login'  style=''>
			<iframe id="target_pdf" name="target_pdf" src="<?php echo site_url($src); ?>" style="left:5%;right:5%;top:5%;bottom:5%;border:0px solid #000;position:absolute;width:90%;height:70%; margin-top:10px;"></iframe>
			<button type="button" class="btn btn-info okbro" style="bottom:20px;right:20px; position:absolute;" >Login</button>
			<button type="button" class="btn btn-warning" style="bottom:20px;left:20px; position:absolute;" onclick='document.getElementById("panel_login").style.display="none";document.getElementById("modalku_login").style.display="none";'>Batal</button>
			</div>
			
			<style>
			#modalku_login, #panel_login {
				display:none;
			}
			#panel_login  {
				border-radius: 10px; background: #fff; position:fixed; left:30%;right:30%;top:20%;bottom:20%;z-index:90001;
			}
			@media screen and (max-width: 480px) {
			#panel_login {
				left:5%;
				right:5%;
			}
			.okbro_login{
				width:60px;
			}
			}
			</style>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

<?php 

	$this->load->helper('alert');
	if($data_upload){
		if($data_upload[0][0] || $data_upload[1][0]) {
			//alert('Selamat:\nSurat dan Berkas pendukung sukses diunggah');
			echo "
			<!--layer untuk modalku-->
			<div id='modalku' style='background: #777; position:fixed; left:0;right:0;top:0;bottom:0;z-index:90000; opacity:0.9;'>
			</div>
			<div id='panel'  style=''>
			<iframe id=\"target_pdf\" name=\"target_pdf\" src=\"".site_url($src)."\" style=\"left:5%;right:5%;top:5%;bottom:5%;border:0px solid #000;position:absolute;width:90%;height:70%\"></iframe>
			<button type=\"button\" class=\"btn btn-info okbro\" style=\"bottom:20px;right:20px; position:absolute;\" onclick='document.getElementById(\"panel\").style.display=\"none\";document.getElementById(\"modalku\").style.display=\"none\";'>Close</button>
			<button type=\"button\" class=\"btn btn-warning\" style=\"bottom:20px;left:20px; position:absolute;\">Klik >> untuk cetak</button>
			</div>
			
			<style>
			#panel {
				border-radius: 10px; background: #fff; position:fixed; left:30%;right:30%;top:20%;bottom:20%;z-index:90001;
			}
			@media screen and (max-width: 480px) {
			#panel {
				left:5%;
				right:5%;
			}
			.okbro{
				width:60px;
			}
			}
			</style>
			";
		} else {
			alert('Maaf Surat dan Berkas Anda Gagal di unggah \natau Anda Belum Unggah Surat dan Berkas');
		}
	} 

	if($src_register){
		//if($data_upload[0][0] || $data_upload[1][0]) {
			//alert('Selamat:\nSurat dan Berkas pendukung sukses diunggah');
			echo "
			<!--layer untuk modalku-->
			<div id='modalku' style='background: #777; position:fixed; left:0;right:0;top:0;bottom:0;z-index:90000; opacity:0.9;'>
			</div>
			<div id='panel'  style=''>
			<iframe id=\"target_pdf\" name=\"target_pdf\" src=\"".site_url($src_register)."\" style=\"left:5%;right:5%;top:5%;bottom:5%;border:0px solid #000;position:absolute;width:90%;height:70%\"></iframe>
			<button type=\"button\" class=\"btn btn-info okbro\" style=\"bottom:20px;right:20px; position:absolute;\" onclick='document.getElementById(\"panel\").style.display=\"none\";document.getElementById(\"modalku\").style.display=\"none\";'>Close</button>
			<button type=\"button\" class=\"btn btn-warning\" style=\"bottom:20px;left:20px; position:absolute;\">Klik >> untuk cetak</button>
			</div>
			
			<style>
			#panel {
				border-radius: 10px; background: #fff; position:fixed; left:30%;right:30%;top:20%;bottom:20%;z-index:90001;
			}
			@media screen and (max-width: 480px) {
			#panel {
				left:5%;
				right:5%;
			}
			.okbro{
				width:60px;
			}
			}
			</style>
			";
		//} else {
			//alert('Maaf Surat dan Berkas Anda Gagal di unggah \natau Anda Belum Unggah Surat dan Berkas');
		//}
	} 

?>
	</body>
</html>