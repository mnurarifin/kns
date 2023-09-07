<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta name="google" content="notranslate">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Link of CSS files -->
  <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/app-assets/vendors/css/vendors.min.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/flaticon.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/remixicon.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/swiper-min.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/fancybox.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/jquery-ui-min.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/odometer.min.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/aos.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/responsive.css">
  <title><?= COMPANY_NAME ?></title>
  <link rel="icon" type="image/png" href="<?= BASEURL; ?>/assets/img/favicon.png">
  <script src="/assets/js/jquery.min.js"></script>
  <script src="<?= BASEURL; ?>/app-assets/vendors/js/vendors.min.js"></script>
  <script src="<?= BASEURL; ?>/app-assets/js/vue@3.2.36.js"></script>
</head>

<body>

  <!--Preloader starts-->
  <div class="preloader js-preloader">
    <img src="<?= BASEURL; ?>/assets/img/preloader.gif" alt="Image">
  </div>
  <!--Preloader ends-->

  <div class="body_overlay"></div>

  <!-- Page Wrapper End -->
  <div class="page-wrapper">

    <?= $this->include('Template/Pub/header') ?>
    <div><?php echo isset($content) ? $content : ''; ?></div>
    <?= $this->include('Template/Pub/footer') ?>

  </div>
  <!-- Page Wrapper End -->

  <!-- Back-to-top button Start -->
  <a href="javascript:void(0)" class="back-to-top bounce"><i class="ri-arrow-up-s-line"></i></a>
  <!-- Back-to-top button End -->

  <!-- Link of JS files -->
  <script src="<?= BASEURL; ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/form-validator.min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/contact-form-script.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/aos.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/swiper-min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/owl.carousel.min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/jquery-ui-min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/odometer.min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/jquery.countdown.min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/fancybox.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/jquery.appear.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/tweenmax.min.js"></script>
  <script src="<?= BASEURL; ?>/assets/js/main.js"></script>
</body>

</html>