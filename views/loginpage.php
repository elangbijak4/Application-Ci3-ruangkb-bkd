<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="zxx">
<!-- Head -->

<head>
    <title>Ruang Kaban <?php echo $this->config->item('nama_opd'); ?> Provinsi Sulawesi Selatan</title>
    <!-- Meta-Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="keywords" content="Key Login Form a Responsive Web Template, Bootstrap Web Templates, Flat Web Templates, Android Compatible Web Template, Smartphone Compatible Web Template, Free Webdesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design">
	

	<script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- //Meta-Tags -->
    <!-- Index-Page-CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/assets_login/css/style.css');?>" type="text/css" media="all">
    <!-- //Custom-Stylesheet-Links -->
    <!--fonts -->
    <!-- //fonts -->
    <link rel="stylesheet" href="<?php echo base_url('assets/assets_login/css/font-awesome.min.css');?>" type="text/css" media="all">
    <!-- //Font-Awesome-File-Links -->
	
	<!-- Google fonts -->
	<link href="//fonts.googleapis.com/css?family=Quattrocento+Sans:400,400i,700,700i" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Mukta:200,300,400,500,600,700,800" rel="stylesheet">
	<!-- Google fonts -->
	<link href="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url('/public/vendor3.4.1/jquery/3.4.1/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/js/bootstrap.min.js'); ?>"></script>

</head>
<!-- //Head -->
<!-- Body -->

<body>

<section class="main">
	<div class="layer">
		
		<div class="bottom-grid">
			<div class="logo">
				<h1> <img src="<?php echo base_url('/assets/images/logo_sulsel.png');?>" class="logo_sulsel" style="float:left;margin-right:10px;" /><a href="#">e-Ruang Kaban <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel</a></h1>
			</div>
			<div class="links" >
				<ul class="links-unordered-list">
					<li class="active">
						<a href="<?php echo $this->config->item('base_domain'); ?>/sistrabkdsulsel-ver02/" class="">Home</a>
					</li>
					<li class="active">
						<a href="<?php echo site_url('welcome/about'); ?>" class="" ><span style='color:white;'>About Us</span></a>
					</li>
					<li class="active">
						<a href="#" class="" data-toggle="modal" data-target="#myModal">Register</a>
					</li>
					<li class="active">
						<a href="<?php echo site_url('welcome/contact'); ?>" class="">Contact</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="content-w3ls">
			<div class="text-center icon">
				<span class="text-login" style="font-size:35px;color:#fff">Login Ruang Kaban</span>
			</div>
			<div class="content-bottom">
				<form action="<?php echo site_url('login/login/process') ?>" method="post" accept-charset="utf-8">
					<div class="field-group">
						<span class="fa fa-user" aria-hidden="true"></span>
						<div class="wthree-field">
							<input name="username" id="text1" type="text" value="" placeholder="Username" required>
						</div>
					</div>
					<div class="field-group">
						<span class="fa fa-lock" aria-hidden="true"></span>
						<div class="wthree-field">
							<input name="password" id="myInput" type="password" placeholder="Password" required>
						</div>
					</div>
					<div class="wthree-field">
						<button type="submit" class="btn">Masuk Ruang Kaban</button>
					</div>
					<ul class="list-login">
						<li class="switch-agileits">
							<label class="switch">
								<input type="checkbox" name="remember_me">
								<span class="slider round"></span>
								<span style='color:white;'>Remember me</span>
							</label>
						</li>
						<li>
							<a href="#" class="text-right" data-toggle="modal" data-target="#myModal3"><span style="color:white;">Forgot password</span></a>
						</li>
						<li class="clearfix"></li>
					</ul>
					<ul class="list-login">
						<li class="">
							<a href="#" class="" data-toggle="modal" data-target="#myModal">Register</a>
						</li>
						<li class="">
							<a href="#" class="" data-toggle="modal" data-target="#myModal2"><span style="color:white;">Butuh Bantuan?</span></a>
						</li>
						<li class="clearfix"></li>
					</ul>
				</form>
			</div>
		</div>
		<div class="bottom-grid1">
			<div class="copyright" >
				<p style="font-size:5px;">© 2020 <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel | Design:
					<a href="http://w3layouts.com">W3layouts</a>
				</p>
			</div>
		</div>
    </div>
</section>

<!-- Modal1 -->
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content" style='background-color: rgba(200, 200, 200, 0.95);'>
		<div class="modal-header">
		<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
		<h5 class="modal-title">
			<img src="<?php echo base_url('/assets/assets_login/images/LogoSulselH.png');?>" class="logo_sulsel" style="height:40px;width:auto;float:left;margin-right:20px;" />
			e-Office <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel
		</h5>
		</div>
		<div class="modal-body">
		<p>Silahkan mendaftar manual ke admin <?php echo $this->config->item('nama_opd'); ?> untuk mendapatkan password</p>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
		</div>
	</div>
	</div>
</div>	

<!-- Modal2 -->
<div class="modal fade" id="myModal2" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content" style='background-color: rgba(200, 200, 200, 0.95);'>
		<div class="modal-header">
		<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
		<h5 class="modal-title">
			<img src="<?php echo base_url('/assets/assets_login/images/LogoSulselH.png');?>" class="logo_sulsel" style="height:40px;width:auto;float:left;margin-right:20px;" />
			e-Office <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel
		</h5>
		</div>
		<div class="modal-body">
		<p>Silahkan Hubungi petugas terdekat</p>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
		</div>
	</div>
	</div>
</div>	

<div class="modal fade" id="myModal3" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content" style='background-color: rgba(200, 200, 200, 0.95);'>
		<div class="modal-header">
		<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
		<h5 class="modal-title">
			<img src="<?php echo base_url('/assets/assets_login/images/LogoSulselH.png');?>" class="logo_sulsel" style="height:40px;width:auto;float:left;margin-right:20px;" />
			e-Office <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel
		</h5>
		</div>
		<div class="modal-body">
		<p>Silahkan menghubungi super admin, password hanya dapat diberikan secara manual</p>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
		</div>
	</div>
	</div>
</div>	

<?php 
$error = $this->session->userdata('form_error');
if ($error)
{   
	echo "<script> alert('Harap mengisi kolom username atau password') </script>";
}

$error_isian = $this->session->userdata('login_salah');
if ($error_isian)
{
	echo "<script> alert('Username atau password anda salah') </script>";
}

$error_login = $this->session->userdata('percobaan_login');
if ($error_login)
{
	echo "<script> alert('Maaf Sesi anda kadaluarsa. Silahkan login lagi') </script>";
}

$ok_keluar = $this->session->userdata('keluar', 'keluar');
if ($ok_keluar)
{
	echo "<script> alert('Anda telah keluar') </script>";
}

$this->session->unset_userdata('form_error');
$this->session->unset_userdata('login_salah');
$this->session->unset_userdata('percobaan_login');
$this->session->unset_userdata('keluar');
?>
</body>
<!-- //Body -->
</html>
