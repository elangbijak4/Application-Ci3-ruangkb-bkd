<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Mamba Bootstrap Template - Index</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?php echo base_url('public/bootstrapmade/assets/img/favicon.png');?>" rel="icon">
  <link href="<?php echo base_url('public/bootstrapmade/assets/img/apple-touch-icon.png');?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo base_url('public/bootstrapmade/assets/vendor/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('public/bootstrapmade/assets/vendor/icofont/icofont.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('public/bootstrapmade/assets/vendor/boxicons/css/boxicons.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('public/bootstrapmade/assets/vendor/animate.css/animate.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('public/bootstrapmade/assets/vendor/venobox/venobox.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('public/bootstrapmade/assets/vendor/aos/aos.css');?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo base_url('public/bootstrapmade/assets/css/style.css');?>" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Mamba - v2.1.0
  * Template URL: https://bootstrapmade.com/mamba-one-page-bootstrap-template-free/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body style="background:#FFDF1B">
  <!-- ======= Header ======= -->
  <header id="header" style="background:#FEAF20;">
    <div class="container">

      <div class="logo float-left">
        <!-- Uncomment below if you prefer to use an image logo -->
        <a href="#" style="float:left;" id="logo_bkd9"><img src="<?php echo base_url('/assets/images/logo_sulsel.png');?>" alt="" class="img-fluid"></a>
        <h3 class="text-light" style="float:left;" id="judul_bkd9"><a href="#"><span>BKD Prov. Sulawesi Selatan</span></a></h3>
      </div>
      <style>
              #judul_bkd9{
                  font-size:18px;
                  margin-left:20px;
                  margin-top:10px;
                }
              @media screen and (max-width: 480px) {
                #logo_bkd9{
                  display:none;
                }
                #judul_bkd9{
                  font-size:16px;
                  margin-left:0px;
                }
                }

            </style>
      <nav class="nav-menu float-right d-none d-lg-block">
        <ul>
          <li class="active"><a href="<?php echo site_url('frontoffice'); ?>">Home</a></li>
          <li><a href="<?php echo site_url('welcome/about'); ?>">About Us</a></li>
          <!--<li><a href="<?php echo site_url('welcome/team'); ?>">Team</a></li>-->
          <li><a href="<?php echo site_url('welcome/contact'); ?>">Contact Us</a></li>
        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->

  <main id="main">
    <!-- ======= Our Team Section ======= -->
    <section id="team" class="team">
      <div class="container">

        <div class="section-title">
          <h2>Team Kami</h2>
          <p>Sebagai yang utama terlibat di dalam pembuatan proyek ini.</p>
        </div>

        <div class="row">

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up">
            <div class="member">
              <div class="pic"><img src="<?php echo base_url('public/bootstrapmade/assets/img/team/team-1.jpg');?>" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Drs. H. ASRI SAHRUN SAID</h4>
                <span>Kepala BKD Prov. Sulsel</span>
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <div class="pic"><img src="<?php echo base_url('public/bootstrapmade/assets/img/team/team-2.jpg');?>" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Andi Sinra</h4>
                <span>Product Manager</span>
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <div class="pic"><img src="<?php echo base_url('public/bootstrapmade/assets/img/team/team-3.jpg');?>" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Aslan Alwi</h4>
                <span>CTO</span>
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <div class="pic"><img src="<?php echo base_url('public/bootstrapmade/assets/img/team/team-4.jpg');?>" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Munirah Muslim</h4>
                <span>Accountant</span>
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Our Team Section -->
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>BKD Provinsi Sulawesi Selatan</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/mamba-one-page-bootstrap-template-free/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/jquery/jquery.min.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/jquery.easing/jquery.easing.min.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/php-email-form/validate.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/jquery-sticky/jquery.sticky.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/venobox/venobox.min.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/waypoints/jquery.waypoints.min.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/counterup/counterup.min.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/isotope-layout/isotope.pkgd.min.js');?>"></script>
  <script src="<?php echo base_url('public/bootstrapmade/assets/vendor/aos/aos.js');?>"></script>

  <!-- Template Main JS File -->
  <script src="<?php echo base_url('public/bootstrapmade/assets/js/main.js');?>"></script>

</body>

</html>